@extends('layouts.app')

@section('content')
<div class="container mx-auto py-8 px-4">
    <div class="max-w-2xl mx-auto">
        <!-- Header -->
        <div class="mb-6 flex items-center justify-between">
            <h1 class="text-2xl font-bold text-gray-800">Buat Tempat Layanan Baru</h1>
            <a href="{{ route('admin.dashboard') }}" class="text-gray-600 hover:text-gray-900">
                &larr; Kembali
            </a>
        </div>

        <!-- Form Container -->
        <div class="bg-white shadow-md rounded-lg p-6 border border-gray-200">
            <form method="POST" action="{{ route('admin.temp.store') }}">
                @csrf

                <!-- 1. Input Nama -->
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2">Nama Tempat</label>
                    <input type="text" name="nama" class="w-full border border-gray-300 rounded-md p-2 focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Contoh: Masjid Al-Hidayah" required>
                </div>

                <!-- 2. Input Deskripsi -->
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2">Deskripsi</label>
                    <textarea name="deskripsi" class="w-full border border-gray-300 rounded-md p-2 focus:outline-none focus:ring-2 focus:ring-blue-500" rows="3" placeholder="Deskripsi singkat tentang tempat ini..."></textarea>
                </div>

                <!-- 3. Pilihan Akses (Publik/Privat) -->
                <div class="mb-6 bg-gray-50 p-4 rounded border border-gray-200">
                    <label class="block text-gray-800 text-sm font-bold mb-3">Siapa yang bisa gabung?</label>
                    <div class="space-y-3">
                        <!-- Opsi Publik -->
                        <label class="flex items-start cursor-pointer group">
                            <input type="radio" name="is_public" value="1" id="tipe_publik" checked class="mt-1">
                            <div class="ml-3">
                                <span class="font-semibold text-gray-800 group-hover:text-blue-600">Terbuka (Publik)</span>
                                <p class="text-xs text-gray-500">Semua orang bisa melihat dan bergabung tanpa kode akses.</p>
                            </div>
                        </label>

                        <!-- Opsi Private -->
                        <label class="flex items-start cursor-pointer group">
                            <input type="radio" name="is_public" value="0" id="tipe_private" class="mt-1">
                            <div class="ml-3">
                                <span class="font-semibold text-gray-800 group-hover:text-blue-600">Tertutup (Private)</span>
                                <p class="text-xs text-gray-500">Kode Referral akan dibuat otomatis oleh sistem. Kamu bisa melihatnya di Dashboard nanti.</p>
                            </div>
                        </label>
                    </div>
                </div>

                <!-- 4. Tombol Simpan -->
                <div class="flex justify-end">
                    <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded-md hover:bg-blue-700 font-bold transition">
                        Buat Tempat
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection