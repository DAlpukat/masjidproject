@extends('layouts.app')

@section('content')
<div class="container mx-auto py-8 px-4">
    <div class="max-w-6xl mx-auto">
        
        <!-- Header Room -->
        <div class="mb-6">
            <a href="{{ route('user.rooms') }}" class="text-blue-600 hover:underline">&larr; Kembali ke Ruangan Saya</a>
            
            <div class="flex justify-between items-center mt-2">
                <h1 class="text-3xl font-bold">{{ $tempat->nama }}</h1>
                
                <!-- HANYA UNTUK ADMIN: Tombol Cepat ke Dashboard Kelola -->
                @if(auth()->id() == $tempat->user_id)
                    <a href="{{ route('pages.index', $tempat->id) }}" class="bg-purple-600 text-white px-4 py-2 rounded-md hover:bg-purple-700 text-sm font-bold shadow">
                        &uarr; Kelola Halaman (Admin)
                    </a>
                @endif
            </div>

            <!-- Deskripsi dengan class desc-clamp -->
            <p class="text-gray-600 desc-clamp mt-2">
                {{ $tempat->deskripsi ?: 'Tidak ada deskripsi' }}
            </p>
        </div>

        <!-- Navigasi Pages (Tab: Universal untuk Semua Orang) -->
        @if($pages->count() > 0)
            <div class="flex border-b border-gray-200 mb-6 overflow-x-auto whitespace-nowrap">
                <div class="space-x-8">
                    @foreach($pages as $page)
                        <!-- LOGIKA: TANPA PEMBEDAAN ADMIN/USER -->
                        @if($page->tipe == 'kas')
                            <!-- Tab Kas: Arah ke Dashboard Laporan -->
                            <a href="{{ route('laporan.show', [$tempat->slug, $page->id]) }}" class="block py-4 px-1 text-gray-600 border-b-2 border-transparent hover:text-blue-600 hover:border-gray-300 font-medium">
                                {{ $page->judul }}
                            </a>
                        @else
                            <!-- Tab Info/Barang: Scroll ke bawah -->
                            <a href="#{{ $page->tipe }}-{{ $page->id }}" class="block py-4 px-1 text-gray-600 border-b-2 border-transparent hover:text-blue-600 hover:border-gray-300 font-medium">
                                {{ $page->judul }}
                            </a>
                        @endif
                    @endforeach
                </div>
            </div>
        @endif

        <!-- Konten Halaman (Loop) -->
        @forelse($pages as $page)
            <div id="{{ $page->tipe }}-{{ $page->id }}" class="mb-8 p-6 bg-white rounded shadow border border-gray-200">
                
                <!-- JIKA TIPE KAS -->
                @if($page->tipe == 'kas')
                    <h2 class="text-xl font-bold mb-4">Halaman: {{ $page->judul }}</h2>
                    <div class="bg-gray-50 p-6 rounded border border-gray-200 text-center">
                        <p class="text-gray-700 mb-4">Klik tombol di bawah untuk melihat detail laporan keuangan.</p>
                        <a href="{{ route('laporan.show', [$tempat->slug, $page->id]) }}" class="inline-block bg-blue-600 text-white px-6 py-2 rounded hover:bg-blue-700 font-bold">
                            Buka Dashboard Laporan
                        </a>
                    </div>

                <!-- JIKA TIPE INFO -->
                @elseif($page->tipe == 'info')
                    <h2 class="text-xl font-bold mb-4">Halaman: {{ $page->judul }}</h2>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        @forelse($page->posts as $post)
                            <!-- KOTAK BERITA -->
                            <a href="{{ route('post.show', $post->id) }}" class="block group">
                                <div class="bg-white rounded-lg shadow-md overflow-hidden border border-gray-200 group-hover:shadow-xl transition duration-300">
                                    <!-- Gambar Utama -->
                                    <img src="{{ asset('storage/' . $post->image) }}" alt="{{ $post->title }}" class="w-full h-48 object-cover">
                                    
                                    <div class="p-4">
                                        <h3 class="font-bold text-lg text-gray-800 group-hover:text-blue-600 mb-2 line-clamp-2">
                                            {{ $post->title }}
                                        </h3>
                                        <p class="text-gray-500 text-xs">
                                            {{ $post->created_at->format('d M Y') }}
                                        </p>
                                    </div>
                                </div>
                            </a>
                        @empty
                            <div class="col-span-3 text-center text-gray-500 py-10">
                                Belum ada berita.
                            </div>
                        @endforelse
                    </div>

                <!-- JIKA TIPE BARANG -->
                @else
                    <h2 class="text-xl font-bold mb-4">Halaman: {{ $page->judul }} (Tipe: Barang)</h2>
                    <div class="bg-gray-50 p-6 rounded border border-gray-200 text-center">
                        <p class="text-gray-700">Daftar Barang Pinjam akan muncul di sini.</p>
                    </div>
                @endif

            </div>
        @empty
            <div class="text-center text-gray-500 py-10">
                Admin belum membuat halaman di room ini.
            </div>
        @endforelse

    </div>
</div>
@endsection