<?php

use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::get('/', fn () => view('frontend.home'))->name('frontend.home');

Route::get('/backend', function () {
    return view('backend.dashboard');
})->middleware(['auth', 'verified'])->name('home');

Route::group(['prefix' => 'backend', 'middleware' => 'auth'], function () {
    Route::resource('/users', UserController::class);
});

require __DIR__.'/settings.php';
