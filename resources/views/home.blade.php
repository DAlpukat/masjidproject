@extends('layouts.app')

@section('content')
<div class="py-10">
    
    <div class="text-center mb-12 reveal active">
        <span class="inline-block px-4 py-1 text-[10px] font-bold tracking-[0.2em] text-white uppercase bg-white/10 border border-white/20 rounded-full mb-4">
            Explore Services
        </span>
        <h1 class="text-4xl md:text-5xl font-black text-white tracking-tight mb-4">
            Temukan <span class="text-gradient-mono">Layanan</span>
        </h1>
        <p class="text-gray-400 font-medium max-w-2xl mx-auto">
            Bergabung dengan komunitas dan tempat layanan favoritmu dalam ekosistem yang transparan.
        </p>
    </div>

    <div class="glass-card p-8 mb-10 relative overflow-hidden group reveal delay-100 rounded-3xl">
        <div class="absolute -right-20 -top-20 w-64 h-64 bg-white/10 rounded-full blur-3xl opacity-20 group-hover:opacity-40 transition-opacity duration-500 pointer-events-none"></div>

        <div class="relative z-10 flex flex-col md:flex-row items-center justify-between gap-8">
            <div class="flex-1 text-center md:text-left">
                <h2 class="text-xl font-bold text-white flex items-center justify-center md:justify-start gap-3">
                    <div class="w-10 h-10 rounded-full bg-white/10 flex items-center justify-center border border-white/20">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11.536 15.536a2.5 2.5 0 00-3.536 0l-3 3a2.5 2.5 0 000 3.536l3 3a2.5 2.5 0 003.536 0l2.829-2.829A6 6 0 0121 9z"></path></svg>
                    </div>
                    Punya Kode Undangan?
                </h2>
                <p class="text-sm text-gray-400 mt-2 pl-0 md:pl-14">
                    Masukkan kode referral kelas atau layanan untuk bergabung secara instan.
                </p>
            </div>
            
            <form method="POST" action="{{ route('join.store') }}" class="w-full md:w-auto flex flex-col sm:flex-row gap-3">
                @csrf
                <input type="text" name="kode" placeholder="KODE (X9Z2KA)" class="glass-input px-6 py-3 w-full md:w-64 font-mono uppercase tracking-wider text-sm rounded-xl text-center sm:text-left" required>
                <button type="submit" class="btn-monochrome whitespace-nowrap">
                    Gabung
                </button>
            </form>
        </div>
    </div>

    @if(session('success'))
        <div class="glass-card border-l-4 border-emerald-500 bg-emerald-500/10 mb-8 p-4 flex items-center animate-soft-pulse rounded-xl">
            <div class="bg-emerald-500/20 p-2 rounded-lg mr-3 shadow-sm border border-emerald-500/30">
                <svg class="w-5 h-5 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>
            </div>
            <span class="font-bold text-white text-sm">{{ session('success') }}</span>
        </div>
    @endif

    <div class="glass-card p-6 mb-10 reveal delay-200 rounded-2xl">
        <form action="{{ route('home') }}" method="GET">
            <div class="grid grid-cols-1 md:grid-cols-12 gap-5 items-end">
                <div class="md:col-span-5">
                    <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-2">Cari Tempat</label>
                    <div class="relative">
                        <input type="text" name="search" value="{{ request('search') }}" class="glass-input px-4 py-3 w-full rounded-xl pl-10" placeholder="Ketik nama tempat...">
                        <svg class="w-5 h-5 text-gray-500 absolute left-3 top-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                    </div>
                </div>

                <div class="md:col-span-3">
                    <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-2">Status</label>
                    <select name="status" class="glass-input px-4 py-3 w-full appearance-none cursor-pointer rounded-xl">
                        <option value="aktif" class="bg-black text-gray-300" {{ request('status') == 'aktif' || request('status') == null ? 'selected' : '' }}>Aktif</option>
                        <option value="pending" class="bg-black text-gray-300" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="nonaktif" class="bg-black text-gray-300" {{ request('status') == 'nonaktif' ? 'selected' : '' }}>Nonaktif</option>
                    </select>
                </div>

                <div class="md:col-span-3">
                    <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-2">Urutkan</label>
                    <select name="sort" class="glass-input px-4 py-3 w-full appearance-none cursor-pointer rounded-xl">
                        <option value="latest" class="bg-black text-gray-300" {{ request('sort') == 'latest' || request('sort') == null ? 'selected' : '' }}>Terbaru</option>
                        <option value="oldest" class="bg-black text-gray-300" {{ request('sort') == 'oldest' ? 'selected' : '' }}>Terlama</option>
                        <option value="name_asc" class="bg-black text-gray-300" {{ request('sort') == 'name_asc' ? 'selected' : '' }}>A-Z</option>
                        <option value="name_desc" class="bg-black text-gray-300" {{ request('sort') == 'name_desc' ? 'selected' : '' }}>Z-A</option>
                    </select>
                </div>

                <div class="md:col-span-1">
                    <button type="submit" class="w-full h-[46px] bg-white text-black hover:bg-gray-200 rounded-xl transition-all flex items-center justify-center">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                    </button>
                </div>
            </div>
        </form>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($publicPlaces as $place)
            <div class="glass-card p-6 relative overflow-hidden group hover:-translate-y-2 transition-transform duration-300 rounded-2xl reveal delay-300">
                
                <div class="absolute inset-0 bg-gradient-to-br from-white/5 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>

                <div class="flex items-start justify-between mb-5 relative z-10">
                    <div class="flex items-center gap-4">
                        <div class="w-14 h-14 rounded-2xl bg-white/5 border border-white/10 flex items-center justify-center text-xl font-black text-white shadow-lg group-hover:scale-110 group-hover:bg-white/10 transition-all duration-300">
                            {{ strtoupper(substr($place->nama, 0, 1)) }}
                        </div>
                        <div>
                            <h3 class="text-lg font-bold text-white leading-tight group-hover:text-gray-200 transition-colors">{{ $place->nama }}</h3>
                            <div class="text-[10px] font-bold text-gray-500 uppercase tracking-widest mt-1">#{{ $place->slug }}</div>
                        </div>
                    </div>

                    <div class="text-right">
                        @if($place->status == 'aktif')
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-[10px] font-bold bg-emerald-500/10 text-emerald-400 border border-emerald-500/20 shadow-[0_0_10px_rgba(16,185,129,0.2)]">
                                <span class="w-1.5 h-1.5 mr-2 bg-emerald-400 rounded-full animate-pulse"></span> AKTIF
                            </span>
                        @elseif($place->status == 'pending')
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-[10px] font-bold bg-yellow-500/10 text-yellow-400 border border-yellow-500/20">
                                PENDING
                            </span>
                        @else
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-[10px] font-bold bg-red-500/10 text-red-400 border border-red-500/20">
                                OFF
                            </span>
                        @endif
                    </div>
                </div>

                <p class="relative z-10 text-sm text-gray-400 leading-relaxed mb-6 min-h-[40px] desc-clamp border-l-2 border-white/10 pl-3">
                    {{ $place->deskripsi ?: 'Tidak ada deskripsi tersedia untuk layanan ini.' }}
                </p>

                <div class="relative z-10 pt-4 border-t border-white/10 flex items-center justify-between">
                    <div class="flex items-center text-xs font-bold text-gray-400">
                        <svg class="w-4 h-4 mr-2 text-white/50" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                        {{ $place->members_count }} Anggota
                    </div>

                    @if(auth()->check() && $place->users->contains(auth()->id()))
                        <a href="{{ route('room.view', $place->slug) }}" class="px-4 py-2 bg-white/5 hover:bg-white/10 border border-white/10 text-white rounded-lg text-xs font-bold transition-all flex items-center gap-2 group/btn">
                            <span>Masuk Room</span>
                            <svg class="w-3 h-3 transform group-hover/btn:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                        </a>

                    @elseif(auth()->check())
                        <form action="{{ route('join.public', $place->id) }}" method="POST">
                            @csrf
                            <button type="submit" class="px-5 py-2 bg-white text-black hover:bg-gray-200 rounded-lg text-xs font-bold shadow-lg shadow-white/10 hover:shadow-white/20 hover:scale-105 active:scale-95 transition-all">
                                + Gabung
                            </button>
                        </form>

                    @else
                        <a href="{{ route('login') }}" class="px-4 py-2 border border-white/30 text-white hover:bg-white/10 rounded-lg text-xs font-bold transition-all">
                            Login Dulu
                        </a>
                    @endif
                </div>
            </div>
        @empty
            <div class="col-span-1 md:col-span-3 py-16 text-center">
                <div class="inline-flex p-6 rounded-full bg-white/5 border border-white/10 mb-4 animate-soft-pulse">
                    <svg class="w-12 h-12 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.384-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"></path></svg>
                </div>
                <p class="text-white font-bold tracking-tight text-lg">BELUM ADA TEMPAT LAYANAN</p>
                <p class="text-sm text-gray-500 mt-2">Coba ubah kata kunci pencarian atau filter status.</p>
            </div>
        @endforelse
    </div>

    <div class="mt-12 flex justify-center">
        <div class="scale-90 opacity-80 hover:opacity-100 transition-opacity">
            {{ $publicPlaces->appends(request()->query())->links('pagination::tailwind') }} 
        </div>
    </div>
    
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Efek stagger untuk card
        const cards = document.querySelectorAll('.glass-card');
        cards.forEach((card, index) => {
            setTimeout(() => {
                card.classList.add('active');
            }, index * 100); // Delay bertingkat
        });
    });
</script>
@endsection