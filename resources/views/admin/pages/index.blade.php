@extends('layouts.app')

@section('content')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<div class="bg-monochrome-gif"></div>
<div class="bg-overlay"></div>

<div class="min-h-screen py-12 px-4 sm:px-6 lg:px-8 relative z-10">
    <div class="max-w-6xl mx-auto">
        
        <!-- Header Section -->
        <div class="mb-10">
            <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-6">
                <div>
                    <span class="px-4 py-1.5 text-xs font-bold tracking-widest text-purple-400 uppercase bg-purple-500/10 border border-purple-500/20 rounded-full inline-block mb-3">
                        Manajemen Halaman
                    </span>
                    <h1 class="text-3xl font-black text-white tracking-tight">
                        Kelola <span class="text-transparent bg-clip-text bg-gradient-to-r from-pink-400 to-purple-400">Halaman</span>
                    </h1>
                    <p class="text-gray-400 font-medium mt-1">{{ $tempat->nama }}</p>
                </div>
                <a href="{{ route('admin.dashboard') }}" class="group inline-flex items-center text-sm font-bold text-pink-400 hover:text-pink-300 transition-colors">
                    <svg class="w-4 h-4 mr-2 group-hover:-translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                    Kembali ke Ruangan
                </a>
            </div>
        </div>

        <!-- Form Tambah Halaman -->
        <div class="glass-card p-6 rounded-2xl border border-white/10 shadow-lg mb-8">
            <h3 class="text-sm font-bold text-white uppercase tracking-wider mb-4 flex items-center">
                <svg class="w-5 h-5 mr-2 text-pink-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                Tambah Halaman Baru
            </h3>
            
            <!-- Pesan Error/Sukses -->
            @if(session('error'))
                <div class="bg-red-500/20 border border-red-500/30 text-red-300 px-4 py-3 rounded-xl text-sm mb-4">
                    {{ session('error') }}
                </div>
            @endif
            @if(session('success'))
                <div class="bg-green-500/20 border border-green-500/30 text-green-300 px-4 py-3 rounded-xl text-sm mb-4">
                    {{ session('success') }}
                </div>
            @endif

            <form method="POST" action="{{ route('admin.pages.store') }}">
                @csrf
                <input type="hidden" name="tempat_layanan_id" value="{{ $tempat->id }}">
                
                <div class="flex flex-col md:flex-row gap-4">
                    <div class="flex-1">
                        <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Nama Halaman</label>
                        <input type="text" name="judul" placeholder="Contoh: Pengumuman, Kas Bulanan..." 
                            class="glass-input w-full px-4 py-3 rounded-xl text-white placeholder-gray-500 focus:ring-2 focus:ring-pink-500/50 transition-all" required>
                    </div>
                    
                    <div class="md:w-48">
                        <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Tipe</label>
                        <select name="tipe" class="glass-input w-full px-4 py-3 rounded-xl text-white focus:ring-2 focus:ring-pink-500/50 transition-all cursor-pointer appearance-none" required>
                            <option value="" selected disabled>-- Pilih Tipe --</option>
                            
                            {{-- LOGIKA: Disable jika tipe sudah ada di array $existingTypes --}}
                            <option value="info" class="bg-gray-900" {{ in_array('info', $existingTypes ?? []) ? 'disabled' : '' }}>
                                Info (Pengumuman) {{ in_array('info', $existingTypes ?? []) ? '(Sudah Ada)' : '' }}
                            </option>
                            
                            <option value="kas" class="bg-gray-900" {{ in_array('kas', $existingTypes ?? []) ? 'disabled' : '' }}>
                                Kas (Keuangan) {{ in_array('kas', $existingTypes ?? []) ? '(Sudah Ada)' : '' }}
                            </option>

                            <option value="barang_pinjam" class="bg-gray-900" {{ in_array('barang_pinjam', $existingTypes ?? []) ? 'disabled' : '' }}>
                                Barang Pinjam {{ in_array('barang_pinjam', $existingTypes ?? []) ? '(Sudah Ada)' : '' }}
                            </option>
                        </select>
                    </div>

                    <div class="md:w-auto flex items-end">
                        <button type="submit" class="w-full md:w-auto px-6 py-3 bg-gradient-to-r from-pink-500 to-purple-600 text-white rounded-xl font-bold hover:shadow-lg hover:shadow-pink-500/20 hover:scale-105 transition-all">
                            Tambah
                        </button>
                    </div>
                </div>
            </form>
        </div>

        <!-- List Halaman -->
        @if($pages->count() > 0)
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($pages as $page)
                    <div class="glass-card rounded-2xl border border-white/10 shadow-lg overflow-hidden hover:-translate-y-1 transition-all duration-300 group">
                        
                        <!-- Card Header -->
                        <div class="p-6 border-b border-white/5">
                            <div class="flex items-start justify-between">
                                <div class="flex items-center gap-3">
                                    <div class="w-12 h-12 rounded-xl flex items-center justify-center 
                                        @if($page->tipe == 'info')
                                            bg-blue-500/10 border border-blue-500/20
                                        @elseif($page->tipe == 'kas')
                                            bg-emerald-500/10 border border-emerald-500/20
                                        @else
                                            bg-yellow-500/10 border border-yellow-500/20
                                        @endif
                                    ">
                                        @if($page->tipe == 'info')
                                            <svg class="w-6 h-6 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"></path></svg>
                                        @elseif($page->tipe == 'kas')
                                            <svg class="w-6 h-6 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                        @else
                                            <svg class="w-6 h-6 text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path></svg>
                                        @endif
                                    </div>
                                    <div>
                                        <h3 class="text-lg font-bold text-white">{{ $page->judul }}</h3>
                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-bold 
                                            @if($page->tipe == 'info')
                                                bg-blue-500/10 text-blue-400 border border-blue-500/20
                                            @elseif($page->tipe == 'kas')
                                                bg-emerald-500/10 text-emerald-400 border border-emerald-500/20
                                            @else
                                                bg-yellow-500/10 text-yellow-400 border border-yellow-500/20
                                            @endif
                                        ">
                                            @if($page->tipe == 'info')
                                                Info
                                            @elseif($page->tipe == 'kas')
                                                Kas
                                            @else
                                                Barang
                                            @endif
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Card Body - Preview -->
                        <div class="p-6 min-h-[80px]">
                            @if($page->tipe == 'info')
                                <p class="text-sm text-gray-400 line-clamp-2">
                                    {{ Str::limit(strip_tags($page->content), 100) ?: 'Belum ada konten...' }}
                                </p>
                            @elseif($page->tipe == 'kas')
                                <div class="flex items-center text-emerald-400">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg>
                                    <span class="text-sm font-medium">Laporan Keuangan</span>
                                </div>
                            @else
                                <p class="text-sm text-gray-500 italic">Tidak ada konten</p>
                            @endif
                        </div>

                        <!-- Card Footer - Actions -->
                        <div class="px-6 py-4 bg-white/5 border-t border-white/5 flex items-center justify-between">
                            <div class="flex items-center gap-2">
                                @if($page->tipe == 'info')
                                    <a href="{{ route('admin.posts.index', $page->id) }}" 
                                        class="px-3 py-1.5 bg-blue-500/10 text-blue-400 rounded-lg text-xs font-bold hover:bg-blue-500 hover:text-white transition-all border border-blue-500/20 hover:border-blue-500">
                                        Kelola Berita
                                    </a>
                                @endif

                                @if($page->tipe == 'kas')
                                    <a href="{{ route('kas.dashboard', $page->tempatLayanan->slug) }}" 
                                        class="px-3 py-1.5 bg-emerald-500/10 text-emerald-400 rounded-lg text-xs font-bold hover:bg-emerald-500 hover:text-white transition-all border border-emerald-500/20 hover:border-emerald-500">
                                        Buka Kas
                                    </a>
                                @endif
                            </div>

                            <form id="delete-page-form-{{ $page->id }}" method="POST" action="{{ route('admin.pages.destroy', $page->id) }}" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="button" onclick="confirmDeletePage('{{ $page->id }}', '{{ $page->judul }}')" 
                                    class="p-2 text-gray-500 hover:text-red-400 hover:bg-red-500/10 rounded-lg transition-all" title="Hapus Halaman">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                    </svg>
                                </button>
                            </form>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <!-- Empty State -->
            <div class="glass-card rounded-[2.5rem] p-16 text-center border border-white/10">
                <div class="w-24 h-24 bg-purple-500/10 rounded-full flex items-center justify-center mx-auto mb-6 shadow-inner border border-purple-500/20">
                    <svg class="w-10 h-10 text-purple-400/50" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                </div>
                <h3 class="text-xl font-bold text-white mb-2">Belum Ada Halaman</h3>
                <p class="text-gray-500 max-w-md mx-auto">Buat halaman pertama untuk memulai. Anda bisa menambahkan halaman Info, Kas, atau Barang Pinjam.</p>
            </div>
        @endif
    </div>
</div>
<script>
function confirmDeletePage(pageId, pageTitle) {
    Swal.fire({
        title: '<span class="text-white">Hapus Halaman?</span>',
        html: `<span class="text-gray-400">Apakah Anda yakin ingin menghapus halaman <b>${pageTitle}</b>?<br>Data yang sudah dihapus tidak bisa dikembalikan.</span>`,
        icon: 'error', // Ikon silang merah untuk aksi berbahaya
        iconColor: '#ef4444', // Red-500
        showCancelButton: true,
        confirmButtonColor: '#ef4444', // Red-500
        cancelButtonColor: 'rgba(255,255,255,0.1)',
        confirmButtonText: 'Ya, Hapus!',
        cancelButtonText: 'Batal',
        background: '#111827', 
        color: '#ffffff',
        borderRadius: '1.5rem',
        backdrop: `rgba(0,0,0,0.6) backdrop-blur-sm`,
        customClass: {
            popup: 'border border-white/10 glass-card shadow-2xl',
            confirmButton: 'rounded-xl px-6 py-2 font-bold uppercase text-xs tracking-widest',
            cancelButton: 'rounded-xl px-6 py-2 font-bold uppercase text-xs tracking-widest text-gray-300'
        }
    }).then((result) => {
        if (result.isConfirmed) {
            // Submit form spesifik berdasarkan ID halaman
            document.getElementById('delete-page-form-' + pageId).submit();
        }
    });
}
</script>
@endsection