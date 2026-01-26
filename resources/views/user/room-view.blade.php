@extends('layouts.app')

@section('content')
<div class="bg-mesh-elegant min-h-screen py-10 px-4">
    <div class="max-w-6xl mx-auto">
        
        <div class="mb-10">
            <a href="{{ route('user.rooms') }}" class="inline-flex items-center text-sm font-bold text-pink-500 hover:text-pink-700 transition-colors mb-6 group">
                <svg class="w-4 h-4 mr-2 group-hover:-translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                Kembali ke Ruangan Saya
            </a>
            
            <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-6">
                <div class="flex items-center max-w-full overflow-hidden">
                    <div class="flex-shrink-0 w-14 h-14 rounded-2xl bg-gradient-to-br from-pink-500 to-purple-600 flex items-center justify-center text-white text-2xl font-black shadow-lg mr-5">
                        {{ substr($tempat->nama, 0, 1) }}
                    </div>
                    <div class="min-w-0 flex-1">
                        <h1 class="text-3xl md:text-4xl font-black text-gray-800 leading-tight break-all">{{ $tempat->nama }}</h1>
                        <p class="text-gray-500 font-medium italic mt-1 desc-clamp w-full">
                            {{ $tempat->deskripsi ?: 'Welcome to our workspace.' }}
                        </p>
                    </div>
                </div>
                
                @if(auth()->id() == $tempat->user_id)
                    <a href="{{ route('pages.index', $tempat->id) }}" class="flex-shrink-0 inline-flex items-center px-6 py-3 bg-white text-purple-600 border-2 border-purple-100 rounded-2xl hover:bg-purple-50 font-bold shadow-sm transition-all active:scale-95">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4"></path></svg>
                        Admin Panel
                    </a>
                @endif
            </div>
        </div>

        @if($pages->count() > 0)
            <div class="glass-card rounded-2xl px-6 mb-10 overflow-x-auto shadow-sm border-white/50 scrollbar-hide">
                <div class="flex space-x-8 min-w-max">
                    @foreach($pages as $page)
                        @php $link = ($page->tipe == 'kas') ? route('laporan.show', [$tempat->slug, $page->id]) : "#{$page->tipe}-{$page->id}"; @endphp
                        <a href="{{ $link }}" class="nav-tab-item">
                            @if($page->tipe == 'kas') 
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            @else
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"></path></svg>
                            @endif
                            {{ $page->judul }}
                        </a>
                    @endforeach
                </div>
            </div>
        @endif

        @forelse($pages as $page)
            <div id="{{ $page->tipe }}-{{ $page->id }}" class="mb-16 scroll-mt-24">
                <div class="flex items-center mb-8">
                    <h2 class="text-2xl font-black text-gray-800 mr-4 break-all">{{ $page->judul }}</h2>
                    <div class="h-px flex-1 bg-gradient-to-r from-pink-200 to-transparent"></div>
                </div>
                
                @if($page->tipe == 'kas')
                    <div class="glass-card p-10 rounded-[2.5rem] border-pink-100 text-center relative overflow-hidden group/kas">
                        <div class="absolute -top-10 -right-10 opacity-[0.03] group-hover/kas:scale-110 transition-transform duration-700">
                            <svg class="w-64 h-64 text-purple-600" fill="currentColor" viewBox="0 0 20 20"><path d="M2 10a8 8 0 018-8v8h8a8 8 0 11-16 0z"></path><path d="M12 2.252A8.014 8.014 0 0117.748 8H12V2.252z"></path></svg>
                        </div>
                        <p class="text-gray-600 text-lg mb-8 relative z-10 font-medium">Laporan keuangan transparan tersedia untuk semua anggota.</p>
                        <a href="{{ route('laporan.show', [$tempat->slug, $page->id]) }}" class="btn-gradient-pink px-10 py-4 rounded-2xl font-bold text-lg">
                            Buka Dashboard Kas
                            <svg class="w-6 h-6 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"></path></svg>
                        </a>
                    </div>

                @elseif($page->tipe == 'info')
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                        @forelse($page->posts as $post)
                            <a href="{{ route('post.show', $post->id) }}" class="group block h-full">
                                <div class="glass-card rounded-[2rem] overflow-hidden border-white h-full shadow-sm hover:shadow-2xl">
                                    <div class="relative overflow-hidden h-56 bg-gray-100">
                                        <img src="{{ asset('storage/' . $post->image) }}" alt="{{ $post->title }}" class="w-full h-full object-cover img-hover-zoom">
                                        <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-transparent to-transparent opacity-0 group-hover:opacity-100 transition-all duration-500 flex items-end p-6">
                                            <span class="text-white text-xs font-black uppercase tracking-[0.2em]">Detail Berita</span>
                                        </div>
                                    </div>
                                    <div class="p-8">
                                        <div class="flex items-center text-pink-500 text-[10px] font-black uppercase tracking-widest mb-4">
                                            <span class="w-8 h-px bg-pink-200 mr-2"></span>
                                            {{ $post->created_at->format('d M Y') }}
                                        </div>
                                        <h3 class="font-bold text-xl text-gray-800 leading-snug desc-clamp group-hover:text-pink-600 transition-colors">
                                            {{ $post->title }}
                                        </h3>
                                    </div>
                                </div>
                            </a>
                        @empty
                            <div class="col-span-full py-16 text-center glass-card rounded-[2rem] border-dashed">
                                <p class="text-gray-400 font-bold uppercase tracking-widest">Belum Ada Informasi Baru</p>
                            </div>
                        @endforelse
                    </div>
                @endif
            </div>
        @empty
            <div class="text-center py-32 glass-card rounded-[3rem] border-dashed border-2">
                <div class="w-20 h-20 bg-pink-50 rounded-full flex items-center justify-center mx-auto mb-6 shadow-inner">
                    <svg class="w-10 h-10 text-pink-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </div>
                <h3 class="text-xl font-bold text-gray-400 uppercase tracking-[0.3em] italic">Workspace Kosong</h3>
            </div>
        @endforelse

    </div>
</div>
@endsection