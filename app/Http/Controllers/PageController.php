<?php

namespace App\Http\Controllers;

use App\Models\Page;
use App\Models\TempatLayanan;
use Illuminate\Http\Request;

class PageController extends Controller
{
    // Tampilkan list halaman untuk room tertentu (Admin View)
    public function index($id)
    {
        $tempat = TempatLayanan::findOrFail($id);
        
        // Pastikan yang akses hanya pemilik room
        if ($tempat->user_id !== auth()->id()) {
            abort(403);
        }

        $pages = $tempat->pages()->orderBy('urutan')->get();

        return view('admin.pages.index', compact('tempat', 'pages'));
    }

    // Simpan halaman baru
    public function store(Request $request)
    {
        $request->validate([
            'tempat_layanan_id' => 'required|exists:tempat_layanans,id',
            'judul' => 'required|string|max:255',
            'tipe' => 'required|in:kas,barang_pinjam,info',
        ]);

        Page::create([
            'tempat_layanan_id' => $request->tempat_layanan_id,
            'judul' => $request->judul,
            'tipe' => $request->tipe,
            'urutan' => Page::where('tempat_layanan_id', $request->tempat_layanan_id)->count() + 1,
        ]);

        return back()->with('success', 'Halaman berhasil ditambahkan');
    }

    // Hapus halaman
    public function destroy($id)
    {
        $page = Page::findOrFail($id);
        if ($page->tempatLayanan->user_id !== auth()->id()) {
            abort(403);
        }
        $page->delete();
        return back()->with('success', 'Halaman dihapus');
    }
}