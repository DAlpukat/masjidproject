<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\Page;
use Illuminate\Http\Request;

class PostController extends Controller
{
    // List semua berita di dalam halaman info tertentu (Admin)
    public function index($pageId)
    {
        $page = Page::find($pageId);
        if ($page->tempatLayanan->user_id !== auth()->id()) { abort(403); }

        $posts = $page->posts;
        return view('admin.posts.index', compact('page', 'posts'));
    }

    // Form tambah berita
    public function create($pageId)
    {
        $page = Page::find($pageId);
        return view('admin.posts.create', compact('page'));
    }

    // Simpan berita + Upload Gambar
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required',
            'content' => 'required',
            'image' => 'required|image|max:2048', // Wajib gambar
            'page_id' => 'required',
        ]);

        // Handle Upload Gambar
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('uploads/posts', 'public');
        }

        Post::create([
            'title' => $request->title,
            'content' => $request->content,
            'image' => $imagePath,
            'page_id' => $request->page_id,
        ]);

        return redirect()->route('posts.index', $request->page_id)
            ->with('success', 'Berita berhasil diterbitkan');
    }

    // Tampilkan detail berita untuk User
    public function show($id)
    {
        // Cari post sekaligus ambil relasi Page dan TempatLayanan (Eager Loading)
        $post = Post::with('page.tempatLayanan')->findOrFail($id);

        // Cek keamanan tambahan: Pastikan Post punya Page yang valid
        if (!$post->page) {
            abort(404, 'Halaman Info tidak ditemukan.');
        }

        return view('user.post-show', compact('post'));
    }

    // Hapus berita
    public function destroy($id)
    {
        $post = Post::findOrFail($id);
        if ($post->page->tempatLayanan->user_id !== auth()->id()) { abort(403); }
        
        // Hapus file fisik jika perlu (opsional, tapi bagus)
        // unlink(public_path('storage/' . $post->image));

        $post->delete();
        return back()->with('success', 'Berita dihapus');
    }
}