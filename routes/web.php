<?php

use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::get('/', fn () => view('frontend.home'))->name('frontend.home');

Route::get('/backend', fn () => view('backend.dashboard'))->middleware(['auth', 'verified'])->name('backend.dashboard');

Route::middleware(['auth', 'verified'])
    ->prefix('backend')
    ->name('backend.')
    ->group(function () {
        Route::resource('users', UserController::class);
    });

require __DIR__.'/settings.php';
