@extends('layouts.app')

@section('content')
<!-- CONTAINER UTAMA DENGAN BACKGROUND MESH GRADIENT (Sesuai Admin) -->
<div class="min-h-screen py-10 px-4" style="background: radial-gradient(at 0% 0%, rgba(255, 192, 203, 0.4) 0px, transparent 50%), radial-gradient(at 100% 100%, rgba(221, 160, 221, 0.3) 0px, transparent 50%), #ffffff;">
    <div class="max-w-6xl mx-auto">
        
        <!-- HEADER SECTION -->
        <div class="text-center mb-10">
            <span class="inline-block px-3 py-1 text-xs font-bold tracking-widest text-pink-500 uppercase bg-pink-100 rounded-full mb-3">
                Explore
            </span>
            <h1 class="text-4xl md:text-5xl font-black text-gray-800 tracking-tight mb-2">
                Temukan <span class="text-transparent bg-clip-text bg-gradient-to-r from-pink-500 to-purple-600">Layanan</span>
            </h1>
            <p class="text-gray-500 font-medium">Bergabung dengan komunitas dan tempat layanan favoritmu.</p>
        </div>

        <!-- FORM JOIN REFERRAL (Glass Card Style) -->
        <div class="glass-card p-6 mb-8 relative overflow-hidden group">
            <!-- Dekorasi Background Glow -->
            <div class="absolute -right-10 -top-10 w-40 h-40 bg-pink-400 rounded-full mix-blend-multiply filter blur-3xl opacity-20 group-hover:opacity-30 transition-opacity duration-500"></div>

            <div class="relative z-10 flex flex-col md:flex-row items-center justify-between gap-6">
                <div class="flex-1">
                    <h2 class="text-lg font-bold text-gray-800 flex items-center gap-2">
                        <svg class="w-5 h-5 text-pink-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11.536 15.536a2.5 2.5 0 00-3.536 0l-3 3a2.5 2.5 0 000 3.536l3 3a2.5 2.5 0 003.536 0l2.829-2.829A6 6 0 0121 9z"></path></svg>
                        Punya Kode Undangan?
                    </h2>
                    <p class="text-sm text-gray-500 mt-1">Masukkan kode referral kelas/layanan untuk bergabung.</p>
                </div>
                
                <form method="POST" action="{{ route('join.store') }}" class="w-full md:w-auto flex gap-2">
                    @csrf
                    <input type="text" name="kode" placeholder="KODE (X9Z2KA)" class="glass-input px-4 py-2 w-full md:w-48 font-mono uppercase tracking-wider text-sm" required>
                    <button type="submit" class="bg-gradient-to-r from-pink-500 to-purple-600 text-white px-6 py-2 rounded-xl font-bold shadow-lg shadow-pink-200/50 hover:shadow-pink-300/60 hover:scale-105 active:scale-95 transition-all whitespace-nowrap">
                        Gabung
                    </button>
                </form>
            </div>
        </div>

        <!-- NOTIFIKASI -->
        @if(session('success'))
            <div class="glass-card border-l-4 border-green-400 bg-white/80 mb-8 p-4 flex items-center animate-bounce-short">
                <div class="bg-green-500 p-1.5 rounded-lg mr-3 shadow-sm">
                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>
                </div>
                <span class="font-bold text-gray-700 text-sm">{{ session('success') }}</span>
            </div>
        @endif

        <!-- FILTER & SEARCH SECTION (Glass Style) -->
        <div class="glass-card p-6 mb-8">
            <form action="{{ route('home') }}" method="GET">
                <div class="grid grid-cols-1 md:grid-cols-12 gap-4 items-end">
                    <!-- Search -->
                    <div class="md:col-span-5">
                        <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Cari Tempat</label>
                        <input type="text" name="search" value="{{ request('search') }}" class="glass-input px-4 py-3 w-full" placeholder="Ketik nama tempat...">
                    </div>

                    <!-- Filter Status -->
                    <div class="md:col-span-3">
                        <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Status</label>
                        <select name="status" class="glass-input px-4 py-3 w-full appearance-none cursor-pointer">
                            <option value="aktif" {{ request('status') == 'aktif' || request('status') == null ? 'selected' : '' }}>Aktif</option>
                            <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="nonaktif" {{ request('status') == 'nonaktif' ? 'selected' : '' }}>Nonaktif</option>
                        </select>
                    </div>

                    <!-- Sort -->
                    <div class="md:col-span-3">
                        <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Urutkan</label>
                        <select name="sort" class="glass-input px-4 py-3 w-full appearance-none cursor-pointer">
                            <option value="latest" {{ request('sort') == 'latest' || request('sort') == null ? 'selected' : '' }}>Terbaru</option>
                            <option value="oldest" {{ request('sort') == 'oldest' ? 'selected' : '' }}>Terlama</option>
                            <option value="name_asc" {{ request('sort') == 'name_asc' ? 'selected' : '' }}>A-Z</option>
                            <option value="name_desc" {{ request('sort') == 'name_desc' ? 'selected' : '' }}>Z-A</option>
                        </select>
                    </div>

                    <!-- Button Submit -->
                    <div class="md:col-span-1">
                        <button type="submit" class="w-full h-12 bg-gray-800 text-white rounded-xl shadow-lg hover:bg-gray-900 transition-colors flex items-center justify-center">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                        </button>
                    </div>
                </div>
            </form>
        </div>

        <!-- LIST TEMPAT (GRID) -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @forelse($publicPlaces as $place)
                <div class="glass-card p-6 relative overflow-hidden group hover:-translate-y-1 transition-transform duration-300">
                    
                    <!-- Header Card: Avatar & Info -->
                    <div class="flex items-start justify-between mb-4">
                        <div class="flex items-center gap-4">
                            <!-- Avatar Inisial -->
                            <div class="w-14 h-14 rounded-2xl bg-gradient-to-tr from-pink-100 to-purple-100 flex items-center justify-center text-2xl font-black text-pink-600 shadow-sm group-hover:scale-110 transition-transform duration-300">
                                {{ strtoupper(substr($place->nama, 0, 1)) }}
                            </div>
                            <div>
                                <h3 class="text-lg font-bold text-gray-800 leading-tight group-hover:text-pink-600 transition-colors">{{ $place->nama }}</h3>
                                <div class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mt-1">#{{ $place->slug }}</div>
                            </div>
                        </div>

                        <!-- Status Badge (Mirip Admin) -->
                        <div class="text-right">
                            @if($place->status == 'aktif')
                                <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-[10px] font-black bg-green-50 text-green-600 border border-green-100">
                                    <span class="w-1.5 h-1.5 mr-1.5 bg-green-500 rounded-full animate-pulse"></span> AKTIF
                                </span>
                            @elseif($place->status == 'pending')
                                <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-[10px] font-black bg-orange-50 text-orange-600 border border-orange-100">
                                    PENDING
                                </span>
                            @else
                                <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-[10px] font-black bg-red-50 text-red-600 border border-red-100">
                                    OFF
                                </span>
                            @endif
                        </div>
                    </div>

                    <!-- Deskripsi -->
                    <p class="text-sm text-gray-500 leading-relaxed mb-6 min-h-[40px] desc-clamp-2">
                        {{ $place->deskripsi ?: 'Tidak ada deskripsi tersedia untuk layanan ini.' }}
                    </p>

                    <!-- Footer: Anggota & Tombol -->
                    <div class="pt-4 border-t border-gray-100/50 flex items-center justify-between">
                        <!-- GUNAKAN MEMBERS_COUNT & WARNA GELAP (Slate-600) -->
                        <div class="flex items-center text-xs font-bold text-slate-600">
                            <svg class="w-4 h-4 mr-1.5 text-pink-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                            {{ $place->members_count }} Anggota
                        </div>

                        <!-- ACTION BUTTONS -->
                        @if(auth()->check() && $place->users->contains(auth()->id()))
                            <!-- SUDAH GABUNG -->
                            <a href="{{ route('room.view', $place->slug) }}" class="group/btn px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-600 rounded-xl text-xs font-bold transition-all flex items-center gap-2">
                                <span>Masuk Room</span>
                                <svg class="w-3 h-3 transform group-hover/btn:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                            </a>

                        @elseif(auth()->check())
                            <!-- BELUM GABUNG -->
                            <form action="{{ route('join.public', $place->id) }}" method="POST">
                                @csrf
                                <button type="submit" class="px-5 py-2 bg-gradient-to-r from-pink-500 to-purple-600 text-white rounded-xl text-xs font-bold shadow-lg shadow-pink-200/50 hover:shadow-pink-300/60 hover:scale-105 active:scale-95 transition-all">
                                    + Gabung
                                </button>
                            </form>

                        @else
                            <!-- BELUM LOGIN -->
                            <a href="{{ route('login') }}" class="px-4 py-2 border-2 border-pink-200 text-pink-600 hover:bg-pink-50 rounded-xl text-xs font-bold transition-all">
                                Login Dulu
                            </a>
                        @endif
                    </div>
                </div>
            @empty
                <div class="col-span-1 md:col-span-3 py-12 text-center">
                    <div class="inline-flex p-6 rounded-full bg-pink-50 mb-4">
                        <svg class="w-12 h-12 text-pink-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.384-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"></path></svg>
                    </div>
                    <p class="text-gray-400 font-bold tracking-tight">BELUM ADA TEMPAT LAYANAN</p>
                    <p class="text-xs text-gray-300 mt-1">Coba ubah kata kunci pencarianmu.</p>
                </div>
            @endforelse
        </div>

        <!-- PAGINATION -->
        <div class="mt-12 flex justify-center">
            <!-- Style default pagination Laravel agar lebih cantik -->
            <div class="inline-flex rounded-xl shadow-sm overflow-hidden">
                {{ $publicPlaces->appends(request()->query())->links('pagination::bootstrap-4') }} 
                <!-- Catatan: Jika kamu pakai pagination bawaan Tailwind, biarkan default saja: {{ $publicPlaces->appends(request()->query())->links() }} -->
            </div>
        </div>
        
        <!-- FOOTER TEXT -->
        <p class="mt-12 text-center text-gray-300 text-[10px] font-bold uppercase tracking-[0.2em]">
            &copy; {{ date('Y') }} Sistem Management Layanan
        </p>

    </div>
</div>
@endsection