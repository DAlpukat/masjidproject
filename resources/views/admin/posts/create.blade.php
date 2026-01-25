@extends('layouts.app')

@section('content')
<div class="container mx-auto py-8 px-4">
    <div class="max-w-4xl mx-auto">
        <div class="mb-6">
            <a href="{{ route('posts.index', $page->id) }}" class="text-blue-600 hover:underline">&larr; Kembali</a>
            <h1 class="text-2xl font-bold mt-2">Tulis Berita Baru</h1>
        </div>

        <div class="bg-white shadow rounded-lg p-6 border border-gray-200">
            <form method="POST" action="{{ route('posts.store') }}" enctype="multipart/form-data">
                @csrf
                
                <input type="hidden" name="page_id" value="{{ $page->id }}">

                <div class="mb-4">
                    <label class="block font-bold mb-2">Judul Berita</label>
                    <input type="text" name="title" class="w-full border p-2 rounded" required>
                </div>

                <!-- Wajib Upload Gambar -->
                <div class="mb-4">
                    <label class="block font-bold mb-2">Gambar Utama (Thumbnail)</label>
                    <input type="file" name="image" class="w-full border p-2 rounded" required accept="image/*">
                    <p class="text-xs text-gray-500 mt-1">Wajib diisi. Muncul di kotak daftar berita.</p>
                </div>

                <!-- Konten Editor -->
                <div class="mb-4">
                    <label class="block font-bold mb-2">Isi Berita</label>
                    <textarea id="mytextarea" name="content" class="w-full border p-2 rounded"></textarea>
                </div>

                <div class="flex justify-end">
                    <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded font-bold hover:bg-blue-700">
                        Terbitkan Berita
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- TINYMCE SCRIPT -->
<script src="https://cdn.tiny.cloud/1/{{ env('TINYMCE_API_KEY', 'no-api-key') }}/tinymce/6/tinymce.min.js" referrerpolicy="origin"></script>
<script>
    tinymce.init({
        selector: '#mytextarea',
        plugins: 'anchor autolink charmap codesample emoticons image link lists media searchreplace table visualblocks wordcount checklist mediaembed casechange export formatpainter pageembed permanentpen footnotes advtemplate advtable advcode editimage tableofcontents mergetags powerpaste tinymcespellchecker autocorrect a11ychecker typography inlinecss',
        toolbar: 'undo redo | blocks fontfamily fontsize | bold italic underline strikethrough | link image media table mergetags | align lineheight | tinycomments | checklist numlist bullist indent outdent | emoticons charmap | removeformat',
        height: 500,
        automatic_uploads: true,
    });
</script>
@endsection