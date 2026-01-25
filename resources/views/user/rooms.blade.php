@extends('layouts.app')

@section('content')


<div class="container mx-auto py-8 px-4">
    <div class="max-w-4xl mx-auto">
        <h1 class="text-2xl font-bold text-gray-800 mb-6">Ruang Saya</h1>
        <p class="text-gray-600 mb-6">Daftar tempat layanan yang kamu ikuti.</p>

        @if(session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4">
                {{ session('error') }}
            </div>
        @endif

        <!-- Grid Daftar Room -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            @forelse($rooms as $room)
                <div class="bg-white shadow rounded-lg p-6 border border-gray-200 flex flex-col justify-between h-full">
                    <div>
                        <div class="flex justify-between items-start mb-2">
                            <h2 class="text-xl font-bold text-gray-900">{{ $room->nama }}</h2>
                            <!-- Badge Status -->
                            @if($room->status == 'aktif')
                                <span class="bg-green-100 text-green-800 text-xs font-semibold px-2.5 py-0.5 rounded">Aktif</span>
                            @else
                                <span class="bg-yellow-100 text-yellow-800 text-xs font-semibold px-2.5 py-0.5 rounded">Pending</span>
                            @endif
                        </div>
                        
                        <!-- PARAGRAF DESKRIPSI DENGAN KELAS desc-clamp -->
                        <p class="text-gray-600 text-sm mb-4 desc-clamp">
                            {{ $room->deskripsi ?: 'Tidak ada deskripsi' }}
                        </p>

                        <div class="text-xs text-gray-500 mb-4">
                            <p>Slug: {{ $room->slug }}</p>
                            <p>Total Anggota: {{ $room->anggota_count }} Orang</p>
                        </div>
                    </div>

                    <!-- Tombol Aksi -->
                    <div class="flex justify-between items-center mt-4 pt-4 border-t border-gray-100">
                        <a href="{{ route('room.view', $room->slug) }}" class="text-blue-600 hover:text-blue-800 font-medium text-sm">
                            Masuk Room &rarr;
                        </a>

                        <!-- Tombol Leave -->
                        <form action="{{ route('room.leave', $room->id) }}" method="POST" onsubmit="return confirm('Yakin ingin keluar dari {{ $room->nama }}?');">
                            @csrf
                            <button type="submit" class="text-red-500 hover:text-red-700 text-xs font-bold border border-red-200 hover:bg-red-50 px-3 py-1 rounded transition">
                                Keluar
                            </button>
                        </form>
                    </div>
                </div>
            @empty
                <div class="col-span-1 md:col-span-2 bg-white p-8 text-center rounded-lg shadow border border-gray-200">
                    <p class="text-gray-500 mb-4">Kamu belum bergabung ke ruangan manapun.</p>
                    <a href="{{ route('home') }}" class="text-blue-600 font-semibold hover:underline">Cari Ruangan Sekarang</a>
                </div>
            @endforelse
        </div>
    </div>
</div>
@endsection