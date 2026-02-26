@extends('layouts.app')

@section('content')
<div class="container mx-auto py-8 px-4">
    <div class="max-w-5xl mx-auto">
        <div class="mb-6">
            <a href="{{ route('pages.index', $tempat->id) }}" class="text-blue-600 hover:underline">&larr; Kembali ke Kelola Halaman</a>
            <h1 class="text-2xl font-bold mt-2">Edit Halaman: {{ $page->judul }}</h1>
        </div>

        <div class="bg-white shadow rounded-lg p-6 border border-gray-200">
            <form method="POST" action="{{ route('pages.update', $page->id) }}">
                @csrf
                @method('PUT')

                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2">Judul Halaman</label>
                    <input type="text" name="judul" value="{{ $page->judul }}" class="w-full border p-2 rounded" required>
                </div>

                <!-- TINYMCE EDITOR -->
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2">Konten Info</label>
                    <textarea id="mytextarea" name="content" class="w-full border p-2 rounded">
                        {{ $page->content }}
                    </textarea>
                </div>

                <div class="flex justify-end">
                    <button type="submit" class="bg-green-600 text-white px-6 py-2 rounded font-bold">
                        Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- TINYMCE SCRIPT (ini make API KEY dari .ENV) -->
<!-- Jika ada di .env, dia pakai API Key. Jika tidak, pakai no-api-key -->
<script src="https://cdn.tiny.cloud/1/{{ env('TINYMCE_API_KEY', 'no-api-key') }}/tinymce/6/tinymce.min.js" referrerpolicy="origin"></script>
<script>
    tinymce.init({
        selector: '#mytextarea',
        plugins: 'anchor autolink charmap codesample emoticons image link lists media searchreplace table visualblocks wordcount checklist mediaembed casechange export formatpainter pageembed permanentpen footnotes advtemplate advtable advcode editimage tableofcontents mergetags powerpaste tinymcespellchecker autocorrect a11ychecker typography inlinecss',
        toolbar: 'undo redo | blocks fontfamily fontsize | bold italic underline strikethrough | link image media table mergetags | align lineheight | tinycomments | checklist numlist bullist indent outdent | emoticons charmap | removeformat',
        height: 500,
        automatic_uploads: true,
        file_picker_types: 'image',
        images_upload_url: 'postAcceptor.php',
    });
</script>
@endsection