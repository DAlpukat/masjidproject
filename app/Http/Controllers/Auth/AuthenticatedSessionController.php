<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();

        $request->session()->regenerate();

        // --- LOGIKA REDIRECT BERDASARKAN ROLE ---

        // 1. PRIORITY PERTAMA: Superadmin
        if (auth()->user()->is_superadmin) {
            return redirect()->route('superadmin.dashboard');
        }

        // 2. PRIORITY KEDUA: Admin Biasa (Pembuat Kelas)
        if (auth()->user()->is_admin) {
            return redirect()->route('admin.dashboard');
        }

        // 3. PRIORITY KETIGA: User Biasa
        // Cek apakah user sudah gabung kelas apa saja
        $joinedCount = auth()->user()->joinedPlaces()->count();

        if ($joinedCount > 0) {
            // Kalau sudah ada room -> Ke Halaman "Ruangan Saya"
            return redirect()->route('user.rooms');
        } else {
            // Kalau belum ada room -> Ke Halaman Home (Cari Kelas)
            return redirect()->route('home');
        }
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}