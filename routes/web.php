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
use App\Http\Controllers\SuperAdminController;

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

// --- 1. Public Page (Guest) ---
Route::get('/', function () {
    return view('welcome');
})->name('welcome');

// --- 2. Authenticated Users (General) ---
Route::middleware('auth')->group(function () {
    // Home & Room Navigation
    Route::get('/home', [HomeController::class, 'index'])->name('home');
    Route::post('/join', [HomeController::class, 'join'])->name('join.store');
    Route::post('/join-public/{place}', [HomeController::class, 'joinPublic'])->name('join.public');
    
    Route::get('/my-rooms', [HomeController::class, 'myRooms'])->name('user.rooms');
    Route::get('/room/{slug}', [HomeController::class, 'viewRoom'])->name('room.view');
    Route::post('/room/{tempat}/leave', [HomeController::class, 'leave'])->name('room.leave');

    // Profile Management
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// --- 3. System Kas (Authenticated) ---
Route::middleware('auth')->group(function () {
    Route::get('/room/{tempatId}/kas', [KasController::class, 'dashboard'])->name('kas.dashboard');
    Route::post('/room/{tempatId}/kas', [KasController::class, 'store'])->name('kas.store');
    Route::post('/room/{tempatId}/kategori', [KasController::class, 'storeKategori'])->name('kas.kategori.store');
    Route::delete('/kas/destroy/{id}', [KasController::class, 'destroy'])->name('kas.destroy');
    
    // Public Post View (Bisa diakses user biasa dan admin)
    Route::get('/post/{id}', [PostController::class, 'show'])->name('post.show');
});

// --- 4. Admin Routes (Khusus Admin Pemilik Kelas) ---
// Menggunakan Prefix 'admin' dan Name 'admin.'
Route::middleware(['auth', 'admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {
        
        // Dashboard Admin
        Route::get('/dashboard', [AdminController::class, 'index'])->name('dashboard');

        // CRUD Tempat Layanan
        Route::get('/tempat-layanan/create', [TempatLayananController::class, 'create'])->name('temp.create');
        Route::post('/tempat-layanan', [TempatLayananController::class, 'store'])->name('temp.store');
        Route::get('/tempat-layanan/{id}/edit', [TempatLayananController::class, 'edit'])->name('temp.edit');
        Route::put('/tempat-layanan/{id}', [TempatLayananController::class, 'update'])->name('temp.update');
        Route::delete('/tempat-layanan/{tempat}', [TempatLayananController::class, 'destroy'])->name('temp.destroy');
        
        // Manajemen Anggota
        Route::get('/tempat-layanan/{id}/members', [TempatLayananController::class, 'members'])->name('group.members');

        // Pages & Posts Management
        // Nama route otomatis ada prefix 'admin.' (contoh: admin.pages.index)
        Route::get('/pages/{id}', [PageController::class, 'index'])->name('pages.index');
        Route::post('/pages', [PageController::class, 'store'])->name('pages.store');
        Route::get('/pages/{page}/edit', [PageController::class, 'edit'])->name('pages.edit');
        Route::put('/pages/{page}', [PageController::class, 'update'])->name('pages.update');
        Route::delete('/pages/{id}', [PageController::class, 'destroy'])->name('pages.destroy');
        
        // Posts Routes
        Route::get('/pages/{pageId}/posts', [PostController::class, 'index'])->name('posts.index');
        Route::get('/pages/{pageId}/posts/create', [PostController::class, 'create'])->name('posts.create');
        Route::post('/posts/store', [PostController::class, 'store'])->name('posts.store');
        Route::delete('/posts/{id}', [PostController::class, 'destroy'])->name('posts.destroy');
});

// --- 5. SuperAdmin Routes (Khusus SuperAdmin) ---
// Menggunakan Prefix 'superadmin' dan Name 'superadmin.'
Route::middleware(['auth', 'is.superadmin'])
    ->prefix('superadmin')
    ->name('superadmin.')
    ->group(function () {
        Route::get('/dashboard', [SuperAdminController::class, 'index'])->name('dashboard');
        
        // Approve Kelas (Pending -> Aktif)
        Route::post('/tempat/{id}/approve', [SuperAdminController::class, 'approveTempat'])->name('approve');
        
        // Hapus Kelas Global
        Route::delete('/tempat/{id}', [SuperAdminController::class, 'destroyTempat'])->name('destroy.tempat');
        
        // Hapus User Global
        Route::delete('/user/{id}', [SuperAdminController::class, 'destroyUser'])->name('destroy.user');
});

// --- 6. Default Redirect ---
Route::get('/dashboard', function () {
    return redirect()->route('home');
})->middleware(['auth'])->name('dashboard');

require __DIR__.'/auth.php';