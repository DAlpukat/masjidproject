<?php

namespace App\Http\Controllers;

use App\Models\TempatLayanan;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Menampilkan halaman Home dengan daftar room publik.
     */
    public function index(Request $request)
    {
        // Query dasar: Hanya Room yang Publik
        $query = TempatLayanan::where('is_public', true);

        // 1. Search
        if ($request->filled('search')) {
            $keyword = $request->search;
            $query->where(function($q) use ($keyword) {
                $q->where('nama', 'like', '%' . $keyword . '%')
                  ->orWhere('deskripsi', 'like', '%' . $keyword . '%');
            });
        }

        // 2. Filter Status
        $statusFilter = $request->input('status', 'aktif');
        $query->where('status', $statusFilter);

        // 3. Sort
        $sort = $request->input('sort', 'latest');
        switch ($sort) {
            case 'oldest':
                $query->orderBy('created_at', 'asc');
                break;
            case 'name_asc':
                $query->orderBy('nama', 'asc');
                break;
            case 'name_desc':
                $query->orderBy('nama', 'desc');
                break;
            default: // latest
                $query->orderBy('created_at', 'desc');
                break;
        }

        // 4. Pagination dengan Eager Loading (Optimasi)
        // with('users') -> untuk cek sudah join/belum di view
        // withCount('users') -> untuk hitung jumlah anggota
        $publicPlaces = $query->with('users')->withCount('users')->paginate(5);

        return view('home', compact('publicPlaces'));
    }

    /**
     * Join Room menggunakan Kode Referral (Privat).
     */
    public function join(Request $request)
    {
        $request->validate(['kode' => 'required|string']);

        $tempat = TempatLayanan::where('kode_referral', $request->kode)->first();

        if (!$tempat) {
            return back()->with('error', 'Kode referral tidak ditemukan!');
        }

        // Cek jika sudah join
        if (auth()->user()->joinedPlaces->contains($tempat->id)) {
            return redirect()->route('room.view', $tempat->slug)->with('info', 'Kamu sudah bergabung di tempat ini.');
        }

        // Proses Join
        auth()->user()->joinedPlaces()->attach($tempat->id);

        // Langsung arahkan ke room
        return redirect()->route('room.view', $tempat->slug)->with('success', 'Berhasil bergabung ke ' . $tempat->nama);
    }

    /**
     * Menampilkan daftar room yang sudah diikuti user.
     */
    public function myRooms()
    {
        $rooms = auth()->user()->joinedPlaces()
            ->withCount(['users as anggota_count' => function($query) {
                // Hitung user yang bukan admin pemilik dan bukan superadmin
                $query->whereColumn('users.id', '!=', 'tempat_layanans.user_id')
                      ->where(function($q) {
                          $q->where('users.is_superadmin', false)
                            ->orWhereNull('users.is_superadmin');
                      });
            }])
            ->latest()
            ->get();

        return view('user.rooms', compact('rooms'));
    }

    /**
     * Menampilkan detail Room.
     */
    public function viewRoom($slug)
    {
        $tempat = TempatLayanan::where('slug', $slug)
            ->withCount('users')
            ->firstOrFail();

        // Otorisasi: Harus anggota atau pemilik
        $isMember = $tempat->users->contains(auth()->id());
        $isOwner = $tempat->user_id === auth()->id();

        if (!$isMember && !$isOwner) {
            abort(403, 'Kamu belum bergabung ke room ini.');
        }

        // Ambil halaman, urutkan berdasarkan ID (atau urutan jika ada)
        $pages = $tempat->pages()->orderBy('id')->get();

        return view('user.room-view', compact('tempat', 'pages'));
    }
    
    /**
     * Keluar dari Room.
     */
    public function leave(TempatLayanan $tempat)
    {
        // Pemilik tidak bisa leave
        if ($tempat->user_id === auth()->id()) {
            return back()->with('error', 'Kamu adalah pengurus utama. Tidak bisa keluar. Hapus room dari Dashboard Admin jika tidak diperlukan.');
        }

        // Proses Leave
        auth()->user()->joinedPlaces()->detach($tempat->id);

        return redirect()->route('user.rooms')->with('success', 'Berhasil keluar dari ' . $tempat->nama);
    }

    /**
     * Join Room Publik via Tombol.
     */
        public function joinPublic(TempatLayanan $place)
    {
        // Cek jika sudah join
        if ($place->users->contains(auth()->id())) {
            return redirect()->route('room.view', $place->slug)->with('info', 'Kamu sudah bergabung ke room ini.');
        }

        // Proses Gabung
        $place->users()->attach(auth()->id());

        return redirect()->route('room.view', $place->slug)->with('success', 'Berhasil bergabung ke ' . $place->nama);
    }
}