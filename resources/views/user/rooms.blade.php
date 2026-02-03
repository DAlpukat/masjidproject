@extends('layouts.app')

@section('content')
<div class="bg-mesh-elegant min-h-screen py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-5xl mx-auto">
        
        <!-- Header Section -->
        <div class="mb-10 text-center md:text-left">
            <span class="px-4 py-1.5 text-xs font-bold tracking-widest text-pink-600 uppercase bg-pink-100 rounded-full inline-block mb-3 shadow-sm">
                Workspace Saya
            </span>
            <h1 class="text-4xl font-black text-gray-900 mb-2 tracking-tight">
                Ruang <span class="text-transparent bg-clip-text bg-gradient-to-r from-pink-500 to-purple-600">Layanan</span>
            </h1>
            <p class="text-gray-500 font-medium max-w-lg">Akses dashboard dan fitur kas dari tempat layanan yang telah Anda ikuti.</p>
        </div>

        <!-- Alert Message -->
        @if(session('success') || session('error'))
            <div class="{{ session('success') ? 'bg-green-50 border-green-200 text-green-700' : 'bg-red-50 border-red-200 text-red-700' }} border-l-4 p-4 mb-8 rounded-xl shadow-sm flex items-center animate-fade-in-down">
                <p class="text-sm font-bold">{{ session('success') ?: session('error') }}</p>
            </div>
        @endif

        <!-- Grid Room Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
            @forelse($rooms as $room)
                <div class="glass-card rounded-[2rem] p-8 flex flex-col justify-between shadow-lg hover:shadow-xl hover:-translate-y-1 transition-all duration-300 border border-white/20 bg-white/40">
                    <div>
                        <div class="flex justify-between items-start mb-6">
                            <!-- Icon Avatar -->
                            <div class="w-14 h-14 rounded-2xl bg-gradient-to-tr from-pink-100 to-purple-100 flex items-center justify-center text-pink-600 font-black text-2xl shadow-inner">
                                {{ substr($room->nama, 0, 1) }}
                            </div>
                            
                            <!-- Status Badge -->
                            @if($room->status == 'aktif')
                                <span class="flex items-center px-3 py-1 bg-emerald-50 text-emerald-600 text-[10px] font-black uppercase tracking-tighter rounded-lg border border-emerald-100 shadow-sm">
                                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 mr-1.5 animate-pulse"></span> Aktif
                                </span>
                            @else
                                <span class="px-3 py-1 bg-amber-50 text-amber-600 text-[10px] font-black uppercase tracking-tighter rounded-lg border border-amber-100 shadow-sm">
                                    Pending Approval
                                </span>
                            @endif
                        </div>
                        
                        <h2 class="text-2xl font-extrabold text-gray-800 mb-3 leading-tight">
                            {{ $room->nama }}
                        </h2>
                        
                        <p class="text-gray-500 text-sm mb-6 line-clamp-2 leading-relaxed min-h-[2.5rem]">
                            {{ $room->deskripsi ?: 'Tidak ada deskripsi untuk ruangan ini.' }}
                        </p>

                        <!-- Meta Stats -->
                        <div class="grid grid-cols-2 gap-4 mb-6">
                            <div class="bg-white/60 px-4 py-3 rounded-2xl border border-white/50 shadow-sm">
                                <p class="text-[10px] text-gray-400 uppercase font-bold tracking-wider leading-none mb-1">Anggota</p>
                                <p class="text-lg font-bold text-gray-700">{{ $room->anggota_count ?? 0 }} <span class="text-xs font-normal text-gray-400">Orang</span></p>
                            </div>
                            <div class="bg-white/60 px-4 py-3 rounded-2xl border border-white/50 shadow-sm">
                                <p class="text-[10px] text-gray-400 uppercase font-bold tracking-wider leading-none mb-1">Tipe</p>
                                <p class="text-sm font-bold {{ $room->is_public ? 'text-blue-500' : 'text-purple-500' }}">
                                    {{ $room->is_public ? 'Publik' : 'Privat' }}
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="flex items-center gap-3 mt-auto pt-4 border-t border-white/30">
                        @if($room->status == 'aktif')
                            <a href="{{ route('room.view', $room->slug) }}" class="flex-1 text-center py-3 rounded-xl btn-gradient-pink font-bold text-sm tracking-wide shadow-md hover:shadow-lg transform hover:-translate-y-0.5 transition-all text-white">
                                Masuk Ruangan →
                            </a>
                        @else
                            <button disabled class="flex-1 text-center py-3 rounded-xl bg-gray-200 text-gray-500 font-bold text-sm tracking-wide cursor-not-allowed">
                                Menunggu Persetujuan
                            </button>
                        @endif

                        <form action="{{ route('room.leave', $room->id) }}" method="POST" onsubmit="return confirm('Yakin ingin keluar dari {{ $room->nama }}?');" class="inline">
                            @csrf
                            <button type="submit" class="p-3 text-gray-400 hover:text-red-500 hover:bg-red-50 rounded-xl transition-all border border-transparent hover:border-red-100 group" title="Keluar Ruangan">
                                <svg class="w-5 h-5 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                            </button>
                        </form>
                    </div>
                </div>
            @empty
                <!-- Empty State -->
                <div class="col-span-full glass-card rounded-[2.5rem] p-16 text-center border border-white/20 bg-white/40">
                    <div class="w-24 h-24 bg-pink-50 rounded-full flex items-center justify-center mx-auto mb-6 shadow-inner">
                        <svg class="w-10 h-10 text-pink-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-800 mb-2">Belum Ada Ruangan</h3>
                    <p class="text-gray-500 mb-8 max-w-md mx-auto">Anda belum bergabung dengan tempat layanan manapun. Cari room publik atau gunakan kode referral untuk bergabung.</p>
                    <a href="{{ route('home') }}" class="inline-flex items-center px-8 py-3 btn-gradient-pink rounded-full font-bold shadow-lg hover:shadow-xl transform hover:-translate-y-1 transition-all text-white">
                        Cari Ruangan Sekarang
                    </a>
                </div>
            @endforelse
        </div>
    </div>
</div>
@endsection