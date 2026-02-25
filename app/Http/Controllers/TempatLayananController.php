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
        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'deskripsi' => 'nullable|string|max:255',
            'is_public' => 'required|boolean', // Pastikan kirim 1 atau 0
        ]);

        // 2. Generate Slug dari Nama
        $slug = Str::slug($request->nama);

        // 3. Logika Kode Referral & Status
        $kodeReferral = null;
        $status = 'pending'; // Default harus PENDING

        // Jika Private (is_public = 0), generate kode unik
        if (!$request->is_public) {
            do {
                $kodeReferral = strtoupper(Str::random(6)); // Contoh: X7Y9Z2
            } while (TempatLayanan::where('kode_referral', $kodeReferral)->exists());
        }

        // 4. Simpan ke Database
        $tempat = TempatLayanan::create([
            'nama' => $request->nama,
            'slug' => $slug,
            'deskripsi' => $request->deskripsi,
            'is_public' => $request->is_public,
            'kode_referral' => $kodeReferral,
            'status' => $status, // 'pending'
            'user_id' => auth()->id(),
        ]);

        // 5. Auto-join Admin sebagai anggota pertama
        $tempat->users()->attach(auth()->id());

        return redirect()
            ->route('admin.dashboard')
            ->with('success', 'Kelas berhasil diajukan. Menunggu persetujuan Superadmin.');
    }

    public function edit($id)
    {
        $tempat = TempatLayanan::findOrFail($id);

        // Cek Keamanan: Hanya pemilik yang boleh edit
        if ($tempat->user_id !== auth()->id()) {
            abort(403, 'Anda tidak memiliki akses.');
        }

        return view('admin.tempat-layanan.edit', compact('tempat'));
    }

    public function update(Request $request, $id)
    {
        $tempat = TempatLayanan::findOrFail($id);

        if ($tempat->user_id !== auth()->id()) {
            abort(403, 'Anda tidak memiliki akses.');
        }

        // Validasi & Casting Boolean
        $data = $request->validate([
            'nama' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'is_public' => 'boolean',
            
            // Validasi config kas
            'use_individual_ledger' => 'boolean',
            'use_mandatory_cash' => 'boolean',
            'shared_expense_enabled' => 'boolean',
            'free_expense_enabled' => 'boolean',
        ]);

        // Pastikan checkbox terbaca dengan benar (1 atau 0)
        $data['is_public'] = $request->boolean('is_public');
        $data['use_individual_ledger'] = $request->boolean('use_individual_ledger');
        $data['use_mandatory_cash'] = $request->boolean('use_mandatory_cash');
        $data['shared_expense_enabled'] = $request->boolean('shared_expense_enabled');
        $data['free_expense_enabled'] = $request->boolean('free_expense_enabled');

        $tempat->update($data);

        return redirect()
            ->route('admin.dashboard')
            ->with('success', 'Pengaturan kelas berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $tempat = TempatLayanan::findOrFail($id);

        // Otorisasi
        if (auth()->id() != $tempat->user_id && !auth()->user()->is_superadmin) {
            return response()->json([
                'success' => false, 
                'message' => 'Akses Ditolak.'
            ], 403);
        }

        try {
            // Hapus relasi anggota
            $tempat->users()->detach();
            
            // Hapus ruangan
            $tempat->delete();

            return response()->json([
                'success' => true,
                'message' => 'Ruangan berhasil dihapus.'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menghapus: ' . $e->getMessage()
            ], 500);
        }
    }
    
    public function members($id)
    {
        $tempat = TempatLayanan::findOrFail($id);
        
        $members = $tempat->users()
            ->where('users.id', '!=', $tempat->user_id) // Exclude admin room
            ->where(function($query) {
                $query->where('users.is_admin', false)
                    ->orWhereNull('users.is_admin');
            })
            ->where(function($query) {
                $query->where('users.is_superadmin', false)
                    ->orWhereNull('users.is_superadmin');
            })
            ->get();

        return view('admin.tempat-layanan.members', compact('tempat', 'members'));
    }
    public function kick($id, $userId)
    {
        // 1. Ambil data tempat
        $tempat = \App\Models\TempatLayanan::findOrFail($id);

        // 2. Keamanan: Pastikan yang nge-kick adalah pemilik komunitasnya
        if ($tempat->user_id !== auth()->id()) {
            return redirect()->back()->with('error', 'Anda tidak memiliki izin.');
        }

        // 3. Putuskan hubungan anggota tersebut dari komunitas (tabel pivot)
        $tempat->users()->detach($userId);

        // 4. Kembali ke halaman daftar anggota dengan pesan sukses
        return redirect()->back()->with('success', 'Anggota berhasil dikeluarkan.');
    }
}