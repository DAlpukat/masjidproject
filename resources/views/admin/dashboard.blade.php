@extends('layouts.app')

@section('content')
<div class="bg-monochrome-gif"></div>
<div class="bg-overlay"></div>

<div class="min-h-screen py-12 px-4 sm:px-6 lg:px-8 relative z-10">
    <div class="max-w-6xl mx-auto">
        
        <div class="mb-10">
            <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-6">
                <div class="space-y-2">
                    <span class="px-4 py-1.5 text-xs font-bold tracking-widest text-pink-400 uppercase bg-pink-500/10 border border-pink-500/20 rounded-full inline-block mb-3">
                        Control Panel
                    </span>
                    <h1 class="text-4xl font-black text-white tracking-tight">
                        Dashboard <span class="text-transparent bg-clip-text bg-gradient-to-r from-pink-400 to-purple-400">Saya</span>
                    </h1>
                    <p class="text-gray-400 font-medium">Kelola operasional dan anggota layanan Anda dengan mudah.</p>
                </div>
                
                <a href="{{ route('admin.temp.create') }}" class="group relative inline-flex items-center px-8 py-3.5 overflow-hidden text-white bg-gradient-to-br from-pink-500 to-purple-600 rounded-2xl shadow-xl shadow-pink-500/20 transition-all hover:shadow-pink-500/40 hover:scale-105 active:scale-95">
                    <span class="absolute right-0 w-8 h-32 -mt-12 transition-all duration-1000 transform translate-x-12 bg-white opacity-10 rotate-12 group-hover:-translate-x-40 ease"></span>
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                    <span class="font-bold tracking-wide">Tambah Layanan</span>
                </a>
            </div>
        </div>

        @if(session('success'))
            <div class="glass-card border-l-4 border-emerald-500 bg-emerald-500/10 mb-8 p-4 flex items-center animate-soft-pulse rounded-xl">
                <div class="bg-emerald-500/20 p-2 rounded-lg mr-3 shadow-sm border border-emerald-500/30">
                    <svg class="w-5 h-5 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>
                </div>
                <span class="font-bold text-white text-sm">{!! session('success') !!}</span>
            </div>
        @endif

        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
            @forelse($tempatLayanans as $item)
                <div class="glass-card rounded-[2rem] p-8 flex flex-col justify-between shadow-lg hover:shadow-xl hover:-translate-y-1 transition-all duration-300 border border-white/10 relative overflow-hidden group">
                    
                    <div class="absolute -right-10 -top-10 w-40 h-40 bg-pink-500/5 rounded-full blur-2xl group-hover:bg-pink-500/10 transition-all duration-500"></div>
                    
                    <div class="relative z-10">
                        <div class="flex justify-between items-start mb-6">
                            <div class="w-14 h-14 rounded-2xl bg-gradient-to-br from-pink-500/20 to-purple-500/20 border border-pink-500/20 flex items-center justify-center text-pink-400 font-black text-2xl shadow-inner">
                                {{ substr($item->nama, 0, 1) }}
                            </div>
                            
                            @if($item->status == 'aktif')
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-[10px] font-bold bg-emerald-500/10 text-emerald-400 border border-emerald-500/20 shadow-[0_0_10px_rgba(16,185,129,0.2)]">
                                    <span class="w-1.5 h-1.5 mr-1.5 bg-emerald-400 rounded-full animate-pulse"></span> AKTIF
                                </span>
                            @else
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-[10px] font-bold bg-yellow-500/10 text-yellow-400 border border-yellow-500/20">
                                    <span class="w-1.5 h-1.5 mr-1.5 bg-yellow-400 rounded-full"></span> PENDING
                                </span>
                            @endif
                        </div>
                        
                        <h2 class="text-2xl font-extrabold text-white mb-2 leading-tight">
                            {{ $item->nama }}
                        </h2>
                        <p class="text-pink-400/60 text-xs font-medium mb-4">#{{ $item->slug }}</p>
                        
                        <div class="mb-6">
                            @if($item->is_public)
                                <div class="flex items-center text-blue-400 font-bold text-sm">
                                    <svg class="w-4 h-4 mr-1.5" fill="currentColor" viewBox="0 0 20 20"><path d="M10 12a2 2 0 100-4 2 2 0 000 4z"></path><path fill-rule="evenodd" d="M.458 10C1.732 5.943 5.523 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z" clip-rule="evenodd"></path></svg>
                                    <span class="px-2 py-0.5 bg-blue-500/10 border border-blue-500/20 rounded text-xs">Publik</span>
                                </div>
                            @else
                            <button onclick="copyToClipboard(this, '{{ $item->kode_referral }}')" 
                                class="flex items-center group/btn space-x-2 bg-white/5 px-4 py-2 rounded-xl border border-white/10 hover:border-pink-500/30 transition-all active:scale-95">
                                
                                <span class="font-mono font-bold text-pink-400">{{ $item->kode_referral }}</span>
                                
                                <div class="icon-container">
                                    <svg class="copy-icon w-4 h-4 text-gray-500 group-hover/btn:text-pink-400 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7v8a2 2 0 002 2h6M8 7V5a2 2 0 012-2h4.586a1 1 0 01.707.293l4.414 4.414a1 1 0 01.293.707V15a2 2 0 01-2 2h-2M8 7H6a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2v-2"></path>
                                    </svg>
                                    <svg class="check-icon hidden w-4 h-4 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                    </svg>
                                </div>
                            </button>
                            @endif
                        </div>

                        <div class="grid grid-cols-2 gap-4 mb-6">
                            <div class="bg-white/5 px-4 py-3 rounded-2xl border border-white/5">
                                <p class="text-[10px] text-gray-500 uppercase font-bold tracking-wider leading-none mb-1">Anggota</p>
                                <div class="flex items-center">
                                    <div class="flex items-center justify-center w-7 h-7 rounded-full bg-pink-500/20 text-white text-[10px] font-bold border border-pink-500/30">
                                        {{ $item->members_count }}
                                    </div>
                                    <span class="ml-2 text-sm font-bold text-white">Orang</span>
                                </div>
                            </div>
                            <div class="bg-white/5 px-4 py-3 rounded-2xl border border-white/5">
                                <p class="text-[10px] text-gray-500 uppercase font-bold tracking-wider leading-none mb-1">Akses</p>
                                <p class="text-sm font-bold {{ $item->is_public ? 'text-blue-400' : 'text-purple-400' }}">
                                    {{ $item->is_public ? 'Publik' : 'Privat' }}
                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="flex items-center gap-4 mt-auto pt-6 border-t border-white/10 relative z-10">
                        <a href="{{ route('admin.temp.edit', $item->id) }}" class="btn-action-mono group/btn" title="Edit Pengaturan">
                            <svg class="w-5 h-5 transition-transform duration-300 group-hover/btn:-rotate-12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                        </a>
                        
                        <a href="{{ route('admin.group.members', $item->id) }}" class="btn-action-mono group/btn" title="Lihat Anggota">
                            <svg class="w-5 h-5 transition-transform duration-300 group-hover/btn:scale-110" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                        </a>

                        <a href="{{ route('admin.pages.index', $item->id) }}" class="btn-action-mono group/btn" title="Kelola Halaman">
                            <svg class="w-5 h-5 transition-transform duration-300 group-hover/btn:rotate-90" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                        </a>
                        
                        <form action="{{ route('admin.temp.destroy', $item->id) }}" method="POST" onsubmit="return confirm('Hapus permanen?');" class="ml-auto">
                            @csrf @method('DELETE')
                            <button type="submit" class="btn-action-danger group/btn" title="Hapus Layanan">
                                <svg class="w-5 h-5 transition-all duration-300 group-hover/btn:rotate-12 group-hover/btn:scale-110" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                            </button>
                        </form>
                    </div>
                    </div>
            @empty
                <div class="col-span-full glass-card rounded-[2.5rem] p-16 text-center border border-white/10">
                    <div class="w-24 h-24 bg-pink-500/10 rounded-full flex items-center justify-center mx-auto mb-6 shadow-inner border border-pink-500/20">
                        <svg class="w-10 h-10 text-pink-400/50" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                    </div>
                    <h3 class="text-xl font-bold text-white mb-2">Belum Ada Layanan</h3>
                    <p class="text-gray-500 mb-8 max-w-md mx-auto">Anda belum membuat tempat layanan. Klik tombol di atas untuk membuat layanan baru.</p>
                </div>
            @endforelse
        </div>
    </div>
</div>

<script>
function copyToClipboard(btn, text) {
    navigator.clipboard.writeText(text);
    
    // Ambil elemen ikon
    const copyIcon = btn.querySelector('.copy-icon');
    const checkIcon = btn.querySelector('.check-icon');
    
    // Tukar ikon
    copyIcon.classList.add('hidden');
    checkIcon.classList.remove('hidden');
    
    // Balikkan ikon ke semula setelah 2 detik
    setTimeout(() => {
        copyIcon.classList.remove('hidden');
        checkIcon.classList.add('hidden');
    }, 2000);
}
</script>
@endsection