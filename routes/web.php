<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\TempatLayananController;
use App\Http\Controllers\HomeController;

// --- 1. Landing Page (Publik / Tanpa Login) ---
Route::get('/', function () {
    return view('welcome');
})->name('welcome');

// --- 2. Halaman Home User (Masukin Kode Referral - Harus Login) ---
Route::get('/home', [HomeController::class, 'index'])->name('home')->middleware('auth');
Route::post('/join', [HomeController::class, 'join'])->name('join.store')->middleware('auth');

// --- 3. Halaman Admin ---
Route::get('/admin/dashboard', [AdminController::class, 'index'])
    ->name('admin.dashboard')
    ->middleware(['auth', 'admin']);

Route::get('/admin/tempat-layanan/create', [TempatLayananController::class, 'create'])
    ->name('admin.temp.create')
    ->middleware(['auth', 'admin']);

Route::post('/admin/tempat-layanan', [TempatLayananController::class, 'store'])
    ->name('admin.temp.store')
    ->middleware(['auth', 'admin']);

// --- Route Standard Lainnya ---
Route::get('/dashboard', function () {
    return redirect()->route('home'); // Redirect dashboard default ke home user
})->middleware(['auth']);

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';