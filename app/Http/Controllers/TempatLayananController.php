<?php

namespace App\Http\Controllers;

use App\Models\TempatLayanan;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class TempatLayananController extends Controller
{
    public function create()
    {
        return view('admin.tempat-layanan.create');
    }

    public function store(Request $request)
    {
        // 1. Validasi Input
        $request->validate([
            'nama' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'is_public' => 'required|boolean', 
        ]);

        $slug = Str::slug($request->nama);
        $kodeReferral = null;
        if (!$request->is_public) {
            $kodeReferral = strtoupper(Str::random(6));
        }

        // Simpan Data Room
        $tempat = TempatLayanan::create([
            'nama' => $request->nama,
            'slug' => $slug,
            'deskripsi' => $request->deskripsi,
            'is_public' => $request->is_public,
            'kode_referral' => $kodeReferral,
            'status' => 'pending',
            'user_id' => auth()->id(),
        ]);

        // --- TAMBAHKAN INI (Auto Join Admin ke Room) ---
        $tempat->users()->attach(auth()->id());
        // --------------------------------------------------

        $message = 'Tempat layanan berhasil diajukan.';
        if (!$request->is_public) {
            $message .= " <strong>Kode Referral Kamu: {$kodeReferral}</strong> (Simpan kode ini!).";
        }

        return redirect()->route('admin.dashboard')->with('success', $message);
    }
}