@extends('layouts.app')

@section('content')
<!-- TAMBAHKAN BACKGROUND DI SINI AGAR TEMA MASUK -->
<div class="bg-monochrome-gif"></div>
<div class="bg-overlay"></div>

<div class="min-h-screen py-12 px-4 sm:px-6 lg:px-8 relative z-10">
    <div class="max-w-2xl mx-auto">
        
        <!-- Header Section -->
        <div class="mb-10">
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-6">
                <div>
                    <span class="px-4 py-1.5 text-xs font-bold tracking-widest text-pink-400 uppercase bg-pink-500/10 border border-pink-500/20 rounded-full inline-block mb-3">
                        Buat Baru
                    </span>
                    <h1 class="text-3xl font-black text-white tracking-tight">
                        Tambah <span class="text-transparent bg-clip-text bg-gradient-to-r from-pink-400 to-purple-400">Layanan</span>
                    </h1>
                    <p class="text-gray-400 font-medium mt-1">Buat tempat layanan baru untuk komunitas Anda</p>
                </div>
                <a href="{{ route('admin.dashboard') }}" class="group inline-flex items-center text-sm font-bold text-pink-400 hover:text-pink-300 transition-colors">
                    <svg class="w-4 h-4 mr-2 group-hover:-translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                    Kembali ke Dashboard
                </a>
            </div>
        </div>

        <!-- Form Container -->
        <div class="glass-card p-8 rounded-[2rem] border border-white/10 shadow-xl">
            <form method="POST" action="{{ route('admin.temp.store') }}">
                @csrf

                <!-- Input Nama -->
                <div class="mb-6">
                    <label class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-3">
                        <svg class="w-4 h-4 inline-block mr-2 text-pink-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                        Nama Tempat Layanan
                    </label>
                    <input type="text" name="nama" 
                        class="glass-input w-full px-5 py-4 rounded-xl text-white placeholder-gray-500 focus:ring-2 focus:ring-pink-500/50 transition-all" 
                        placeholder="Contoh: Masjid Al-Hidayah, Kelas 12 IPA 1" required>
                </div>

                <!-- Input Deskripsi -->
                <div class="mb-8">
                    <label class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-3">
                        <svg class="w-4 h-4 inline-block mr-2 text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h7"></path></svg>
                        Deskripsi (Opsional)
                    </label>
                    <textarea name="deskripsi" rows="3" 
                        class="glass-input w-full px-5 py-4 rounded-xl text-white placeholder-gray-500 focus:ring-2 focus:ring-pink-500/50 transition-all resize-none"
                        placeholder="Deskripsi singkat tentang tempat layanan ini..."></textarea>
                </div>

                <!-- Pilihan Akses -->
                <div class="mb-8">
                    <label class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-4">
                        <svg class="w-4 h-4 inline-block mr-2 text-pink-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 11V7a4 4 0 118 0m-4 4v4m0 0h4m-4 0H8"></path></svg>
                        Siapa yang bisa bergabung?
                    </label>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <!-- Opsi Publik -->
                        <label class="cursor-pointer relative group">
                            <input type="radio" name="is_public" value="1" id="tipe_publik" checked class="peer sr-only">
                            <div class="p-6 border-2 border-white/10 rounded-2xl peer-checked:border-blue-500/50 peer-checked:bg-blue-500/5 transition-all hover:bg-white/5 h-full">
                                <div class="flex items-start justify-between mb-3">
                                    <div class="p-2 bg-blue-500/10 rounded-xl">
                                        <svg class="w-6 h-6 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 20 20"><path fill="currentColor" d="M10 12a2 2 0 100-4 2 2 0 000 4z"></path><path fill-rule="evenodd" d="M.458 10C1.732 5.943 5.523 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z" clip-rule="evenodd"></path></svg>
                                    </div>
                                    <span class="bg-blue-500/10 text-blue-400 text-[10px] font-bold px-2 py-1 rounded uppercase">Recommended</span>
                                </div>
                                <h4 class="font-bold text-lg text-white mb-2">Terbuka (Publik)</h4>
                                <p class="text-sm text-gray-500 leading-relaxed">
                                    Semua orang bisa melihat dan bergabung tanpa kode akses. Cocok untuk organisasi terbuka.
                                </p>
                            </div>
                        </label>

                        <!-- Opsi Privat -->
                        <label class="cursor-pointer relative group">
                            <input type="radio" name="is_public" value="0" id="tipe_private" class="peer sr-only">
                            <div class="p-6 border-2 border-white/10 rounded-2xl peer-checked:border-purple-500/50 peer-checked:bg-purple-500/5 transition-all hover:bg-white/5 h-full">
                                <div class="flex items-start justify-between mb-3">
                                    <div class="p-2 bg-purple-500/10 rounded-xl">
                                        <svg class="w-6 h-6 text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                                    </div>
                                    <span class="bg-purple-500/10 text-purple-400 text-[10px] font-bold px-2 py-1 rounded uppercase">Private</span>
                                </div>
                                <h4 class="font-bold text-lg text-white mb-2">Tertutup (Privat)</h4>
                                <p class="text-sm text-gray-500 leading-relaxed">
                                    Hanya bisa bergabung dengan kode referral unik. Cocok untuk kelompok eksklusif.
                                </p>
                                <p class="text-xs text-gray-600 mt-3 italic flex items-center">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                    Kode akan dibuat otomatis
                                </p>
                            </div>
                        </label>
                    </div>
                </div>

                <!-- Info Box -->
                <div class="bg-white/5 p-5 rounded-2xl border border-white/10 mb-8">
                    <div class="flex items-start gap-4">
                        <div class="p-2 bg-yellow-500/10 rounded-xl flex-shrink-0">
                            <svg class="w-5 h-5 text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        </div>
                        <div>
                            <h4 class="font-bold text-white mb-1">Perlu Persetujuan</h4>
                            <p class="text-sm text-gray-500">Tempat layanan yang Anda buat memerlukan persetujuan dari Superadmin sebelum dapat digunakan. Anda akan mendapat notifikasi setelah disetujui.</p>
                        </div>
                    </div>
                </div>

                <!-- Tombol Submit -->
                <div class="flex flex-col sm:flex-row justify-end gap-4">
                    <a href="{{ route('admin.dashboard') }}" class="px-8 py-3 bg-white/5 text-gray-400 rounded-xl font-bold hover:bg-white/10 hover:text-white transition-all text-center border border-white/10">
                        Batal
                    </a>
                    <button type="submit" class="px-8 py-3 bg-gradient-to-r from-pink-500 to-purple-600 text-white rounded-xl font-bold shadow-lg shadow-pink-500/20 hover:shadow-pink-500/40 hover:scale-105 transition-all flex items-center justify-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                        Buat Tempat Layanan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection