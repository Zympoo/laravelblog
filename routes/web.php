<?php

use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::get('/', fn () => view('frontend.home'))->name('frontend.home');

Route::get('/backend', fn () => view('backend.dashboard'))->middleware(['auth', 'verified'])->name('backend.dashboard');

Route::get('/backend/users', [UserController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('backend.users.index');

require __DIR__.'/settings.php';
