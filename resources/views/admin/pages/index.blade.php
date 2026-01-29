@extends('layouts.app')

@section('content')
<div class="container mx-auto py-8 px-4">
    <div class="max-w-6xl mx-auto">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold">Kelola Halaman: {{ $tempat->nama }}</h1>
            <a href="{{ route('admin.dashboard') }}" class="text-gray-500 hover:text-gray-700">&larr; Kembali</a>
        </div>

        <!-- Form Tambah Halaman -->
        <div class="bg-white p-4 rounded shadow mb-6">
            <form method="POST" action="{{ route('pages.store') }}">
                @csrf
                <input type="hidden" name="tempat_layanan_id" value="{{ $tempat->id }}">
                
                <div class="flex gap-2">
                    <input type="text" name="judul" placeholder="Nama Halaman (ex: Pengumuman)" class="flex-1 border p-2 rounded" required>
                    
                    <select name="tipe" class="border p-2 rounded bg-white">
                        <option value="info">Info (Pengumuman)</option>
                        <option value="kas">Kas (Keuangan)</option>
                        <option value="barang_pinjam">Barang Pinjam</option>
                    </select>

                    <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Tambah</button>
                </div>
            </form>
        </div>

        <!-- List Halaman -->
        <div class="bg-white shadow rounded overflow-hidden">
            <table class="w-full text-left">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="p-3">Nama & Tipe</th>
                        <th class="p-3 w-1/3">Pratinjau Isi</th>
                        <th class="p-3">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($pages as $page)
                        <tr class="border-b">
                            <td class="p-3">
                                <div class="font-bold">{{ $page->judul }}</div>
                                @if($page->tipe == 'kas') <span class="bg-green-100 text-green-800 px-2 rounded text-xs">Kas</span>
                                @elseif($page->tipe == 'barang_pinjam') <span class="bg-yellow-100 text-yellow-800 px-2 rounded text-xs">Barang</span>
                                @else <span class="bg-gray-100 text-gray-800 px-2 rounded text-xs">Info</span>
                                @endif
                            </td>
                            
                            <!-- Pratinjau Isi -->
                            <td class="p-3">
                                @if($page->tipe == 'info')
                                    <div class="text-xs text-gray-600 max-h-20 overflow-y-auto">
                                        {{ Str::limit(strip_tags($page->content), 150, '...') ?: strip_tags('<span class="italic">Kosong</span>') }}
                                    </div>
                                @elseif($page->tipe == 'kas')
                                    <span class="text-xs text-blue-600">Laporan Keuangan</span>
                                @else
                                    <span class="text-xs text-gray-400">Tidak ada konten</span>
                                @endif
                            </td>

                            <!-- AKSI -->
                            <td class="p-3">
                                <div class="flex flex-col gap-1">
                                    <!-- JIKA INFO: TOMBOL EDIT -->
                                    @if($page->tipe == 'info')
                                        <a href="{{ route('posts.index', $page->id) }}" class="text-blue-600 hover:underline text-sm mr-2 font-bold">
                                            Kelola Berita
                                        </a>
                                    @endif

                                    <!-- JIKA KAS: TOMBOL LIHAT LAPORAN -->
                                    @if($page->tipe == 'kas')
                                        <a href="{{ route('kas.dashboard', $page->tempat_layanan_id) }}" class="...">
                                            Buka Kas
                                        </a>
                                    @endif

                                    <!-- TOMBOL HAPUS (UMUM) -->
                                    <form method="POST" action="{{ route('pages.destroy', $page->id) }}" onsubmit="return confirm('Yakin hapus halaman ini?')" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button class="text-red-500 hover:underline text-xs">Hapus Halaman</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="p-4 text-center text-gray-500">Belum ada halaman.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection