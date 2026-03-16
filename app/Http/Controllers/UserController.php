<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\UserIndexRequest;
use App\Http\Requests\UserStoreRequest;
use App\Http\Requests\UserUpdateRequest;
use App\Models\Role;
use App\Models\User;
use App\Services\MediaService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Throwable;

class UserController extends Controller
{
    protected $mediaService;

    public function __construct(MediaService $mediaService)
    {
        $this->mediaService = $mediaService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(UserIndexRequest $request)
    {
        $filters = $request->defaults();

        $users = User::query()
            ->with('role', 'media')
            ->search($filters['q'])
            ->roleFilter($filters['role'])
            ->statusFilter($filters['status'])
            ->verifiedFilter($filters['verified'])
            ->trashedFilter($filters['trashed'])
            ->sortBySafe($filters['sort'], $filters['dir'])
            ->paginate($filters['per_page'])
            ->withQueryString();

        $roles = Role::query()
            ->orderBy('name')
            ->get(['id', 'name']);

        return view('backend.users.index', [
            'users' => $users,
            'roles' => $roles,
            'filters' => $filters,
            'perPageAllowed' => [10, 25, 50, 100],
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $roles = Role::query()
            ->orderBy('name')
            ->get(['id', 'name']);

        return view('backend.users.create', ['roles' => $roles]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(UserStoreRequest $request)
    {
        /**
         * Als validatie faalt, komt code hier nooit:
         * Laravel redirect automatisch terug met $errors en old().
         */
        $data = $request->validated();

        try {
            DB::beginTransaction();

            $user = User::create([
                'name' => $data['name'],
                'email' => $data['email'],
                'role_id' => $data['role_id'],
                'is_active' => $data['is_active'],
                'email_verified_at' => $data['email_verified_at'], // komt uit prepareForValidation
                'password' => Hash::make($data['password']),
            ]);

            if($request->hasFile('image')) {
                $this->mediaService->upload(
                    $user,
                    $request->file('image'),
                    'users'
                );
            }

            DB::commit();

            /**
             * SUCCESS redirect:
             * - naar index
             * - flash 'success' => wordt getoond via x-backend.flash in shell
             */
            return redirect()
                ->route('backend.users.index')
                ->with('success', "User '{$user->name}' created successfully.");

        } catch (Throwable) {

            DB::rollBack();

            /**
             * ERROR redirect (business error/DB error):
             * - back() => terug naar formulier
             * - withInput() => old() vult alle inputs opnieuw
             * - with('error', ...) => flash alert in shell
             */
            return back()
                ->withInput()
                ->with('error', 'User could not be created. Please try again.');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user)
    {
        /**
         * Show is vaak nuttig in admin panels om read-only info te tonen:
         * - id, created_at, updated_at
         * - status, role, verified
         *
         * We eager load role om N+1 te vermijden als view role gebruikt.
         */
        $user->load('role', 'media');

        return view('backend.users.show', [
            'user' => $user,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $user)
    {
        /**
         * Route model binding:
         * - Laravel zoekt User op basis van {user} parameter
         * - Bestaat hij niet, dan geeft Laravel automatisch 404
         *
         * We laden roles voor de dropdown in het form.
         */
        $roles = Role::query()
            ->orderBy('name')
            ->get(['id', 'name']);

        $user->load('media');

        return view('backend.users.edit', [
            'user' => $user,
            'roles' => $roles,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UserUpdateRequest $request, User $user)
    {
        /**
         * validated() bevat enkel toegelaten velden.
         * Als validatie faalt:
         * - Laravel redirect automatisch terug
         * - $errors en old() worden gevuld
         * - x-backend.flash toont de errors
         */
        $data = $request->validated();

        try {
            DB::beginTransaction();
            /**
             * We updaten expliciet de velden die bij user horen.
             * Dit is duidelijker dan $user->update($data) omdat:
             * - password conditioneel is
             * - we willen exact zien wat aangepast wordt
             */
            $user->update([
                'name' => $data['name'],
                'email' => $data['email'],
                'role_id' => $data['role_id'],
                'is_active' => $data['is_active'],
                'email_verified_at' => $data['email_verified_at'],
            ]);
            /**
             * Password is optioneel bij update.
             * Alleen als het ingevuld is (niet null en niet leeg),
             * updaten we het password.
             */
            if (! empty($data['password'])) {
                $user->update([
                    'password' => Hash::make($data['password']),
                ]);
            }

            if($request->hasFile('image')) {
                $this->mediaService->replace(
                    $user,
                    $request->file('image'),
                    'users'
                );
            }

            DB::commit();

            /**
             * Success: terug naar edit of naar index.
             * Best practice in admin is vaak: terug naar edit zodat je verder
            kan aanpassen.
             * Jij kan dit later aanpassen naar index als je dat liever hebt.
             */
            return redirect()
                ->route('backend.users.edit', $user)
                ->with('success', "User '{$user->name}' updated successfully.");
        } catch (Throwable) {
            DB::rollBack();

            /**
             * Business/DB error:
             * - terug naar form
             * - behoud input
             * - toon error flash
             */
            return back()
                ->withInput()
                ->with('error', 'User could not be updated. Please try again.');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        try {
            /**
             * Soft delete:
             * de rij blijft bestaan,
             * maar deleted_at wordt ingevuld.
             */
            $user->delete();

            return redirect()
                ->route('backend.users.index')
                ->with('success', "User '{$user->name}' deleted successfully.");
        } catch (Throwable) {
            return back()
                ->with('error', 'User could not be deleted.');
        }
    }

    public function restore(int $id)
    {
        try {
            /**
             * withTrashed() zorgt ervoor dat ook soft deleted records
             * opzoekbaar zijn.
             */
            $user = User::withTrashed()->findOrFail($id);
            $user->restore();

            return redirect()
                ->route('backend.users.index')
                ->with('success', "User '{$user->name}' restored successfully.");
        } catch (Throwable) {
            return back()
                ->with('error', 'User could not be restored.');
        }
    }

    public function forceDelete(int $id)
    {
        try {
            /**
             * forceDelete() verwijdert de rij definitief uit de database.
             * Dit gebruik je alleen op records die al soft deleted zijn.
             */
            $user = User::withTrashed()->findOrFail($id);

            $name = $user->name;

            $user->forceDelete();

            return redirect()
                ->route('backend.users.index')
                ->with('success', "User '{$name}' permanently deleted.");
        } catch (Throwable) {
            return back()
                ->with('error', 'User could not be permanently deleted.');
        }
    }
}
