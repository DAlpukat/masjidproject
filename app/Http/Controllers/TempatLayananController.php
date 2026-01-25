<?php

namespace App\Http\Controllers;

use App\Models\User; 
use App\Models\TempatLayanan;
use Illuminate\Http\Request;
use Illuminate\Support\Str; // Jangan lupa import ini

class TempatLayananController extends Controller
{
    public function create()
    {
        // Ambil semua user (jika masih diperlukan untuk halaman lain, 
        // tapi untuk create tempat ini sebenarnya sudah cukup auth()->id() saja)
        $anggota = User::all();

        return view('admin.tempat-layanan.create', compact('anggota'));
    }

    public function store(Request $request)
    {
        // 1. Validasi
        $request->validate([
            'nama' => 'required|string|max:255',
            'deskripsi' => 'nullable|string|max:255',
            'is_public' => 'required', // Menerima 1 atau 0
        ]);

        // 2. Generate Slug
        $slug = Str::slug($request->nama);

        // 3. Generate Kode Referral (Otomatis jika private)
        $kodeReferral = null;
        if ($request->is_public == 0) { // Jika dipilih Private
            do {
                // Buat kode random 6 huruf kapital
                $kodeReferral = strtoupper(Str::random(6));
            } while (TempatLayanan::where('kode_referral', $kodeReferral)->exists());
            // Ulangi terus sampai kodenya unik/belum ada di database
        }

        // 4. Simpan Data
        $tempat = TempatLayanan::create([
            'nama' => $request->nama,
            'slug' => $slug,
            'deskripsi' => $request->deskripsi,
            'is_public' => $request->is_public,
            'kode_referral' => $kodeReferral,
            'status' => 'aktif',
            'user_id' => auth()->id(),
        ]);

        // 5. Auto Join Admin sebagai anggota
        $tempat->users()->attach(auth()->id());

        // 6. Redirect
        $message = 'Tempat layanan berhasil diajukan.';
        if (!$request->is_public) {
            // Tampilkan kode di alert dashboard
            $message .= " <strong>Kode Referral Kamu: {$kodeReferral}</strong> (Simpan kode ini!).";
        }

        return redirect()->route('admin.dashboard')->with('success', $message);
    }


    public function destroy(TempatLayanan $tempat)
    {
        // 1. Cek Keamanan: Pastikan yang menghapus adalah pemilik room
        if ($tempat->user_id !== auth()->id()) {
            abort(403, 'Kamu tidak punya izin menghapus tempat ini.');
        }

        // 2. Hapus Data
        // Karena di migration kita sudah pakai onDelete('cascade') pada tabel 
        // pivot dan laporan_kas, maka relasinya akan otomatis terhapus.
        $tempat->delete();

        // 3. Redirect
        return redirect()->route('admin.dashboard')->with('success', 'Tempat layanan berhasil dihapus.');
    }
}