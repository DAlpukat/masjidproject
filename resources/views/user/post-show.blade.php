@extends('layouts.app')

@section('content')
<div class="bg-monochrome-gif"></div>
<div class="bg-overlay"></div>

<div class="min-h-screen py-12 px-4 sm:px-6 lg:px-8 relative z-10">
    <div class="max-w-3xl mx-auto">
        
        <!-- Tombol Kembali -->
        <div class="mb-8">
            <a href="{{ $backUrl }}" 
               class="inline-flex items-center text-sm font-bold text-pink-400 hover:text-pink-300 transition-colors group">
                <svg class="w-5 h-5 mr-2 group-hover:-translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Kembali
            </a>
        </div>

        <!-- Artikel Card -->
        <article class="glass-card rounded-[2rem] overflow-hidden shadow-2xl border border-white/10 mb-12">
            
            <!-- Header -->
            <header class="p-8 md:p-10 pb-4 border-b border-white/5">
                <div class="flex items-center text-pink-400 text-xs font-black uppercase tracking-[0.3em] mb-4">
                    <span class="w-12 h-px bg-pink-500/50 mr-4"></span>
                    {{-- Mengubah ke WITA (Asia/Makassar) --}}
                    {{ $post->created_at->timezone('Asia/Makassar')->format('d F Y') }}
                </div>
                <h1 class="text-2xl md:text-4xl font-black text-white leading-tight mb-4 break-words">
                    {{ $post->title }}
                </h1>
                <div class="flex items-center text-gray-400 text-xs italic">
                    <svg class="w-4 h-4 mr-2 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    {{-- Mengubah jam ke WITA --}}
                    Waktu publikasi: {{ $post->created_at->timezone('Asia/Makassar')->format('H:i') }} WITA
                </div>
            </header>

            <!-- Gambar (Ukuran Kecil/Optimal) -->
            @if($post->image)
            <div class="p-6 md:p-10 flex justify-center bg-white/5">
                <div class="relative overflow-hidden rounded-xl border border-white/10 shadow-lg">
                    <img src="{{ asset('storage/' . $post->image) }}" 
                         alt="{{ $post->title }}" 
                         class="max-h-96 w-auto object-contain mx-auto transition-transform duration-500 hover:scale-105 cursor-pointer">
                </div>
            </div>
            @endif

            <!-- Konten -->
            <div class="p-8 md:p-10 pt-6">
                <div class="prose prose-invert max-w-none text-gray-200 leading-relaxed break-words">
                    {!! $post->content !!}
                </div>
                
                <!-- Dekorasi Bawah -->
                <div class="mt-12 flex items-center justify-center opacity-50">
                    <div class="flex space-x-2">
                        <div class="w-2 h-2 rounded-full bg-pink-400"></div>
                        <div class="w-2 h-2 rounded-full bg-pink-500"></div>
                        <div class="w-2 h-2 rounded-full bg-pink-600"></div>
                    </div>
                </div>
            </div>
        </article>

    </div>
</div>
@endsection