<?php

declare(strict_types=1);

namespace App\Services;

use App\Events\PostCreated;
use App\Events\PostUpdated;
use App\Models\Post;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class PostService
{
    public function __construct(protected MediaService $mediaService) {}

    public function create(array $data): Post
    {
        return DB::transaction(function () use ($data) {
            $post = Post::create([
                'user_id' => $data['user_id'] ?? null,
                'title' => $data['title'],
                'slug' => $data['slug'],
                'excerpt' => $data['excerpt'] ?? null,
                'body' => $data['body'],
                'is_published' => $data['is_published'],
                'is_featured' => $data['is_featured'],
                'published_at' => $data['published_at'] ?? null,
            ]);

            $post->categories()->sync($data['categories'] ?? []);

            if (isset($data['image']) && $data['image'] instanceof UploadedFile) {
                $this->mediaService->upload(
                    $post,
                    $data['image'],
                    'posts'
                );
            }

            PostCreated::dispatch($post);

            return $post;
        });
    }

    public function update(Post $post, array $data): Post
    {
        return DB::transaction(function () use ($post, $data): Post {
            $post->update([
                'user_id' => $data['user_id'] ?? null,
                'title' => $data['title'],
                'slug' => $data['slug'],
                'excerpt' => $data['excerpt'] ?? null,
                'body' => $data['body'],
                'is_published' => $data['is_published'],
                'is_featured' => $data['is_featured'],
                'published_at' => $data['published_at'] ?? null,
            ]);

            $post->categories()->sync($data['categories'] ?? []);

            if (isset($data['image']) && $data['image'] instanceof UploadedFile) {
                $this->mediaService->replace(
                    $post,
                    $data['image'],
                    'posts'
                );
            }

            PostUpdated::dispatch($post);

            return $post;
        });
    }

    public function getLatestPosts(int $limit = 8): Collection
    {
        return Post::query()
            ->with(['user', 'categories', 'media'])
            ->where('is_published', true)
            ->whereNotNull('published_at')
            ->latest('published_at')
            ->take($limit)
            ->get();
    }
}
