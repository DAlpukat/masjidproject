<?php

namespace App\Http\Controllers;

use App\Models\TempatLayanan;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index(Request $request)
    {
        // Ambil query dasar: Hanya Room yang TIDAK PRIVATE (Terbuka)
        $query = TempatLayanan::where('is_public', true);

        // --- 1. SEARCH (Cari nama atau deskripsi) ---
        if ($request->filled('search')) {
            $keyword = $request->search;
            $query->where(function($q) use ($keyword) {
                $q->where('nama', 'like', '%' . $keyword . '%')
                ->orWhere('deskripsi', 'like', '%' . $keyword . '%');
            });
        }

        // --- 2. FILTER (Status) ---
        $statusFilter = $request->input('status', 'aktif');
        $query->where('status', $statusFilter);

        // --- 3. SORT (Pengurutan) ---
        $sort = $request->input('sort', 'latest');
        if ($sort == 'latest') {
            $query->orderBy('created_at', 'desc');
        } elseif ($sort == 'oldest') {
            $query->orderBy('created_at', 'asc');
        } elseif ($sort == 'name_asc') {
            $query->orderBy('nama', 'asc');
        } elseif ($sort == 'name_desc') {
            $query->orderBy('nama', 'desc');
        }

        // --- 4. PAGINATION (5 per halaman) ---
        $publicPlaces = $query->paginate(5);

        return view('home', compact('publicPlaces'));
    }

    public function join(Request $request)
    {
        // Cari tempat berdasarkan kode referral
        $tempat = TempatLayanan::where('kode_referral', $request->kode)->first();

        if (!$tempat) {
            return back()->with('error', 'Kode referral tidak ditemukan!');
        }

        // Cek apakah user sudah join sebelumnya
        if (auth()->user()->joinedPlaces->contains($tempat->id)) {
            return back()->with('info', 'Kamu sudah bergabung ke tempat ini.');
        }

        // Hubungkan User dengan Tempat
        auth()->user()->joinedPlaces()->attach($tempat->id);

        return back()->with('success', 'Berhasil bergabung ke ' . $tempat->nama);
    }

    public function myRooms()
    {
        $rooms = auth()->user()->joinedPlaces()
            ->withCount(['users as anggota_count' => function($query) {
                $query->whereColumn('users.id', '!=', 'tempat_layanans.user_id') // Kecualikan Admin
                    ->where('users.email', '!=', 'superadmin@gmail.com'); // GANTI dengan email Super Admin Anda
            }]) 
            ->latest()
            ->get();

        return view('user.rooms', compact('rooms'));
    }

    public function viewRoom($slug)
    {
        // PERBAIKAN: Tambahkan withCount agar di halaman detail juga muncul jumlahnya jika butuh
        $tempat = TempatLayanan::where('slug', $slug)
            ->withCount('users')
            ->firstOrFail();

        if (!$tempat->users->contains(auth()->id())) {
            abort(403, 'Kamu belum bergabung ke room ini.');
        }
        $pages = $tempat->pages()->orderBy('urutan')->get();

        return view('user.room-view', compact('tempat', 'pages'));
    }
    
    public function leave(TempatLayanan $tempat)
    {
        // 1. Cek Keamanan: Pemilik/Admin tidak boleh leave.
        if ($tempat->user_id === auth()->id()) {
            return back()->with('error', 'Kamu adalah pengurus utama. Kamu tidak bisa keluar, silakan hapus room dari Dashboard Admin jika tidak diperlukan.');
        }

        // 2. Cek Apakah user sudah join (opsional, tapi untuk keamanan)
        if (!$tempat->users->contains(auth()->id())) {
            return back()->with('error', 'Kamu bukan anggota room ini.');
        }

        // 3. Proses Leave: Hapus hubungan di tabel pivot
        auth()->user()->joinedPlaces()->detach($tempat->id);

        return back()->with('success', 'Berhasil keluar dari ' . $tempat->nama);
    }

    public function joinPublic(TempatLayanan $place)
    {
        // Cek jangan sampai pemilik room gabung ke room sendiri
        if ($place->user_id === auth()->id()) {
            return back()->with('info', 'Kamu adalah admin tempat ini.');
        }

        // Cek apakah room memang publik (keamanan)
        if (!$place->is_public) {
            return back()->with('error', 'Room ini privat. Gunakan kode referral.');
        }

        // Cek apakah user sudah join
        if ($place->users->contains(auth()->id())) {
            return back()->with('info', 'Kamu sudah bergabung ke room ini sebelumnya.');
        }

        // Proses Gabung
        $place->users()->attach(auth()->id());

        return back()->with('success', 'Berhasil bergabung ke ' . $place->nama);
    }

}