<?php

use App\Http\Controllers\RoleController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::get('/', fn () => view('frontend.home'))->name('frontend.home');

Route::get('/backend', fn () => view('backend.dashboard'))->middleware(['auth', 'verified'])->name('backend.dashboard');

Route::middleware(['auth', 'verified'])
    ->prefix('backend')
    ->name('backend.')
    ->group(function () {
        Route::resource('users', UserController::class)->withTrashed();

        Route::patch('users/{id}/restore', [UserController::class, 'restore'])
            ->name('users.restore');

        Route::delete('users/{id}/force-delete', [UserController::class, 'forceDelete'])
            ->name('users.forceDelete');

        Route::resource('roles', RoleController::class)->withTrashed();

        Route::patch('roles/{id}/restore', [RoleController::class, 'restore'])
            ->name('roles.restore');

        Route::delete('roles/{id}/force-delete', [RoleController::class, 'forceDelete'])
            ->name('roles.forceDelete');
    });

require __DIR__.'/settings.php';
