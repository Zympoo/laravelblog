<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Scope;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Role extends Model
{
    use SoftDeletes;

    //
    protected $fillable = [
        'name',
        'description',
    ];

    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }

    #[Scope]
    protected function search(Builder $query, string $q): Builder
    {
        $q = trim($q);

        if ($q === '') {
            return $query;
        }

        return $query->where(function (Builder $sub) use ($q) {
            $sub->where('name', 'LIKE', "%{$q}%")
                ->orWhere('description', 'LIKE', "%{$q}%");
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
        $allowed = ['id', 'name', 'created_at'];

        if (! in_array($sort, $allowed, true)) {
            $sort = 'created_at';
        }

        $dir = strtolower($dir) === 'asc' ? 'asc' : 'desc';

        return $query->orderBy($sort, $dir);
    }
}
