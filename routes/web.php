<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\SongController;
use App\Http\Controllers\GenreController;
use App\Http\Controllers\WallpaperController;

Route::get('/', function () {
    return redirect()->route('dashboard');
});

// Auth routes are already set up by Laravel Breeze

Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
});

// Admin routes
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::resource('songs', SongController::class);
    Route::resource('genres', GenreController::class);
    Route::resource('wallpapers', WallpaperController::class);
});