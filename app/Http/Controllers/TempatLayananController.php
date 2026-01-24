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

        // 2. Buat Slug otomatis
        $slug = Str::slug($request->nama) . '-' . strtolower(Str::random(5));

        // 3. Logika generate kode referral (Mirip gugel klasrum)
        $kodeReferral = null;
        if (!$request->is_public) {
            // Generate kode random 6 huruf, semua jadi HURUF BESAR
            $kodeReferral = strtoupper(Str::random(6));
        }

        // 4. bwat simpan Data
        $tempat = TempatLayanan::create([
            'nama' => $request->nama,
            'slug' => $slug,
            'deskripsi' => $request->deskripsi,
            'is_public' => $request->is_public,
            'kode_referral' => $kodeReferral,
            'status' => 'pending', 
            'user_id' => auth()->id(),
        ]);

        // 5. pesan sukses
        $message = 'Tempat layanan berhasil dibuat.';
        if (!$request->is_public) {
            $message .= " <strong>Kode Referral Kamu: {$kodeReferral}</strong> (Simpan kode ini!).";
        }

        return redirect()->route('admin.dashboard')->with('success', $message);
    }
}