<?php

declare(strict_types=1);

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Post;
use App\Services\PostService;
use Illuminate\Http\Request;

class PostController extends Controller
{
    protected PostService $postService;

    public function __construct(PostService $postService)
    {
        $this->postService = $postService;
    }

    /**
     * Toon overzicht van posts.
     */
    public function index(Request $request)
    {
        $latestPosts = $this->postService->getLatestPosts(8);

        $query = Post::query()
            ->with('categories')       // eager load categories
            ->with('user')             // auteur info
            ->where('is_published', true) // alleen gepubliceerde posts
            ->latest('published_at');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(fn ($q) => $q->where('title', 'like', "%{$search}%")
                ->orWhere('excerpt', 'like', "%{$search}%")
                ->orWhere('body', 'like', "%{$search}%")
            );
        }

        $posts = $query->paginate(10)->withQueryString();

        return view('frontend.posts.index', [
            'posts' => $posts,
            'latestPosts' => $latestPosts,
        ]);
    }

    /**
     * Toon één enkele post.
     */
    public function show(Post $post)
    {
        $latestPosts = $this->postService->getLatestPosts(8);

        $post->load('categories', 'user');

        return view('frontend.posts.show', [
            'post' => $post,
            'latestPosts' => $latestPosts,
        ]);
    }
}
