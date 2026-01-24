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

        <!-- List Tempat Public -->
        <h2 class="text-2xl font-bold mb-4">Tempat Terbuka</h2>
        @if(session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-2 rounded mb-4">
                {{ session('success') }}
            </div>
        @endif

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            @forelse($publicPlaces as $place)
                <div class="bg-white p-4 rounded shadow hover:shadow-lg transition border">
                    <h3 class="font-bold text-lg">{{ $place->nama }}</h3>
                    <p class="text-gray-600 text-sm mb-4">{{ $place->deskripsi ?: 'Tidak ada deskripsi' }}</p>
                    <div class="flex justify-between items-center">
                        <span class="text-xs text-gray-500">{{ $place->users->count() }} Anggota</span>
                        <button class="text-blue-600 text-sm font-semibold hover:underline">Lihat Detail</button>
                    </div>
                </div>
            @empty
                <p class="text-gray-500 col-span-2 text-center">Belum ada tempat layanan publik.</p>
            @endforelse
        </div>
    </div>
</div>
@endsection