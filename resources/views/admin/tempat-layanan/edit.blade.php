@extends('layouts.app')

@section('content')
<div class="bg-monochrome-gif"></div>
<div class="bg-overlay"></div>

<div class="min-h-screen py-12 px-4 sm:px-6 lg:px-8 relative z-10">
    <div class="max-w-4xl mx-auto">
        
        <!-- Header Section -->
        <div class="mb-10">
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-6">
                <div>
                    <span class="px-4 py-1.5 text-xs font-bold tracking-widest text-pink-400 uppercase bg-pink-500/10 border border-pink-500/20 rounded-full inline-block mb-3">
                        Pengaturan
                    </span>
                    <h1 class="text-3xl font-black text-white tracking-tight">
                        Edit <span class="text-transparent bg-clip-text bg-gradient-to-r from-pink-400 to-purple-400">{{ $tempat->nama }}</span>
                    </h1>
                    <p class="text-gray-400 font-medium mt-1">Kelola pengaturan tempat layanan Anda</p>
                </div>
                <a href="{{ route('admin.dashboard') }}" class="group inline-flex items-center text-sm font-bold text-pink-400 hover:text-pink-300 transition-colors">
                    <svg class="w-4 h-4 mr-2 group-hover:-translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                    Kembali ke Dashboard
                </a>
            </div>
        </div>

        <!-- Form Container -->
        <div class="glass-card p-8 rounded-[2rem] border border-white/10 shadow-xl">
            <form method="POST" action="{{ route('admin.temp.update', $tempat->id) }}">
                @csrf
                @method('PUT')

                <!-- Section 1: Info Dasar -->
                <div class="mb-8 pb-8 border-b border-white/10">
                    <h3 class="text-lg font-black text-white mb-6 uppercase tracking-wider flex items-center">
                        <span class="w-1.5 h-6 bg-gradient-to-b from-pink-500 to-purple-600 rounded-full mr-3"></span>
                        Info Dasar
                    </h3>
                    
                    <div class="space-y-6">
                        <!-- Nama Tempat -->
                        <div>
                            <label class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">Nama Tempat</label>
                            <input type="text" name="nama" value="{{ $tempat->nama }}" 
                                class="glass-input w-full px-5 py-4 rounded-xl text-white placeholder-gray-500 focus:ring-2 focus:ring-pink-500/50 transition-all" 
                                placeholder="Contoh: Masjid Al-Hidayah" required>
                        </div>

                        <!-- Deskripsi -->
                        <div>
                            <label class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">Deskripsi</label>
                            <textarea name="deskripsi" rows="3" 
                                class="glass-input w-full px-5 py-4 rounded-xl text-white placeholder-gray-500 focus:ring-2 focus:ring-pink-500/50 transition-all resize-none"
                                placeholder="Deskripsi singkat tentang tempat ini...">{{ $tempat->deskripsi }}</textarea>
                        </div>

                        <!-- Status & Akses -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Status -->
                            <div class="bg-white/5 p-5 rounded-2xl border border-white/5">
                                <p class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-3">Status</p>
                                <div class="flex items-center">
                                    @if($tempat->status == 'aktif')
                                        <span class="inline-flex items-center px-4 py-2 rounded-xl text-sm font-bold bg-emerald-500/10 text-emerald-400 border border-emerald-500/20">
                                            <span class="w-2 h-2 mr-2 bg-emerald-400 rounded-full animate-pulse"></span> AKTIF
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-4 py-2 rounded-xl text-sm font-bold bg-yellow-500/10 text-yellow-400 border border-yellow-500/20">
                                            <span class="w-2 h-2 mr-2 bg-yellow-400 rounded-full"></span> PENDING
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <!-- Publik/Private Toggle (Logika Inverse) -->
                            <div class="bg-white/5 p-5 rounded-2xl border border-white/5">
                                <p class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-3">Akses</p>
                                <label class="flex items-center cursor-pointer group">
                                    <div class="relative">
                                        <!-- LOGIC:
                                             1. Hidden default value = 1 (Publik).
                                             2. Checkbox value = 0 (Privat).
                                             3. Jika Checked (Kanan) -> Value 0 (Privat).
                                             4. Jika Unchecked (Kiri) -> Value 1 (Publik).
                                        -->
                                        <input type="hidden" name="is_public" value="1">
                                        <input type="checkbox" name="is_public" id="is_public" value="0" 
                                            {{ $tempat->is_public == 0 ? 'checked' : '' }} 
                                            class="sr-only peer">
                                        
                                        <div class="w-14 h-7 bg-gray-700 rounded-full peer peer-checked:bg-gradient-to-r peer-checked:from-pink-500 peer-checked:to-purple-500 transition-all"></div>
                                        <div class="absolute left-1 top-1 w-5 h-5 bg-white rounded-full shadow-md peer-checked:translate-x-7 transition-transform"></div>
                                    </div>
                                    <span id="access-label" class="ml-4 text-sm font-bold text-white group-hover:text-pink-400 transition-colors">
                                        {{ $tempat->is_public == 0 ? 'Privat' : 'Publik' }}
                                    </span>
                                </label>
                            </div>
                        </div>

                        <!-- Kode Referral (jika privat) -->
                        <div id="referral-section" class="{{ $tempat->is_public == 0 ? '' : 'hidden' }} bg-purple-500/10 p-5 rounded-2xl border border-purple-500/20">
                            <p class="text-xs font-bold text-purple-400 uppercase tracking-wider mb-2">Kode Referral</p>
                            <div class="flex items-center gap-3">
                                <span class="font-mono text-2xl font-bold text-white tracking-widest">{{ $tempat->kode_referral }}</span>
                                <button type="button" onclick="navigator.clipboard.writeText('{{ $tempat->kode_referral }}')" 
                                    class="p-2 bg-white/10 hover:bg-white/20 rounded-xl transition-all group/btn">
                                    <svg class="w-5 h-5 text-purple-400 group-hover/btn:text-purple-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 5H6a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2v-1M8 5a2 2 0 002 2h2a2 2 0 002-2M8 5a2 2 0 012-2h2a2 2 0 012 2m0 0h2a2 2 0 012 2v3m2 4H10m0 0l3-3m-3 3l3 3"></path></svg>
                                </button>
                            </div>
                            <p class="text-xs text-gray-500 mt-2">Bagikan kode ini untuk mengundang anggota baru</p>
                        </div>
                    </div>
                </div>

                <!-- Section 2: Mode Keuangan -->
                <div class="mb-8">
                    <h3 class="text-lg font-black text-white mb-4 uppercase tracking-wider flex items-center">
                        <span class="w-1.5 h-6 bg-gradient-to-b from-purple-500 to-indigo-600 rounded-full mr-3"></span>
                        Mode Keuangan
                    </h3>
                    <p class="text-sm text-gray-500 mb-6">Pilih bagaimana sistem kas berjalan di tempat ini.</p>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        
                        <!-- Pilihan 1: Kas Umum -->
                        <label class="cursor-pointer relative group">
                            <input type="radio" name="use_individual_ledger" value="0" {{ !$tempat->use_individual_ledger ? 'checked' : '' }} class="peer sr-only" onchange="toggleLedgerMode()">
                            <div class="p-6 border-2 border-white/10 rounded-2xl peer-checked:border-emerald-500/50 peer-checked:bg-emerald-500/5 transition-all hover:bg-white/5 h-full">
                                <div class="flex items-start justify-between mb-3">
                                    <div class="p-2 bg-emerald-500/10 rounded-xl">
                                        <svg class="w-6 h-6 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                                    </div>
                                    <span class="bg-white/10 text-gray-400 text-[10px] font-bold px-2 py-1 rounded uppercase">Default</span>
                                </div>
                                <h4 class="font-bold text-lg text-white mb-2 peer-checked:text-emerald-400">Kas Umum</h4>
                                <p class="text-sm text-gray-500 leading-relaxed">
                                    Hanya mencatat pemasukan & pengeluaran kas total. Tidak ada catatan saldo per anggota.
                                </p>
                                <p class="text-xs text-gray-600 mt-3 italic">Cocok untuk: Masjid, Yayasan, Organisasi Sukarela.</p>
                            </div>
                        </label>

                        <!-- Pilihan 2: Kas Perorangan -->
                        <label class="cursor-pointer relative group">
                            <input type="radio" name="use_individual_ledger" value="1" {{ $tempat->use_individual_ledger ? 'checked' : '' }} class="peer sr-only" onchange="toggleLedgerMode()">
                            <div class="p-6 border-2 border-white/10 rounded-2xl peer-checked:border-pink-500/50 peer-checked:bg-pink-500/5 transition-all hover:bg-white/5 h-full">
                                <div class="flex items-start justify-between mb-3">
                                    <div class="p-2 bg-pink-500/10 rounded-xl">
                                        <svg class="w-6 h-6 text-pink-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                                    </div>
                                    <span class="bg-pink-500/10 text-pink-400 text-[10px] font-bold px-2 py-1 rounded uppercase">Advanced</span>
                                </div>
                                <h4 class="font-bold text-lg text-white mb-2 peer-checked:text-pink-400">Kas Perorangan</h4>
                                <p class="text-sm text-gray-500 leading-relaxed">
                                    Mencatat siapa yang setor, berapa saldo/tunggakannya, dan distribusi biaya bersama.
                                </p>
                                <p class="text-xs text-gray-600 mt-3 italic">Cocok untuk: Kelas SMA, Angkatan, Arisan.</p>
                            </div>
                        </label>
                    </div>

                    <!-- Detail Kas Perorangan (Hidden jika Kas Umum) -->
                    <div id="ledger-settings" class="{{ $tempat->use_individual_ledger ? 'mt-6 block' : 'mt-6 hidden' }}">
                        <div class="bg-white/5 p-6 rounded-2xl border border-white/10">
                            <h5 class="font-bold text-white mb-4 flex items-center">
                                <svg class="w-5 h-5 mr-2 text-pink-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4"></path></svg>
                                Fitur Tambahan
                            </h5>
                            
                            <div class="space-y-4">
                                <label class="flex items-start cursor-pointer group p-4 bg-white/5 rounded-xl hover:bg-white/10 transition-all">
                                    <input type="checkbox" name="use_mandatory_cash" value="1" {{ $tempat->use_mandatory_cash ? 'checked' : '' }} 
                                        class="mt-1 w-5 h-5 text-pink-600 rounded focus:ring-pink-500 bg-white/10 border-white/20">
                                    <div class="ml-4">
                                        <span class="font-semibold text-white group-hover:text-pink-400 transition-colors">Aktifkan Kas Wajib</span>
                                        <p class="text-xs text-gray-500 mt-1">Kategori kas dengan target dan aturan minus (tunggakan).</p>
                                    </div>
                                </label>

                                <label class="flex items-start cursor-pointer group p-4 bg-white/5 rounded-xl hover:bg-white/10 transition-all">
                                    <input type="checkbox" name="shared_expense_enabled" value="1" {{ $tempat->shared_expense_enabled ? 'checked' : '' }} 
                                        class="mt-1 w-5 h-5 text-pink-600 rounded focus:ring-pink-500 bg-white/10 border-white/20">
                                    <div class="ml-4">
                                        <span class="font-semibold text-white group-hover:text-pink-400 transition-colors">Aktifkan Shared Expense</span>
                                        <p class="text-xs text-gray-500 mt-1">Pengeluaran bisa otomatis dibagi rata ke semua anggota.</p>
                                    </div>
                                </label>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Tombol Simpan -->
                <div class="flex flex-col sm:flex-row justify-end gap-4 pt-6 border-t border-white/10">
                    <a href="{{ route('admin.dashboard') }}" class="px-8 py-3 bg-white/5 text-gray-400 rounded-xl font-bold hover:bg-white/10 hover:text-white transition-all text-center border border-white/10">
                        Batal
                    </a>
                    <button type="submit" class="px-8 py-3 bg-gradient-to-r from-pink-500 to-purple-600 text-white rounded-xl font-bold shadow-lg shadow-pink-500/20 hover:shadow-pink-500/40 hover:scale-105 transition-all">
                        Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    function toggleLedgerMode() {
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
    
    // Script untuk toggle Akses (Publik/Privat) & Update Label/Referral Section
    const accessToggle = document.getElementById('is_public');
    const accessLabel = document.getElementById('access-label');
    const referralSection = document.getElementById('referral-section');

    function updateAccessUI() {
        if (accessToggle.checked) {
            // Kanan (Checked) -> Private (Value 0)
            accessLabel.textContent = 'Privat';
            referralSection.classList.remove('hidden');
        } else {
            // Kiri (Unchecked) -> Public (Value 1)
            accessLabel.textContent = 'Publik';
            referralSection.classList.add('hidden');
        }
    }

    // Init on load
    updateAccessUI();
    
    // Listen to change
    accessToggle?.addEventListener('change', updateAccessUI);
</script>
@endsection