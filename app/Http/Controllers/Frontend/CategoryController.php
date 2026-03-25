<?php

declare(strict_types=1);

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Services\PostService;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    protected PostService $postService;

    public function __construct(PostService $postService)
    {
        $this->postService = $postService;
    }

    public function show(Category $category, Request $request)
    {
        $latestPosts = $this->postService->getLatestPosts(8);

        // Alleen gepubliceerde posts
        $posts = $category->posts()
            ->with('categories', 'user')
            ->where('is_published', true)
            ->latest('published_at');

        // Optioneel zoeken
        if ($request->filled('search')) {
            $search = $request->search;
            $posts->where(fn ($q) => $q->where('title', 'like', "%{$search}%")
                ->orWhere('excerpt', 'like', "%{$search}%")
                ->orWhere('body', 'like', "%{$search}%")
            );
        }

        $posts = $posts->paginate(10)->withQueryString();

        return view('frontend.categories.show', [
            'category' => $category,
            'posts' => $posts,
            'latestPosts' => $latestPosts,
        ]);
    }
}
