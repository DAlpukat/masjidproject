@extends('layouts.app')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
@section('content')
<div class="bg-mesh-elegant min-h-screen py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-5xl mx-auto">
        
        <div class="mb-10 text-center md:text-left">
            <span class="px-4 py-1.5 text-xs font-bold tracking-widest text-white uppercase bg-pink-600/80 backdrop-blur-sm rounded-full inline-block mb-3 shadow-lg">
                Workspace Saya
            </span>
            <h1 class="text-4xl font-black text-white mb-2 tracking-tight drop-shadow-md">
                Ruang <span class="text-transparent bg-clip-text bg-gradient-to-r from-pink-300 to-purple-300">Layanan</span>
            </h1>
            <p class="text-gray-100 font-medium max-w-lg drop-shadow-sm">Akses dashboard dan fitur kas dari tempat layanan yang telah Anda ikuti.</p>
        </div>

        @if(session('success') || session('error'))
            <div class="{{ session('success') ? 'bg-green-500/20 border-green-400 text-white' : 'bg-red-500/20 border-red-400 text-white' }} backdrop-blur-md border-l-4 p-4 mb-8 rounded-xl shadow-md flex items-center animate-fade-in-down">
                <p class="text-sm font-bold">{{ session('success') ?: session('error') }}</p>
            </div>
        @endif

        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
            @forelse($rooms as $room)
                <div class="glass-card rounded-[2rem] p-8 flex flex-col justify-between shadow-2xl hover:shadow-pink-500/20 hover:-translate-y-1 transition-all duration-300 border border-white/30 bg-black/20 backdrop-blur-lg">
                    <div>
                        <div class="flex justify-between items-start mb-6">
                            <div class="w-14 h-14 rounded-2xl bg-gradient-to-tr from-white to-pink-100 flex items-center justify-center text-pink-600 font-black text-2xl shadow-xl">
                                {{ substr($room->nama, 0, 1) }}
                            </div>
                            
                            @if($room->status == 'aktif')
                                <span class="flex items-center px-3 py-1 bg-emerald-500 text-white text-[10px] font-black uppercase tracking-tighter rounded-lg shadow-lg">
                                    <span class="w-1.5 h-1.5 rounded-full bg-white mr-1.5 animate-pulse"></span> Aktif
                                </span>
                            @else
                                <span class="px-3 py-1 bg-amber-500 text-white text-[10px] font-black uppercase tracking-tighter rounded-lg shadow-lg">
                                    Pending Approval
                                </span>
                            @endif
                        </div>
                        
                        <h2 class="text-2xl font-extrabold text-white mb-3 leading-tight drop-shadow-sm">
                            {{ $room->nama }}
                        </h2>
                        
                        <p class="text-gray-200 text-sm mb-6 line-clamp-2 leading-relaxed min-h-[2.5rem]">
                            {{ $room->deskripsi ?: 'Tidak ada deskripsi untuk ruangan ini.' }}
                        </p>

                        <div class="grid grid-cols-2 gap-4 mb-6">
                            <div class="bg-white/10 px-4 py-3 rounded-2xl border border-white/20 shadow-inner">
                                <p class="text-[10px] text-pink-200 uppercase font-bold tracking-wider leading-none mb-1">Anggota</p>
                                <p class="text-lg font-bold text-white">
                                    {{ $room->anggota_count ?? 0 }} 
                                    <span class="text-xs font-normal text-gray-300">Orang</span>
                                </p>
                            </div>
                            <div class="bg-white/10 px-4 py-3 rounded-2xl border border-white/20 shadow-inner">
                                <p class="text-[10px] text-purple-200 uppercase font-bold tracking-wider leading-none mb-1">Tipe</p>
                                <p class="text-sm font-bold {{ $room->is_public ? 'text-blue-300' : 'text-purple-300' }}">
                                    {{ $room->is_public ? 'Publik' : 'Privat' }}
                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="flex items-center gap-3 mt-auto pt-4 border-t border-white/10">
                        @if($room->status == 'aktif')
                            <a href="{{ route('room.view', $room->slug) }}" class="flex-1 text-center py-3 rounded-xl btn-gradient-pink font-bold text-sm tracking-wide shadow-lg hover:shadow-pink-500/50 transform hover:-translate-y-0.5 transition-all text-white">
                                Masuk Ruangan →
                            </a>
                        @else
                            <button disabled class="flex-1 text-center py-3 rounded-xl bg-white/10 text-gray-400 font-bold text-sm tracking-wide cursor-not-allowed border border-white/5">
                                Menunggu Persetujuan
                            </button>
                        @endif

                        <form id="leave-room-form-{{ $room->id }}" action="{{ route('room.leave', $room->id) }}" method="POST" class="inline">
                            @csrf
                            <button type="button" onclick="confirmLeave('{{ $room->id }}', '{{ $room->nama }}')" 
                                class="p-3 text-gray-300 hover:text-red-400 hover:bg-white/10 rounded-xl transition-all border border-transparent hover:border-white/20 group" title="Keluar Ruangan">
                                <svg class="w-5 h-5 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                                </svg>
                            </button>
                        </form>
                    </div>
                </div>
            @empty
                <div class="col-span-full glass-card rounded-[2.5rem] p-16 text-center border border-white/30 bg-black/20">
                    <div class="w-24 h-24 bg-white/10 rounded-full flex items-center justify-center mx-auto mb-6 shadow-inner">
                        <svg class="w-10 h-10 text-pink-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                    </div>
                    <h3 class="text-xl font-bold text-white mb-2">Belum Ada Ruangan</h3>
                    <p class="text-gray-300 mb-8 max-w-md mx-auto">Anda belum bergabung dengan tempat layanan manapun. Cari room publik atau gunakan kode referral untuk bergabung.</p>
                    <a href="{{ route('home') }}" class="inline-flex items-center px-8 py-3 btn-gradient-pink rounded-full font-bold shadow-lg text-white">
                        Cari Ruangan Sekarang
                    </a>
                </div>
            @endforelse
        </div>
    </div>
</div>
<script>
function confirmLeave(roomId, roomName) {
    Swal.fire({
        title: '<span class="text-white">Keluar Ruangan?</span>',
        html: `<span class="text-gray-400">Yakin ingin keluar dari <b>${roomName}</b>?<br>Anda harus menggunakan kode referral lagi untuk masuk.</span>`,
        icon: 'warning',
        iconColor: '#f472b6', // Warna pink-400
        showCancelButton: true,
        confirmButtonColor: '#ec4899', // Pink-500
        cancelButtonColor: 'rgba(255,255,255,0.1)',
        confirmButtonText: 'Ya, Keluar!',
        cancelButtonText: 'Batal',
        background: '#111827', // Dark Gray (Match your theme)
        color: '#ffffff',
        borderRadius: '1.5rem',
        backdrop: `rgba(0,0,0,0.6) backdrop-blur-sm`, // Efek blur di belakang pop-up
        customClass: {
            popup: 'border border-white/10 glass-card shadow-2xl',
            confirmButton: 'rounded-xl px-6 py-2 font-bold uppercase text-xs tracking-widest',
            cancelButton: 'rounded-xl px-6 py-2 font-bold uppercase text-xs tracking-widest text-gray-300'
        }
    }).then((result) => {
        if (result.isConfirmed) {
            // Jalankan submit form jika user klik "Ya"
            document.getElementById('leave-room-form-' + roomId).submit();
        }
    });
}
</script>
@endsection