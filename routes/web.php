<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\TempatLayananController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\LaporanKasController;
use App\Http\Controllers\PostController;

// --- 1. Landing Page (Publik / Tanpa Login) ---
Route::get('/', function () {
    return view('welcome');
})->name('welcome');

// --- 2. Halaman User Biasa (Join, List Room, View Room) ---
Route::middleware('auth')->group(function () {
    Route::post('/room/{tempat}/leave', [HomeController::class, 'leave'])->name('room.leave');
    Route::get('/home', [HomeController::class, 'index'])->name('home');
    Route::post('/join', [HomeController::class, 'join'])->name('join.store');
    
    Route::post('/join-public/{place}', [HomeController::class, 'joinPublic'])->name('join.public');

    Route::get('/my-rooms', [HomeController::class, 'myRooms'])->name('user.rooms');
    Route::get('/room/{slug}', [HomeController::class, 'viewRoom'])->name('room.view');
});

// --- 3. Laporan Kas Routes (CRUD) ---
// User biasa dan Admin bisa akses
Route::middleware('auth')->group(function () {
    Route::get('/room/{slug}/laporan/{pageId}', [LaporanKasController::class, 'show'])->name('laporan.show');
    Route::get('/room/{slug}/laporan/{pageId}/create', [LaporanKasController::class, 'create'])->name('laporan.create');
    Route::post('/laporan/store', [LaporanKasController::class, 'store'])->name('laporan.store');
    Route::delete('/laporan/{id}', [LaporanKasController::class, 'destroy'])->name('laporan.destroy');

    // Route Detail Post (User dan Admin bisa akses)
    Route::get('/post/{id}', [PostController::class, 'show'])->name('post.show');
});

// --- 4. Halaman Admin (Hanya Admin) ---
Route::middleware(['auth', 'admin'])->group(function () {
    
    Route::get('/pages/{page}/edit', [PageController::class, 'edit'])->name('pages.edit');
    Route::put('/pages/{page}', [PageController::class, 'update'])->name('pages.update');

    // Dashboard
    Route::get('/admin/dashboard', [AdminController::class, 'index'])->name('admin.dashboard');

    // Tempat Layanan (CRUD)
    Route::get('/admin/tempat-layanan/create', [TempatLayananController::class, 'create'])->name('admin.temp.create');
    Route::post('/admin/tempat-layanan', [TempatLayananController::class, 'store'])->name('admin.temp.store');
    Route::delete('/admin/tempat-layanan/{tempat}', [TempatLayananController::class, 'destroy'])->name('admin.temp.destroy');

    // Pages Management
    Route::get('/pages/{id}', [PageController::class, 'index'])->name('pages.index');
    Route::post('/pages', [PageController::class, 'store'])->name('pages.store');
    Route::delete('/pages/{id}', [PageController::class, 'destroy'])->name('pages.destroy');
    
    // Posts Management (Berita di Halaman Info)
    Route::get('/pages/{pageId}/posts', [PostController::class, 'index'])->name('posts.index');
    Route::get('/pages/{pageId}/posts/create', [PostController::class, 'create'])->name('posts.create');
    Route::post('/posts/store', [PostController::class, 'store'])->name('posts.store');
    Route::delete('/posts/{id}', [PostController::class, 'destroy'])->name('posts.destroy');
});

// --- 5. Route Standard Lainnya ---
Route::get('/dashboard', function () {
    return redirect()->route('home');
})->middleware(['auth']);

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';