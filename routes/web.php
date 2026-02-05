<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\TempatLayananController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\LaporanKasController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\KasController;

// --- 1. Landing Page ---
Route::get('/', function () {
    return view('welcome');
})->name('welcome');

// --- 2. Halaman User Biasa ---
Route::middleware('auth')->group(function () {
    Route::post('/room/{tempat}/leave', [HomeController::class, 'leave'])->name('room.leave');
    Route::get('/home', [HomeController::class, 'index'])->name('home');
    Route::post('/join', [HomeController::class, 'join'])->name('join.store');
    Route::post('/join-public/{place}', [HomeController::class, 'joinPublic'])->name('join.public');
    Route::get('/my-rooms', [HomeController::class, 'myRooms'])->name('user.rooms');
    Route::get('/room/{slug}', [HomeController::class, 'viewRoom'])->name('room.view');
});

// --- 3. Sistem Kas & Post ---
Route::middleware('auth')->group(function () {
    Route::get('/room/{tempatId}/kas', [KasController::class, 'dashboard'])->name('kas.dashboard');
    Route::post('/room/{tempatId}/kas', [KasController::class, 'store'])->name('kas.store');
    Route::post('/room/{tempatId}/kategori', [KasController::class, 'storeKategori'])->name('kas.kategori.store');
    Route::delete('/kas/destroy/{id}', [KasController::class, 'destroy'])->name('kas.destroy');
    Route::get('/post/{id}', [PostController::class, 'show'])->name('post.show');
});

// --- 4. Halaman Admin (Hanya Admin) ---
Route::middleware(['auth', 'admin'])->group(function () {
    
    // Dashboard Admin
    Route::get('/admin/dashboard', [AdminController::class, 'index'])->name('admin.dashboard');

    // Tempat Layanan
    Route::get('/admin/tempat-layanan/create', [TempatLayananController::class, 'create'])->name('admin.temp.create');
    Route::post('/admin/tempat-layanan', [TempatLayananController::class, 'store'])->name('admin.temp.store');
    Route::get('/admin/tempat-layanan/{id}/edit', [TempatLayananController::class, 'edit'])->name('admin.temp.edit');
    Route::put('/admin/tempat-layanan/{id}', [TempatLayananController::class, 'update'])->name('admin.temp.update');
    Route::delete('/admin/tempat-layanan/{tempat}', [TempatLayananController::class, 'destroy'])->name('admin.temp.destroy');
    
    // Anggota Komunitas
    Route::get('/admin/tempat-layanan/{id}/members', [TempatLayananController::class, 'members'])->name('admin.group.members');
    Route::delete('/admin/tempat/{id}/members/{userId}', [TempatLayananController::class, 'kick'])->name('admin.members.kick');

    // Pages Management (PERBAIKAN: Menambahkan 'admin.' pada name)
    Route::get('/pages/{id}', [PageController::class, 'index'])->name('admin.pages.index');
    Route::post('/pages', [PageController::class, 'store'])->name('admin.pages.store');
    Route::get('/pages/{page}/edit', [PageController::class, 'edit'])->name('admin.pages.edit');
    Route::put('/pages/{page}', [PageController::class, 'update'])->name('admin.pages.update');
    Route::delete('/pages/{id}', [PageController::class, 'destroy'])->name('admin.pages.destroy');
    
    // Posts Management (PERBAIKAN: Menambahkan 'admin.' pada name)
    Route::get('/pages/{pageId}/posts', [PostController::class, 'index'])->name('admin.posts.index');
    Route::get('/pages/{pageId}/posts/create', [PostController::class, 'create'])->name('admin.posts.create');
    Route::post('/posts/store', [PostController::class, 'store'])->name('admin.posts.store');
    Route::delete('/posts/{id}', [PostController::class, 'destroy'])->name('admin.posts.destroy');
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