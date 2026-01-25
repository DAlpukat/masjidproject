@extends('layouts.app')

@section('content')
<div class="container mx-auto py-8 px-4">
    <div class="max-w-6xl mx-auto">
        <div class="mb-6 flex justify-between items-center">
            <div>
                <a href="{{ route('pages.index', $page->tempat_layanan_id) }}" class="text-blue-600 hover:underline">&larr; Kembali</a>
                <h1 class="text-2xl font-bold mt-2">Kelola Berita: {{ $page->judul }}</h1>
            </div>
            <a href="{{ route('posts.create', $page->id) }}" class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700">
                + Tulis Berita Baru
            </a>
        </div>

        <!-- Grid List Berita -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @forelse($posts as $post)
                <div class="bg-white p-4 rounded shadow border border-gray-200">
                    <!-- Preview Gambar -->
                    <img src="{{ asset('storage/' . $post->image) }}" class="w-full h-48 object-cover rounded mb-3">
                    
                    <h3 class="font-bold text-lg mb-2">{{ $post->title }}</h3>
                    
                    <div class="flex justify-between items-center mt-4 pt-3 border-t">
                        <span class="text-xs text-gray-500">{{ $post->created_at->format('d M Y') }}</span>
                        <form action="{{ route('posts.destroy', $post->id) }}" method="POST" onsubmit="return confirm('Hapus?')">
                            @csrf @method('DELETE')
                            <button class="text-red-500 text-xs font-bold hover:underline">Hapus</button>
                        </form>
                    </div>
                </div>
            @empty
                <div class="col-span-3 text-center text-gray-500 py-10">
                    Belum ada berita. Klik tombol di kanan atas untuk menulis.
                </div>
            @endforelse
        </div>
    </div>
</div>
@endsection