<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\TempatLayanan;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class TempatLayananController extends Controller
{
    public function create()
    {
        // Siapa yang boleh akses halaman create? Sudah diatur di Route middleware 'admin'
        return view('admin.tempat-layanan.create');
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
                $kodeReferral = strtoupper(Str::random(6));
            } while (TempatLayanan::where('kode_referral', $kodeReferral)->exists());
        }

        // 4. Simpan Data
        // PERUBAHAN PENTING: Status default sekarang adalah 'pending'
        $tempat = TempatLayanan::create([
            'nama' => $request->nama,
            'slug' => $slug,
            'deskripsi' => $request->deskripsi,
            'is_public' => $request->is_public,
            'kode_referral' => $kodeReferral,
            'status' => 'pending', // <--- HARUS PENDING AGAR PERLU APPROVAL
            'user_id' => auth()->id(),
        ]);

        // 5. Auto Join Admin sebagai anggota
        $tempat->users()->attach(auth()->id());

        // 6. Redirect
        // Pesan disesuaikan memberitahu bahwa kelas butuh persetujuan
        $message = 'Tempat layanan berhasil diajukan. Menunggu persetujuan Superadmin.';
        
        return redirect()->route('admin.dashboard')->with('success', $message);
    }

    public function edit($id)
    {
        $tempat = TempatLayanan::findOrFail($id);

        // KEAMANAN: Pastikan yang edit adalah pemilik kelas
        if ($tempat->user_id !== auth()->id()) {
            abort(403, 'Anda tidak memiliki akses untuk mengedit tempat ini.');
        }

        return view('admin.tempat-layanan.edit', compact('tempat'));
    }

    public function update(Request $request, $id)
    {
        $tempat = TempatLayanan::findOrFail($id);

        // KEAMANAN: Pastikan yang update adalah pemilik kelas
        if ($tempat->user_id !== auth()->id()) {
            abort(403, 'Anda tidak memiliki akses untuk mengubah tempat ini.');
        }

        // Validasi Data
        $data = $request->validate([
            'nama' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'is_public' => 'boolean',
            
            'use_individual_ledger' => 'boolean',
            'use_mandatory_cash' => 'boolean',
            'shared_expense_enabled' => 'boolean',
            'free_expense_enabled' => 'boolean',
        ]);

        // Pastikan nilai boolean terbaca dengan benar
        $data['is_public'] = $request->boolean('is_public');
        $data['use_individual_ledger'] = $request->boolean('use_individual_ledger');
        $data['use_mandatory_cash'] = $request->boolean('use_mandatory_cash');
        $data['shared_expense_enabled'] = $request->boolean('shared_expense_enabled');
        $data['free_expense_enabled'] = $request->boolean('free_expense_enabled');

        $tempat->update($data);

        return redirect()->route('admin.dashboard')->with('success', 'Pengaturan Tempat Layanan berhasil diperbarui.');
    }

    public function destroy(TempatLayanan $tempat)
    {
        // KEAMANAN: Pastikan yang menghapus adalah pemilik room
        // Catatan: Superadmin memiliki route hapus sendiri di SuperAdminController
        if ($tempat->user_id !== auth()->id()) {
            abort(403, 'Anda tidak memiliki izin menghapus tempat ini.');
        }

        // Hapus Data (Relasi cascade akan berjalan otomatis dari migration)
        $tempat->delete();

        return redirect()->route('admin.dashboard')->with('success', 'Tempat layanan berhasil dihapus.');
    }
}