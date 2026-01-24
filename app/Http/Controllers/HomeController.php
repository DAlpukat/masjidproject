<?php

namespace App\Http\Controllers;

use App\Models\TempatLayanan;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        // Ambil semua tempat yang statusnya AKTIF dan PUBLIC
        $publicPlaces = TempatLayanan::where('status', 'aktif')
                                     ->where('is_public', true)
                                     ->latest()
                                     ->get();

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
        // Ambil semua room yang user-nya join (berdasarkan tabel pivot)
        $rooms = auth()->user()->joinedPlaces()->latest()->get();
        return view('user.rooms', compact('rooms'));
    }

    public function viewRoom($slug)
    {
        // Cari room berdasarkan slug
        $tempat = TempatLayanan::where('slug', $slug)->firstOrFail();

        // Cek apakah user sudah join room ini
        if (!$tempat->users->contains(auth()->id())) {
            abort(403, 'Kamu belum bergabung ke room ini.');
        }

        // Ambil halaman (pages) yang dibuat admin untuk room ini
        $pages = $tempat->pages()->orderBy('urutan')->get();

        return view('user.room-view', compact('tempat', 'pages'));
    }
    
}