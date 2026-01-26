@extends('layouts.app')

@section('content')
<div class="bg-mesh-elegant py-12 px-4">
    <div class="max-w-5xl mx-auto">
        
        <div class="mb-10 text-center md:text-left">
            <span class="px-4 py-1.5 text-xs font-bold tracking-widest text-pink-600 uppercase bg-pink-100 rounded-full inline-block mb-3">
                Workspace Saya
            </span>
            <h1 class="text-4xl font-black text-gray-800 mb-2">
                Ruang <span class="text-gradient-pink">Layanan</span>
            </h1>
            <p class="text-gray-500 font-medium">Temukan dan akses kembali tempat layanan yang telah Anda ikuti.</p>
        </div>

        @if(session('success') || session('error'))
            <div class="{{ session('success') ? 'bg-green-50 border-green-200 text-green-700' : 'bg-pink-50 border-pink-200 text-pink-700' }} border-l-4 p-4 mb-8 rounded-xl shadow-sm animate-pulse-once">
                <p class="text-sm font-bold">{{ session('success') ?: session('error') }}</p>
            </div>
        @endif

        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
            @forelse($rooms as $room)
                <div class="glass-card rounded-[2rem] p-8 flex flex-col justify-between shadow-sm">
                    <div>
                        <div class="flex justify-between items-center mb-4">
                            <div class="w-12 h-12 rounded-2xl bg-gradient-to-tr from-pink-100 to-purple-100 flex items-center justify-center text-pink-600 font-black text-xl">
                                {{ substr($room->nama, 0, 1) }}
                            </div>
                            
                            @if($room->status == 'aktif')
                                <span class="flex items-center px-3 py-1 bg-green-50 text-green-600 text-[10px] font-black uppercase tracking-tighter rounded-lg border border-green-100">
                                    <span class="w-1.5 h-1.5 rounded-full bg-green-500 mr-1.5 animate-pulse"></span> Aktif
                                </span>
                            @else
                                <span class="px-3 py-1 bg-amber-50 text-amber-600 text-[10px] font-black uppercase tracking-tighter rounded-lg border border-amber-100">
                                    Pending
                                </span>
                            @endif
                        </div>
                        
                        <h2 class="text-2xl font-extrabold text-gray-800 mb-2 group-hover:text-pink-600 transition-colors">
                            {{ $room->nama }}
                        </h2>
                        
                        <p class="text-gray-500 text-sm mb-6 desc-clamp leading-relaxed">
                            {{ $room->deskripsi ?: 'Tidak ada deskripsi untuk ruangan ini.' }}
                        </p>

                        <div class="flex items-center gap-4 mb-6">
                            <div class="bg-white/50 px-3 py-1.5 rounded-xl border border-gray-100">
                                <p class="text-[10px] text-gray-400 uppercase font-bold tracking-wider leading-none">Anggota</p>
                                <p class="text-sm font-bold text-gray-700">{{ $room->anggota_count }} Orang</p>
                            </div>
                            <div class="bg-white/50 px-3 py-1.5 rounded-xl border border-gray-100">
                                <p class="text-[10px] text-gray-400 uppercase font-bold tracking-wider leading-none">ID Room</p>
                                <p class="text-sm font-mono font-bold text-pink-500">{{ $room->slug }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="flex items-center gap-3 mt-4">
                        <a href="{{ route('room.view', $room->slug) }}" class="flex-1 text-center py-3 rounded-xl btn-gradient-pink font-bold text-sm tracking-wide">
                            Masuk Ruangan
                        </a>

                        <form action="{{ route('room.leave', $room->id) }}" method="POST" onsubmit="return confirm('Keluar dari {{ $room->nama }}?');" class="inline">
                            @csrf
                            <button type="submit" class="p-3 text-pink-400 hover:text-red-500 hover:bg-red-50 rounded-xl transition-all border border-transparent hover:border-red-100">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                            </button>
                        </form>
                    </div>
                </div>
            @empty
                <div class="col-span-full glass-card rounded-[2.5rem] p-16 text-center">
                    <div class="w-20 h-20 bg-pink-50 rounded-full flex items-center justify-center mx-auto mb-6">
                        <svg class="w-10 h-10 text-pink-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-800 mb-2">Belum Bergabung</h3>
                    <p class="text-gray-500 mb-8">Anda belum terdaftar di ruangan manapun saat ini.</p>
                    <a href="{{ route('home') }}" class="inline-flex items-center px-8 py-3 btn-gradient-pink rounded-full font-bold">
                        Cari Ruangan Sekarang
                    </a>
                </div>
            @endforelse
        </div>
    </div>
</div>
@endsection