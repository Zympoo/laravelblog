<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\Category;
use App\Models\User;

class CategoryPolicy
{
    public function viewAny(User $user): bool
    {
        return in_array($user->role?->name, ['admin', 'editor'], true);
    }

    public function view(User $user, Category $category): bool
    {
        return in_array($user->role?->name, ['admin', 'editor'], true);
    }

    public function create(User $user): bool
    {
        return in_array($user->role?->name, ['admin', 'editor'], true);
    }

    public function update(User $user, Category $category): bool
    {
        return in_array($user->role?->name, ['admin', 'editor'], true);
    }

    public function delete(User $user, Category $category): bool
    {
        return in_array($user->role?->name, ['admin', 'editor'], true);
    }

    public function restore(User $user, Category $category): bool
    {
        return in_array($user->role?->name, ['admin', 'editor'], true);
    }

    public function forceDelete(User $user, Category $category): bool
    {
        return in_array($user->role?->name, ['admin', 'editor'], true);
    }
}
