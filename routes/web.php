<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\TempatLayananController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\LaporanKasController;

// --- 1. Landing Page (Publik / Tanpa Login) ---
Route::get('/', function () {
    return view('welcome');
})->name('welcome');

// --- 2. Halaman User Biasa (Join, List Room, View Room) ---
Route::get('/home', [HomeController::class, 'index'])->name('home')->middleware('auth');
Route::post('/join', [HomeController::class, 'join'])->name('join.store')->middleware('auth');

// Route Tambahan untuk List Room dan View Room
Route::get('/my-rooms', [HomeController::class, 'myRooms'])->name('user.rooms')->middleware('auth');
Route::get('/room/{slug}', [HomeController::class, 'viewRoom'])->name('room.view')->middleware('auth');

// --- 3. Laporan Kas Routes (CRUD) ---
Route::get('/room/{slug}/laporan/{pageId}', [LaporanKasController::class, 'show'])->name('laporan.show')->middleware('auth');

// Route Baru: Tampilkan Form Tambah Laporan
Route::get('/room/{slug}/laporan/{pageId}/create', [LaporanKasController::class, 'create'])->name('laporan.create')->middleware('auth');

// Route Baru: Simpan Laporan
Route::post('/laporan/store', [LaporanKasController::class, 'store'])->name('laporan.store')->middleware('auth');

// Route Hapus Laporan
Route::delete('/laporan/{id}', [LaporanKasController::class, 'destroy'])->name('laporan.destroy')->middleware('auth');

// --- 4. Halaman Admin ---
Route::get('/admin/dashboard', [AdminController::class, 'index'])
    ->name('admin.dashboard')
    ->middleware(['auth', 'admin']);

Route::get('/admin/tempat-layanan/create', [TempatLayananController::class, 'create'])
    ->name('admin.temp.create')
    ->middleware(['auth', 'admin']);

Route::post('/admin/tempat-layanan', [TempatLayananController::class, 'store'])
    ->name('admin.temp.store')
    ->middleware(['auth', 'admin']);

Route::middleware(['auth', 'admin'])->group(function () {
    Route::get('/pages/{id}', [PageController::class, 'index'])->name('pages.index');
    Route::post('/pages', [PageController::class, 'store'])->name('pages.store');
    Route::delete('/pages/{id}', [PageController::class, 'destroy'])->name('pages.destroy');
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