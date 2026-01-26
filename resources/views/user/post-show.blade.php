@extends('layouts.app')

@section('content')
<div class="bg-mesh-elegant min-h-screen py-10 px-4">
    <div class="max-w-4xl mx-auto">
        
        <div class="mb-8">
            <a href="{{ route('room.view', $post->page->tempatLayanan->slug) }}#info-{{ $post->page->id }}" 
               class="inline-flex items-center text-sm font-bold text-pink-500 hover:text-pink-700 transition-colors group">
                <svg class="w-5 h-5 mr-2 group-hover:-translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Kembali ke Ruangan
            </a>
        </div>

        <article class="glass-card rounded-[3rem] overflow-hidden shadow-2xl border-white/60 mb-12">
            
            <header class="p-8 md:p-14 pb-4">
                <div class="flex items-center text-pink-500 text-xs font-black uppercase tracking-[0.3em] mb-6">
                    <span class="w-12 h-px bg-pink-200 mr-4"></span>
                    {{ $post->created_at->format('d F Y') }}
                </div>
                <h1 class="text-3xl md:text-5xl font-black text-gray-900 leading-[1.15] mb-6 break-words">
                    {{ $post->title }}
                </h1>
                <div class="flex items-center text-gray-400 text-sm italic">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    Waktu publikasi: {{ $post->created_at->format('H:i') }} WIB
                </div>
            </header>

            <div class="px-8 md:px-14">
                <div class="banner-container group shadow-inner">
                    <img src="{{ asset('storage/' . $post->image) }}" 
                         alt="{{ $post->title }}" 
                         class="banner-image img-hover-zoom">
                </div>
            </div>

            <div class="p-8 md:p-14 pt-12">
                <div class="prose-custom break-words">
                    {!! $post->content !!}
                </div>
                
                <div class="mt-16 flex items-center justify-center">
                    <div class="flex space-x-2">
                        <div class="w-2 h-2 rounded-full bg-pink-200"></div>
                        <div class="w-2 h-2 rounded-full bg-pink-300"></div>
                        <div class="w-2 h-2 rounded-full bg-pink-400"></div>
                    </div>
                </div>
            </div>
        </article>

    </div>
</div>
@endsection