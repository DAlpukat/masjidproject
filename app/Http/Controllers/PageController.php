<?php

namespace App\Http\Controllers;

use App\Models\Page;
use App\Models\TempatLayanan;
use Illuminate\Http\Request;

class PageController extends Controller
{
    public function index($id)
    {
        $tempat = TempatLayanan::findOrFail($id);
        if ($tempat->user_id !== auth()->id()) { abort(403); }
        $pages = $tempat->pages()->orderBy('urutan')->get();
        return view('admin.pages.index', compact('tempat', 'pages'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'tempat_layanan_id' => 'required|exists:tempat_layanans,id',
            'judul' => 'required|string|max:255',
            'tipe' => 'required|in:kas,barang_pinjam,info',
            'content' => 'nullable|string', // <--- Terima konten
        ]);

        Page::create([
            'tempat_layanan_id' => $request->tempat_layanan_id,
            'judul' => $request->judul,
            'tipe' => $request->tipe,
            'content' => $request->content, // <--- Simpan konten
            'urutan' => Page::where('tempat_layanan_id', $request->tempat_layanan_id)->count() + 1,
        ]);

        return back()->with('success', 'Halaman berhasil ditambahkan');
    }

    public function edit(Page $page)
    {
        if ($page->tempatLayanan->user_id !== auth()->id()) { abort(403); }
        $tempat = $page->tempatLayanan;
        return view('admin.pages.edit', compact('page', 'tempat'));
    }

    public function update(Request $request, Page $page)
    {
        if ($page->tempatLayanan->user_id !== auth()->id()) { abort(403); }

        $request->validate([
            'judul' => 'required|string|max:255',
            'content' => 'nullable|string',
        ]);

        $page->update($request->all());

        return redirect()->route('pages.index', $page->tempat_layanan_id)
            ->with('success', 'Halaman berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $page = Page::findOrFail($id);
        if ($page->tempatLayanan->user_id !== auth()->id()) { abort(403); }
        $page->delete();
        return back()->with('success', 'Halaman dihapus');
    }
}