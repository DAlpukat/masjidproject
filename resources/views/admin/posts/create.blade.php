@extends('layouts.app')

@section('content')
<div class="bg-monochrome-gif"></div>
<div class="bg-overlay"></div>

<div class="min-h-screen py-12 px-4 sm:px-6 lg:px-8 relative z-10">
    <div class="max-w-4xl mx-auto">
        
        <!-- Header -->
        <div class="mb-8">
            <a href="{{ route('admin.posts.index', $page->id) }}" class="text-pink-400 hover:text-pink-300 hover:underline font-bold text-sm inline-flex items-center mb-4">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                Kembali ke Daftar Post
            </a>
            <h1 class="text-3xl font-black text-white tracking-tight">Tulis Berita Baru</h1>
            <p class="text-gray-400 mt-1">Buat konten baru untuk halaman ini.</p>
        </div>

        <!-- Form Card -->
        <div class="glass-card rounded-2xl p-8 border border-white/10">
            <form method="POST" action="{{ route('admin.posts.store') }}" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="page_id" value="{{ $page->id }}">

                <div class="space-y-6">
                    <!-- Judul -->
                    <div>
                        <label class="block text-xs font-bold text-gray-300 uppercase mb-2">Judul Berita</label>
                        <input type="text" name="title" class="glass-input w-full" placeholder="Judul yang menarik..." required>
                    </div>

                    <!-- Gambar -->
                    <div>
                        <label class="block text-xs font-bold text-gray-300 uppercase mb-2">Gambar Utama (Thumbnail)</label>
                        <input type="file" name="image" class="w-full text-sm text-gray-300 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-pink-800 file:text-pink-100 hover:file:bg-pink-700 cursor-pointer" required accept="image/*">
                        <p class="text-xs text-gray-500 mt-2">Wajib diisi. Gambar ini akan muncul di daftar berita.</p>
                    </div>

                    <!-- Konten Editor -->
                    <div>
                        <label class="block text-xs font-bold text-gray-300 uppercase mb-2">Isi Berita</label>
                        <textarea id="mytextarea" name="content" class="w-full" rows="10"></textarea>
                    </div>

                    <!-- Submit -->
                    <div class="pt-4 border-t border-white/10">
                        <button type="submit" class="btn-gradient-pink w-full py-3 rounded-xl font-bold shadow-lg hover:scale-[1.02] transition text-white">
                            Terbitkan Berita
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- TINYMCE SCRIPT -->
<!-- Menggunakan no-api-key untuk menghindari warning domain -->
 <script src="https://cdn.tiny.cloud/1/{{ env('TINYMCE_API_KEY', 'no-api-key') }}/tinymce/6/tinymce.min.js" referrerpolicy="origin"></script>
<script>
    tinymce.init({
        selector: '#mytextarea',
    
        plugins: 'anchor autolink charmap codesample emoticons image link lists media searchreplace table visualblocks wordcount code',
        
        toolbar: 'undo redo | blocks fontfamily fontsize | bold italic underline strikethrough | link image media table | align lineheight | numlist bullist indent outdent | emoticons charmap | removeformat code',
        
        height: 500,
        
        promotion: false, 
        branding: false, 
        

        skin: 'oxide-dark',
        content_css: 'dark',
    
        automatic_uploads: true,
    });
</script>
@endsection