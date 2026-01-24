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

        // 1. Kalau Admin, ke Dashboard Admin
        if (auth()->user()->is_admin) {
            return redirect()->route('admin.dashboard');
        }

        // 2. Kalau User Biasa, cek apakah sudah punya room?
        $joinedCount = auth()->user()->joinedPlaces()->count();

        if ($joinedCount > 0) {
            // Kalau sudah ada room -> Ke Ruangan Saya
            return redirect()->route('user.rooms');
        } else {
            // Kalau belum ada room -> Ke Gabung Room
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
