@extends('layouts.app')

@section('content')
<div class="container mx-auto py-8 px-4">
    <div class="max-w-6xl mx-auto">
        
        <!-- Header Room -->
        <div class="mb-6">
            <a href="{{ route('user.rooms') }}" class="text-blue-600 hover:underline">&larr; Kembali ke Ruangan Saya</a>
            <h1 class="text-3xl font-bold mt-2">{{ $tempat->nama }}</h1>
            <p class="text-gray-600">{{ $tempat->deskripsi }}</p>
        </div>

        <!-- Navigasi Pages (Tab) -->
        @if($pages->count() > 0)
            <div class="flex border-b border-gray-200 mb-6 overflow-x-auto whitespace-nowrap">
                <div class="space-x-8">
                    @foreach($pages as $page)
                        <!-- LOGIC: ADMIN MASUK KE DASHBOARD, USER SCROLL KE KONTEN -->
                        @if(auth()->id() == $tempat->user_id)
                            <!-- Link Admin: Langsung ke Dashboard -->
                            <a href="{{ route('laporan.show', [$tempat->slug, $page->id]) }}" class="block py-4 px-1 text-blue-600 border-b-2 border-blue-600 font-medium hover:bg-blue-50 transition flex items-center gap-1">
                                {{ $page->judul }} 
                                <span class="text-xs bg-blue-100 text-blue-700 px-1 rounded">Kelola</span>
                            </a>
                        @else
                            <!-- Link User: Scroll ke bawah -->
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
                    <h2 class="text-xl font-bold mb-4">Halaman: {{ $page->judul }} (Tipe: Kas)</h2>
                    
                    <div class="bg-gray-50 p-6 rounded border border-gray-200 text-center">
                        <p class="text-gray-700 mb-4">Untuk melihat grafik dan tabel keuangan secara lengkap, silakan masuk ke Dashboard.</p>
                        
                        <!-- Tombol Masuk Dashboard (User bisa juga buat) -->
                        <a href="{{ route('laporan.show', [$tempat->slug, $page->id]) }}" class="inline-block bg-blue-600 text-white px-6 py-2 rounded hover:bg-blue-700 font-bold">
                            Buka Dashboard Laporan Penuh
                        </a>
                    </div>

                <!-- JIKA TIPE BARANG -->
                @elseif($page->tipe == 'barang_pinjam')
                    <h2 class="text-xl font-bold mb-4">Halaman: {{ $page->judul }} (Tipe: Barang)</h2>
                    <div class="bg-gray-50 p-6 rounded border border-gray-200 text-center">
                        <p class="text-gray-700">Daftar Barang Pinjam akan muncul di sini.</p>
                    </div>

                <!-- JIKA TIPE INFO / LAINNYA -->
                @else
                    <h2 class="text-xl font-bold mb-4">Halaman: {{ $page->judul }} (Tipe: Info)</h2>
                    <div class="bg-gray-50 p-6 rounded border border-gray-200 text-center">
                        <p class="text-gray-700">Konten Info/Pengumuman akan muncul di sini.</p>
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