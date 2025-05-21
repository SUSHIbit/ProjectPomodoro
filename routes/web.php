<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\SongController;
use App\Http\Controllers\GenreController;
use App\Http\Controllers\WallpaperController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Welcome page
Route::get('/', function () {
    return view('welcome');
});

// Dashboard route - accessible to everyone
Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
Route::post('/dashboard/filter', [DashboardController::class, 'filterByGenre'])->name('dashboard.filter');
Route::get('/songs/{song}/details', [DashboardController::class, 'getSongDetails'])->name('songs.details');

// Profile routes
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Admin routes
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    // Song routes
    Route::resource('songs', SongController::class);
    Route::get('songs/{song}/preview', [SongController::class, 'preview'])->name('songs.preview');
    Route::get('songs/{song}/download', [SongController::class, 'download'])->name('songs.download');
    
    // Genre routes
    Route::resource('genres', GenreController::class);
    
    // Wallpaper routes
    Route::resource('wallpapers', WallpaperController::class);
    Route::get('wallpapers/{wallpaper}/preview', [WallpaperController::class, 'preview'])->name('wallpapers.preview');
});

// Include auth routes
require __DIR__.'/auth.php';