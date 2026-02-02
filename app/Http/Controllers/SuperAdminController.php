<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\TempatLayanan;
use Illuminate\Http\Request;

class SuperAdminController extends Controller
{
    // 1. Dashboard Superadmin: Melihat semua kelas Pending
    public function index()
    {
        // Ambil semua kelas yang statusnya 'pending'
        $pendingTempats = TempatLayanan::where('status', 'pending')
            ->with('user') // Load data pembuatnya
            ->latest()
            ->get();

        // Ambil semua user untuk list penghapusan
        $users = User::latest()->get();

        return view('superadmin.dashboard', compact('pendingTempats', 'users'));
    }

    // 2. Approve Kelas: Mengubah status pending -> aktif
    public function approveTempat($id)
    {
        $tempat = TempatLayanan::findOrFail($id);
        $tempat->update(['status' => 'aktif']);

        return back()->with('success', "Kelas {$tempat->nama} berhasil diaktifkan.");
    }

    // 3. Reject/Hapus Kelas: Superadmin bisa hapus kelas siapapun
    public function destroyTempat($id)
    {
        $tempat = TempatLayanan::findOrFail($id);
        $tempat->delete(); // Akan cascade delete karena relasi onDelete('cascade')

        return back()->with('success', 'Kelas berhasil dihapus permanen.');
    }

    // 4. Hapus User
    public function destroyUser($id)
    {
        // Cegah superadmin menghapus dirinya sendiri
        if (auth()->id() == $id) {
            return back()->with('error', 'Anda tidak bisa menghapus akun sendiri.');
        }

        $user = User::findOrFail($id);
        $user->delete(); // User terhapus, relasi akan terhapus otomatis jika cascade diatur

        return back()->with('success', 'User berhasil dihapus.');
    }
}