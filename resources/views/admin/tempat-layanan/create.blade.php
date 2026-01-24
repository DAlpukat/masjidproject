@extends('layouts.app')

@section('content')
<div class="container mx-auto py-8 px-4">
    <div class="max-w-2xl mx-auto bg-white p-6 rounded-lg shadow-md">
        <h1 class="text-2xl font-bold mb-6">Registrasi Tempat Layanan Baru</h1>

        <form method="POST" action="{{ route('admin.temp.store') }}">
            @csrf

            <!-- Nama Tempat -->
            <div class="mb-4">
                <label for="nama" class="block text-sm font-medium text-gray-700">Nama Tempat (Masjid/Gereja/Sekolah)</label>
                <input type="text" name="nama" id="nama" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm p-2 border" required>
            </div>

            <!-- Deskripsi -->
            <div class="mb-4">
                <label for="deskripsi" class="block text-sm font-medium text-gray-700">Deskripsi</label>
                <textarea name="deskripsi" id="deskripsi" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm p-2 border" rows="3"></textarea>
            </div>

            <!-- Status Akses (Public/Private) -->
            <div class="mb-6">
                <label class="block text-sm font-medium text-gray-700 mb-2">Status Akses</label>
                
                <div class="flex items-center mb-2">
                    <input type="radio" name="is_public" value="1" id="public" class="text-indigo-600 focus:ring-indigo-500" checked>
                    <label for="public" class="ml-2">Terbuka (Siapa saja bisa akses)</label>
                </div>
                
                <div class="flex items-center">
                    <input type="radio" name="is_public" value="0" id="private" class="text-indigo-600 focus:ring-indigo-500">
                    <label for="private" class="ml-2">Tertutup (Kode Referral otomatis dibuatkan)</label>
                </div>
            </div>

            <!-- Tombol Submit -->
            <div class="mt-6">
                <button type="submit" class="w-full bg-blue-600 text-white font-bold py-2 px-4 rounded-md hover:bg-blue-700 transition">
                    Buat Room
                </button>
            </div>
        </form>
    </div>
</div>
@endsection