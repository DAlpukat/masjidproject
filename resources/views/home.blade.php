@extends('layouts.app')

@section('content')
<div class="container mx-auto py-8 px-4">
    <div class="max-w-4xl mx-auto">
        <h1 class="text-3xl font-bold mb-6 text-center">Daftar Tempat Layanan</h1>

        <!-- Form Join Referral Code -->
        <div class="bg-blue-50 p-6 rounded-lg mb-8 border border-blue-200">
            <h2 class="text-xl font-semibold mb-2">Punya Kode Kelas/Kode Referral?</h2>
            <form method="POST" action="{{ route('join.store') }}" class="flex gap-2">
                @csrf
                <input type="text" name="kode" placeholder="Masukkan kode (ex: X9Z2KA)" class="flex-1 p-2 border rounded-md uppercase" required>
                <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-md font-bold hover:bg-blue-700">Gabung</button>
            </form>
        </div>

        <!-- NOTIFIKASI SUKSES/ERROR -->
        @if(session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-2 rounded mb-4">
                {{ session('success') }}
            </div>
        @endif

        <!-- BAGIAN BARU: SEARCH, FILTER, & SORT -->
        <div class="bg-white p-4 rounded-lg shadow mb-6 border border-gray-200">
            <form action="{{ route('home') }}" method="GET" class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <!-- Search Input -->
                <div>
                    <label class="text-xs font-bold text-gray-500 uppercase">Cari Nama</label>
                    <input type="text" name="search" value="{{ request('search') }}" class="w-full border p-2 rounded" placeholder="Cari tempat...">
                </div>

                <!-- Filter Status -->
                <div>
                    <label class="text-xs font-bold text-gray-500 uppercase">Status</label>
                    <select name="status" class="w-full border p-2 rounded bg-white">
                        <option value="aktif" {{ request('status') == 'aktif' || request('status') == null ? 'selected' : '' }}>Aktif</option>
                        <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="nonaktif" {{ request('status') == 'nonaktif' ? 'selected' : '' }}>Nonaktif</option>
                    </select>
                </div>

                <!-- Sort By -->
                <div>
                    <label class="text-xs font-bold text-gray-500 uppercase">Urutkan</label>
                    <select name="sort" class="w-full border p-2 rounded bg-white">
                        <option value="latest" {{ request('sort') == 'latest' || request('sort') == null ? 'selected' : '' }}>Terbaru</option>
                        <option value="oldest" {{ request('sort') == 'oldest' ? 'selected' : '' }}>Terlama</option>
                        <option value="name_asc" {{ request('sort') == 'name_asc' ? 'selected' : '' }}>Nama A-Z</option>
                        <option value="name_desc" {{ request('sort') == 'name_desc' ? 'selected' : '' }}>Nama Z-A</option>
                    </select>
                </div>

                <div class="md:col-span-3">
                    <button type="submit" class="w-full bg-gray-800 text-white p-2 rounded hover:bg-gray-900 font-bold">
                        Terapkan Filter
                    </button>
                </div>
            </form>
        </div>

        <!-- List Tempat (Grid) -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            @forelse($publicPlaces as $place)
                <div class="bg-white p-4 rounded shadow hover:shadow-lg transition border">
                    <h3 class="font-bold text-lg">{{ $place->nama }}</h3>
                    <p class="text-gray-600 text-sm mb-4 desc-clamp">
                        {{ $place->deskripsi ?: 'Tidak ada deskripsi' }}
                    </p>
                    
                    <div class="flex justify-between items-center text-xs text-gray-500 mb-4">
                        <span>{{ $place->users->count() }} Anggota</span>
                        <span>
                            @if($place->status == 'aktif') <span class="text-green-600 font-bold">Aktif</span>
                            @elseif($place->status == 'pending') <span class="text-yellow-600 font-bold">Pending</span>
                            @else <span class="text-red-600">Nonaktif</span>
                            @endif
                        </span>
                    </div>

                    <!-- LOGIKA BARU: TOMBOL GABUNG / MASUK -->
                    <div class="mt-2 pt-2 border-t border-gray-100">
                        
                        @if(auth()->check() && $place->users->contains(auth()->id()))
                            <!-- SUDAH GABUNG: TOMBOL MASUK ROOM -->
                            <a href="{{ route('room.view', $place->slug) }}" class="block w-full text-center bg-gray-200 text-gray-800 px-4 py-2 rounded font-bold hover:bg-gray-300 text-sm transition">
                                &rarr; Masuk Room
                            </a>

                        @elseif(auth()->check())
                            <!-- BELUM GABUNG: TOMBOL GABUNG -->
                            <form action="{{ route('join.public', $place->id) }}" method="POST">
                                @csrf
                                <button type="submit" class="w-full bg-green-600 text-white px-4 py-2 rounded font-bold hover:bg-green-700 text-sm transition">
                                    + Gabung Sekarang
                                </button>
                            </form>

                        @else
                            <!-- BELUM LOGIN: TOMBOL LOGIN DULU -->
                            <a href="{{ route('login') }}" class="block w-full text-center bg-blue-600 text-white px-4 py-2 rounded font-bold hover:bg-blue-700 text-sm">
                                Login untuk Gabung
                            </a>
                        @endif

                    </div>
                </div>
            @empty
                <p class="text-gray-500 col-span-2 text-center">Tidak ada tempat layanan yang ditemukan.</p>
            @endforelse
        </div>

        <!-- PAGINATION -->
        <div class="mt-8 flex justify-center">
            {{ $publicPlaces->appends(request()->query())->links() }}
        </div>

    </div>
</div>
@endsection