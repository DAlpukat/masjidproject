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
}