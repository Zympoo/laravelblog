<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\PostIndexRequest;
use App\Http\Requests\PostStoreRequest;
use App\Http\Requests\PostUpdateRequest;
use App\Models\Category;
use App\Models\Post;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Throwable;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(PostIndexRequest $request)
    {
        $filters = $request->defaults();
        $posts = Post::query()
            ->with(['user', 'categories'])
            ->search($filters['q'])
            ->authorFilter($filters['author'])
            ->categoryFilter($filters['category'])
            ->statusFilter($filters['status'])
            ->trashedFilter($filters['trashed'])
            ->sortBySafe($filters['sort'], $filters['dir'])
            ->paginate($filters['per_page'])
            ->withQueryString();
        $authors = User::query()
            ->orderBy('name')
            ->get(['id', 'name']);
        $categories = Category::query()
            ->orderBy('name')
            ->get(['id', 'name']);

        return view('backend.posts.index', [
            'posts' => $posts,
            'authors' => $authors,
            'categories' => $categories,
            'filters' => $filters,
            'perPageAllowed' => [10, 25, 50, 100],
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $authors = User::query()
            ->orderBy('name')
            ->get(['id', 'name']);

        $categories = Category::query()
            ->orderBy('name')
            ->get(['id', 'name']);

        return view('backend.posts.create', [
            'authors' => $authors,
            'categories' => $categories,
        ]);

    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(PostStoreRequest $request)
    {
        $data = $request->validated();
        try {
            DB::beginTransaction();

            $post = Post::create([
                'user_id' => $data['user_id'] ?? null,
                'title' => $data['title'],
                'slug' => $data['slug'],
                'excerpt' => $data['excerpt'] ?? null,
                'body' => $data['body'],
                'is_published' => $data['is_published'],
                'published_at' => $data['published_at'] ?? null,
            ]);
            // Many-to-many koppeling met categories
            $post->categories()->sync($data['categories'] ?? []);
            DB::commit();

            return redirect()
                ->route('backend.posts.index')
                ->with('success', "Post '{$post->title}' created successfully.");
        } catch (Throwable $e) {
            DB::rollBack();

            return back()
                ->withInput()
                ->with('error', 'Post could not be created. Please try again.');
        }

    }

    /**
     * Display the specified resource.
     */
    public function show(Post $post)
    {
        $post->load(['user', 'categories']);

        return view('backend.posts.show', [
            'post' => $post,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Post $post)
    {
        $post->load(['categories']);

        $authors = User::query()
            ->orderBy('name')
            ->get(['id', 'name']);

        $categories = Category::query()
            ->orderBy('name')
            ->get(['id', 'name']);

        return view('backend.posts.edit', [
            'post' => $post,
            'authors' => $authors,
            'categories' => $categories,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(PostUpdateRequest $request, Post $post)
    {
        $data = $request->validated();

        try {
            DB::beginTransaction();

            $post->update([
                'user_id' => $data['user_id'] ?? null,
                'title' => $data['title'],
                'slug' => $data['slug'],
                'excerpt' => $data['excerpt'] ?? null,
                'body' => $data['body'],
                'is_published' => $data['is_published'],
                'published_at' => $data['published_at'] ?? null,
            ]);

            $post->categories()->sync($data['categories'] ?? []);

            DB::commit();

            return redirect()
                ->route('backend.posts.edit', $post)
                ->with('success', "Post '{$post->title}' updated successfully.");
        } catch (Throwable $e) {
            DB::rollBack();

            return back()
                ->with('error', 'Post could not be updated. Please try again.');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Post $post)
    {
        try {
            $post->delete();

            return redirect()
                ->route('backend.posts.index')
                ->with('success', 'Post "{$post->title}" deleted successfully.');
        } catch (Throwable $e) {
            return back()
                ->with('error', 'Post could not be deleted. Please try again.');
        }
    }

    public function restore(int $id)
    {
        try {
            $post = Post::withTrashed()->findOrFail($id);

            $post->restore();

            return redirect()
                ->route('backend.posts.index')
                ->with('success', "Post '{$post->title}' restored successfully.");
        } catch (Throwable $e) {
            return back()
                ->with('error', 'Post could not be restored. Please try again.');
        }
    }

    public function forceDelete(int $id)
    {
        try {
            $post = Post::withTrashed()->findOrFail($id);

            $title = $post->title;

            $post->forceDelete();

            return redirect()
                ->route('backend.posts.index')
                ->with('success', "Post '{$title}' permanently deleted successfully.");
        } catch (Throwable $e) {
            return back()
                ->with('error', 'Post could not be permanently deleted. Please try again.');
        }
    }
}
