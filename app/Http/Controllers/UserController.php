<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\UserIndexRequest;
use App\Http\Requests\UserStoreRequest;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Throwable;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(UserIndexRequest $request)
    {
        $filters = $request->defaults();

        $users = User::query()
            ->with('role')
            ->search($filters['q'])
            ->roleFilter($filters['role'])
            ->statusFilter($filters['status'])
            ->verifiedFilter($filters['verified'])
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
    public function show(string $id): void
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $user): void
    {
        /**
         * Route model binding:
         * - Laravel zoekt User op basis van {user} parameter
         * - Bestaat hij niet, dan geeft Laravel automatisch 404
         *
         * We laden roles voor de dropdown in het form.
         */
        Role::query();
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id): void
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id): void
    {
        //
    }
}
