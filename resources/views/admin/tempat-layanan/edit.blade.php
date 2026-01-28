@extends('layouts.app')

@section('content')
<div class="min-h-screen py-10 px-4" style="background: radial-gradient(at 0% 0%, rgba(255, 192, 203, 0.4) 0px, transparent 50%), radial-gradient(at 100% 100%, rgba(221, 160, 221, 0.3) 0px, transparent 50%), #ffffff;">
    <div class="max-w-4xl mx-auto">
        
        <!-- Header -->
        <div class="mb-6 flex items-center justify-between">
            <h1 class="text-2xl font-bold text-gray-800">Edit Pengaturan: {{ $tempat->nama }}</h1>
            <a href="{{ route('admin.dashboard') }}" class="text-gray-600 hover:text-gray-900 font-bold">
                &larr; Kembali ke Dashboard
            </a>
        </div>

        <!-- Form Container -->
        <div class="glass-card p-8 rounded-[2rem] border border-white shadow-xl">
            <form method="POST" action="{{ route('admin.temp.update', $tempat->id) }}">
                @csrf
                @method('PUT')

                <!-- 1. Info Dasar -->
                <div class="mb-8 pb-8 border-b border-gray-200">
                    <h3 class="text-lg font-black text-gray-700 mb-4 uppercase tracking-wider">Info Dasar</h3>
                    
                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-bold mb-2">Nama Tempat</label>
                        <input type="text" name="nama" value="{{ $tempat->nama }}" class="glass-input w-full px-4 py-3 rounded-xl" required>
                    </div>

                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-bold mb-2">Deskripsi</label>
                        <textarea name="deskripsi" class="glass-input w-full px-4 py-3 rounded-xl" rows="3">{{ $tempat->deskripsi }}</textarea>
                    </div>

                    <div class="flex items-center">
                        <input type="checkbox" name="is_public" id="is_public" {{ $tempat->is_public ? 'checked' : '' }} class="w-5 h-5 text-pink-600 rounded focus:ring-pink-500 border-gray-300">
                        <label for="is_public" class="ml-3 block text-gray-700 text-sm font-bold">
                            Publik (Siapa saja bisa join)
                        </label>
                    </div>
                </div>

                <!-- 2. PENGATURAN KAS (LOGIKA BARU) -->
                <div class="mb-6">
                    <h3 class="text-lg font-black text-gray-700 mb-4 uppercase tracking-wider flex items-center">
                        <svg class="w-6 h-6 mr-2 text-pink-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        Mode Keuangan
                    </h3>
                    <p class="text-sm text-gray-500 mb-6">Pilih bagaimana sistem kas berjalan di tempat ini.</p>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        
                        <!-- Pilihan 1: Kas Umum (Default Masjid) -->
                        <label class="cursor-pointer relative group">
                            <input type="radio" name="use_individual_ledger" value="0" {{ !$tempat->use_individual_ledger ? 'checked' : '' }} class="peer sr-only" onchange="toggleLedgerMode()">
                            <div class="p-6 border-2 border-gray-200 rounded-2xl peer-checked:border-green-500 peer-checked:bg-green-50 transition-all hover:shadow-md">
                                <div class="flex items-start justify-between mb-2">
                                    <h4 class="font-bold text-lg text-gray-800 peer-checked:text-green-800">Kas Umum</h4>
                                    <span class="bg-gray-100 text-gray-600 text-[10px] font-bold px-2 py-1 rounded">DEFAULT</span>
                                </div>
                                <p class="text-sm text-gray-500 leading-relaxed">
                                    Hanya mencatat pemasukan & pengeluaran kas total. Tidak ada catatan saldo per anggota/tunggakan.
                                </p>
                                <p class="text-xs text-gray-400 mt-2 italic">Cocok untuk: Masjid, Yayasan, Organisasi Sukarela.</p>
                            </div>
                        </label>

                        <!-- Pilihan 2: Kas Perorangan (Default Kelas) -->
                        <label class="cursor-pointer relative group">
                            <input type="radio" name="use_individual_ledger" value="1" {{ $tempat->use_individual_ledger ? 'checked' : '' }} class="peer sr-only" onchange="toggleLedgerMode()">
                            <div class="p-6 border-2 border-gray-200 rounded-2xl peer-checked:border-pink-500 peer-checked:bg-pink-50 transition-all hover:shadow-md">
                                <div class="flex items-start justify-between mb-2">
                                    <h4 class="font-bold text-lg text-gray-800 peer-checked:text-pink-800">Kas Perorangan</h4>
                                    <span class="bg-pink-100 text-pink-600 text-[10px] font-bold px-2 py-1 rounded">ADVANCED</span>
                                </div>
                                <p class="text-sm text-gray-500 leading-relaxed">
                                    Mencatat siapa yang setor ke kategori apa, berapa saldo/tunggakannya, dan distribusi biaya bersama.
                                </p>
                                <p class="text-xs text-gray-400 mt-2 italic">Cocok untuk: Kelas SMA, Angkatan, Arisan.</p>
                            </div>
                        </label>

                    </div>

                    <!-- DETAIL KAS PERORANGAN (Hidden jika Kas Umum) -->
                    <div id="ledger-settings" class="{{ $tempat->use_individual_ledger ? 'mt-6 block' : 'mt-6 hidden' }}">
                        <div class="bg-white p-6 rounded-xl border border-pink-100 shadow-sm">
                            <h5 class="font-bold text-gray-700 mb-4 border-b pb-2">Fitur Tambahan (Kas Perorangan)</h5>
                            
                            <div class="space-y-3">
                                <label class="flex items-center cursor-pointer group">
                                    <input type="checkbox" name="use_mandatory_cash" value="1" {{ $tempat->use_mandatory_cash ? 'checked' : '' }} class="w-5 h-5 text-pink-600 rounded focus:ring-pink-500 border-gray-300">
                                    <div class="ml-3">
                                        <span class="font-semibold text-gray-800 group-hover:text-pink-600">Aktifkan Kas Wajib</span>
                                        <p class="text-xs text-gray-500">Kategori kas dengan target dan aturan minus (tunggakan).</p>
                                    </div>
                                </label>

                                <label class="flex items-center cursor-pointer group">
                                    <input type="checkbox" name="shared_expense_enabled" value="1" {{ $tempat->shared_expense_enabled ? 'checked' : '' }} class="w-5 h-5 text-pink-600 rounded focus:ring-pink-500 border-gray-300">
                                    <div class="ml-3">
                                        <span class="font-semibold text-gray-800 group-hover:text-pink-600">Aktifkan Shared Expense</span>
                                        <p class="text-xs text-gray-500">Pengeluaran bisa otomatis dibagi rata ke semua anggota (kas wajib).</p>
                                    </div>
                                </label>
                            </div>
                        </div>
                    </div>

                </div>

                <!-- Tombol Simpan -->
                <div class="flex justify-end pt-4 border-t border-gray-200">
                    <button type="submit" class="bg-gradient-to-r from-pink-500 to-purple-600 text-white px-8 py-3 rounded-xl font-bold shadow-lg hover:shadow-pink-200/50 hover:scale-105 transition-all">
                        Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    function toggleLedgerMode() {
        // Ambil value radio button yang diklik
        const ledgerMode = document.querySelector('input[name="use_individual_ledger"]:checked').value;
        const settingsDiv = document.getElementById('ledger-settings');

        if (ledgerMode === "1") {
            settingsDiv.classList.remove('hidden');
            settingsDiv.classList.add('block');
        } else {
            settingsDiv.classList.remove('block');
            settingsDiv.classList.add('hidden');
        }
    }
    
    // Jalankan saat load untuk set state awal
    window.addEventListener('DOMContentLoaded', toggleLedgerMode);
</script>
@endsection