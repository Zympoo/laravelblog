<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Scope;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Category extends Model
{
    //
    use SoftDeletes;

    protected $fillable = [
        'name',
        'slug',
        'description',
    ];

    // Relaties
    // public function posts()
    // {
    // return $this->belongsToMany(Post::class);
    // }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    protected static function booted(): void
    {
        static::creating(function (Category $category) {
            if (blank($category->slug) && filled($category->name)) {
                $category->slug = Str::slug($category->name);
            }
        });
        static::updating(function (Category $category) {
            if ($category->isDirty('name') && blank($category->slug)) {
                $category->slug = Str::slug($category->name);
            }
        });
    }

    #[Scope]
    protected function search(Builder $query, string $q): Builder
    {
        $q = trim($q);
        if ($q === '') {
            return $query;
        }

        return $query->where(function (Builder $sub) use ($q) {
            $sub->where('name', 'like', "%{$q}%")
                ->orWhere('slug', 'like', "%{$q}%")
                ->orWhere('description', 'like', "%{$q}%");
        });
    }

    #[Scope]
    protected function trashedFilter(Builder $query, ?string $trashed): Builder
    {
        if (! $trashed) {
            return $query;
        }

        return match ($trashed) {
            'with' => $query->withTrashed(),
            'only' => $query->onlyTrashed(),
            default => $query,
        };
    }

    #[Scope]
    protected function sortBySafe(Builder $query, string $sort, string $dir): Builder
    {
        $allowed = ['id', 'name', 'slug', 'created_at'];
        if (! in_array($sort, $allowed, true)) {
            $sort = 'created_at';
        }
        $dir = strtolower($dir) === 'asc' ? 'asc' : 'desc';

        return $query->orderBy($sort, $dir);
    }
}
