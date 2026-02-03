<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\TempatLayanan;
use Illuminate\Support\Facades\DB;

class SuperAdminController extends Controller
{
    public function index()
    {
        // 1. Ambil kelas yang statusnya 'pending' (menunggu approve)
        $pendingTempats = TempatLayanan::where('status', 'pending')
            ->with('user') // Load data pembuat kelas
            ->latest()
            ->get();

        // 2. [NEW] Ambil SEMUA kelas untuk list di bawah
        $allTempats = TempatLayanan::with('user')->latest()->get();

        // 3. Ambil semua user untuk fitur hapus user
        $users = User::latest()->get();

        // Jangan lupa kirim $allTempats ke view
        return view('superadmin.dashboard', compact('pendingTempats', 'allTempats', 'users'));
    }

    // Fungsi Menyetujui Kelas
    public function approveTempat($id)
    {
        $tempat = TempatLayanan::findOrFail($id);
        
        // Ubah status dari 'pending' menjadi 'aktif'
        $tempat->update(['status' => 'aktif']);

        return back()
            ->with('success', "Kelas <b>{$tempat->nama}</b> berhasil diaktifkan.");
    }

    // Fungsi Hapus Kelas (Global - Bisa hapus kelas siapapun)
    public function destroyTempat($id)
    {
        $tempat = TempatLayanan::findOrFail($id);

        // Hapus relasi untuk mencegah error database
        $tempat->users()->detach();
        $tempat->pages()->delete();
        
        if (method_exists($tempat, 'kas')) {
            $tempat->kas()->delete(); 
        }

        $tempat->delete();

        return redirect()->back()->with('success', 'Ruangan berhasil dihapus secara permanen beserta seluruh datanya.');
    }

    // Fungsi Hapus User (Global)
    public function destroyUser($id)
    {
        if (auth()->id() == $id) {
            return back()->with('error', 'Anda tidak bisa menghapus akun sendiri.');
        }

        $user = User::findOrFail($id);
        $user->delete();

        return back()
            ->with('success', 'User berhasil dihapus.');
    }
}