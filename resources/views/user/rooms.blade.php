@extends('layouts.app')

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

        <div class="grid grid-cols-1 md:grid-cols-2 gap-8" id="rooms-container">
            @forelse($rooms as $room)
                <div class="glass-card rounded-[2rem] p-8 flex flex-col justify-between shadow-2xl hover:shadow-pink-500/20 hover:-translate-y-1 transition-all duration-300 border border-white/30 bg-black/20 backdrop-blur-lg room-card" data-id="{{ $room->id }}">
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

                        {{-- TOMBOL LEAVE AJAX --}}
                        <button type="button" 
                                data-id="{{ $room->id }}"
                                data-name="{{ $room->nama }}"
                                class="btn-leave-ajax p-3 text-gray-300 hover:text-red-400 hover:bg-white/10 rounded-xl transition-all border border-transparent hover:border-white/20 group" title="Keluar Ruangan">
                            <svg class="w-5 h-5 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                        </button>
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
    document.addEventListener('DOMContentLoaded', function() {
        const csrfToken = document.querySelector('meta[name="csrf-token"]');
        
        if (!csrfToken) {
            console.error('CSRF Token tidak ditemukan!');
            return;
        }

        // --- LOGIKA LEAVE AJAX ---
        const leaveButtons = document.querySelectorAll('.btn-leave-ajax');
        
        leaveButtons.forEach(btn => {
            btn.addEventListener('click', function(e) {
                const roomName = this.dataset.name;
                if(!confirm(`Yakin ingin keluar dari ${roomName}?`)) return;

                const id = this.dataset.id;
                const url = `/room/${id}/leave`;
                const card = this.closest('.room-card'); // Target card untuk dihapus
                
                // Animasi Loading
                this.innerHTML = '...';
                this.disabled = true;

                fetch(url, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken.getAttribute('content'),
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({})
                })
                .then(response => {
                    if (!response.ok) throw new Error('Gagal keluar');
                    return response.json();
                })
                .then(data => {
                    // Animasi Hilang (Fade Out)
                    card.style.transition = 'all 0.4s ease';
                    card.style.opacity = '0';
                    card.style.transform = 'scale(0.9)';
                    
                    // Hapus elemen setelah animasi
                    setTimeout(() => {
                        card.remove();
                        // Cek jika container kosong, tampilkan pesan empty (opsional, bisa reload)
                        if(document.querySelectorAll('.room-card').length === 0) {
                            location.reload(); // Reload untuk menampilkan state empty
                        }
                    }, 400);
                })
                .catch(error => {
                    // Kembalikan tombol jika gagal
                    this.innerHTML = '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>';
                    this.disabled = false;
                    alert('Terjadi kesalahan, coba lagi.');
                });
            });
        });
    });
</script>
@endsection