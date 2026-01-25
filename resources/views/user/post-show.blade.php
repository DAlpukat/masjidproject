@extends('layouts.app')

@section('content')
<div class="container mx-auto py-8 px-4">
    <div class="max-w-4xl mx-auto bg-white p-8 rounded-lg shadow border border-gray-200">
        
        <a href="{{ route('room.view', $post->page->tempatLayanan->slug) }}#info-{{ $post->page->id }}" class="text-blue-600 hover:underline mb-4 inline-block">
            &larr; Kembali ke Berita
        </a>

        <h1 class="text-3xl font-bold text-gray-900 mb-4">{{ $post->title }}</h1>
        <p class="text-sm text-gray-500 mb-6">{{ $post->created_at->format('d F Y, H:i') }}</p>

        <!-- Gambar Utama -->
        <img src="{{ asset('storage/' . $post->image) }}" class="w-full rounded-lg mb-8 shadow-sm">
        
        <div class="prose prose-lg max-w-full break-words overflow-x-hidden">
            {!! $post->content !!}
        </div>
    </div>
</div>
@endsection