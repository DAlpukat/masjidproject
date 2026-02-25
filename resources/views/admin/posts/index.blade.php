@extends('layouts.app')

@section('content')
<div class="bg-monochrome-gif"></div>
<div class="bg-overlay"></div>

<div class="min-h-screen py-12 px-4 sm:px-6 lg:px-8 relative z-10">
    <div class="max-w-6xl mx-auto">
        
        <!-- Header -->
        <div class="mb-10">
            <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-6">
                <div class="space-y-2">
                    <span class="px-4 py-1.5 text-xs font-bold tracking-widest text-blue-400 uppercase bg-blue-500/10 border border-blue-500/20 rounded-full inline-block mb-3">
                        Manajemen Konten
                    </span>
                    <h1 class="text-4xl font-black text-white tracking-tight">
                        Daftar Post
                    </h1>
                    {{-- Perbaikan: Gunakan judul, bukan nama --}}
                    <p class="text-gray-400 font-medium">Halaman: <span class="text-pink-400 font-bold">{{ $page->judul }}</span></p>
                </div>
                
                <div class="flex gap-3">
                    {{-- PERBAIKAN: Arahkan ke tempat->id, bukan page->id --}}
                    <a href="{{ route('admin.pages.index', $tempat->id) }}" class="px-6 py-3 rounded-xl font-bold border border-white/10 text-gray-300 hover:bg-white/5 transition text-sm">
                        &larr; Kembali
                    </a>
                    <a href="{{ route('admin.posts.create', $page->id) }}" class="group relative inline-flex items-center px-8 py-3.5 overflow-hidden text-white bg-gradient-to-br from-blue-500 to-purple-600 rounded-2xl shadow-xl shadow-blue-500/20 transition-all hover:shadow-blue-500/40 hover:scale-105 active:scale-95">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                        <span class="font-bold tracking-wide">Tulis Baru</span>
                    </a>
                </div>
            </div>
        </div>

        @if(session('success'))
            <div class="glass-card border-l-4 border-emerald-500 bg-emerald-500/10 mb-8 p-4 flex items-center rounded-xl">
                <div class="bg-emerald-500/20 p-2 rounded-lg mr-3 border border-emerald-500/30">
                    <svg class="w-5 h-5 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>
                </div>
                <span class="font-bold text-white text-sm">{!! session('success') !!}</span>
            </div>
        @endif

        <!-- List Post -->
        <div class="glass-card rounded-[2rem] overflow-hidden border border-white/10">
            
            @if($posts->count() > 0)
                <div class="overflow-x-auto">
                    <table class="min-w-full">
                        <thead class="bg-white/5 text-xs font-bold text-gray-400 uppercase border-b border-white/10">
                            <tr>
                                <th class="px-6 py-4 text-left w-12">No</th>
                                <th class="px-6 py-4 text-left">Gambar</th>
                                <th class="px-6 py-4 text-left">Judul</th>
                                <th class="px-6 py-4 text-left">Tanggal</th>
                                <th class="px-6 py-4 text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-white/5">
                            @foreach ($posts as $index => $post)
                                <tr class="hover:bg-white/5 transition group">
                                    <td class="px-6 py-4 text-sm text-gray-500 font-mono">
                                        {{ $posts->firstItem() + $index }}
                                    </td>
                                    <td class="px-6 py-4">
                                        @if($post->image)
                                            <img src="{{ asset('storage/' . $post->image) }}" alt="{{ $post->title }}" class="w-16 h-12 object-cover rounded-lg border border-white/10 group-hover:border-pink-500/50 transition">
                                        @else
                                            <div class="w-16 h-12 bg-gray-700 rounded-lg flex items-center justify-center text-gray-500 text-xs">
                                                No Img
                                            </div>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="font-bold text-white group-hover:text-pink-300 transition">{{ $post->title }}</div>
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-400">
                                        {{ $post->created_at->format('d M Y') }}
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="flex items-center justify-center gap-2">
                                            
                                            <!-- TOMBOL LIHAT POST -->
                                            <a href="{{ route('post.show', $post->id) }}" class="btn-action-mono group/btn" title="Lihat Post">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                            </a>

                                            <form action="{{ route('admin.posts.destroy', $post->id) }}" method="POST" onsubmit="return confirm('Hapus post ini?');" class="inline-block">
                                                @csrf @method('DELETE')
                                                <button type="submit" class="btn-action-danger group/btn" title="Hapus">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                
                <div class="p-4 border-t border-white/10">
                    {{ $posts->links() }}
                </div>
            @else
                <div class="p-16 text-center">
                    <div class="w-24 h-24 bg-blue-500/10 rounded-full flex items-center justify-center mx-auto mb-6 border border-blue-500/20">
                        <svg class="w-10 h-10 text-blue-400/50" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"></path></svg>
                    </div>
                    <h3 class="text-xl font-bold text-white mb-2">Belum Ada Post</h3>
                    <p class="text-gray-500 mb-8 max-w-md mx-auto">Mulai menulis artikel pertama Anda untuk halaman ini.</p>
                    <a href="{{ route('admin.posts.create', $page->id) }}" class="inline-flex items-center px-6 py-3 bg-blue-600 text-white rounded-xl font-bold hover:bg-blue-700 transition">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                        Tulis Post Baru
                    </a>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection