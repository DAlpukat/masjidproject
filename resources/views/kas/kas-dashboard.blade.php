@extends('layouts.app')

@section('content')
<!-- Import Chart.js Library -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<!-- TAMBAHAN: Library Choices.js -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/choices.js@9.0.1/public/assets/styles/choices.min.css"/>
<script src="https://cdn.jsdelivr.net/npm/choices.js@9.0.1/public/assets/scripts/choices.min.js"></script>

<div class="bg-app-theme min-h-screen py-8 px-4">
    <div class="max-w-7xl mx-auto">
        
        <!-- Header -->
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8 gap-4">
            <div>
                {{-- Gunakan ID untuk route admin karena route admin menerima ID --}}
                <a href="{{ route('admin.pages.index', $tempat->id) }}" class="text-pink-400 hover:text-pink-300 hover:underline font-bold">&larr; Kembali ke Daftar Halaman</a>
                <h1 class="text-3xl font-black text-white mt-2 drop-shadow-lg">Kas & Keuangan: {{ $tempat->nama }}</h1>
            </div>
            
            <!-- Config Flags Indicator -->
            <div class="flex gap-2">
                @if($tempat->use_individual_ledger)
                    <span class="px-3 py-1 bg-blue-500/20 text-blue-300 text-xs font-bold rounded-full border border-blue-400/30 backdrop-blur-sm">Mode Perorangan</span>
                @else
                    <span class="px-3 py-1 bg-gray-500/20 text-gray-300 text-xs font-bold rounded-full border border-gray-400/30 backdrop-blur-sm">Kas Umum</span>
                @endif
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            
            <!-- Kolom Kiri: Form Transaksi (HANYA ADMIN) -->
            @if(auth()->id() == $tempat->user_id)
                <div class="lg:col-span-1">
                    <div class="glass-card p-6 sticky top-6">
                        <h2 class="text-lg font-bold mb-4 text-white border-b border-white/10 pb-2">Catat Transaksi</h2>
                        
                        {{-- PERBAIKAN: Gunakan $tempat->slug --}}
                        <form action="{{ route('kas.store', $tempat->slug) }}" method="POST" enctype="multipart/form-data">
                            @csrf

                            <!-- Toggle Jenis -->
                            <div class="flex mb-6 bg-white/5 p-1 rounded-xl border border-white/10">
                                <label class="flex-1 text-center cursor-pointer">
                                    <input type="radio" name="jenis" value="masuk" class="peer hidden" checked onchange="toggleForm()">
                                    <div class="py-2 text-sm font-bold rounded-lg text-gray-400 peer-checked:bg-green-500 peer-checked:text-white transition">
                                        PEMASUKAN
                                    </div>
                                </label>
                                <label class="flex-1 text-center cursor-pointer">
                                    <input type="radio" name="jenis" value="keluar" class="peer hidden" onchange="toggleForm()">
                                    <div class="py-2 text-sm font-bold rounded-lg text-gray-400 peer-checked:bg-red-500 peer-checked:text-white transition">
                                        PENGELUARAN
                                    </div>
                                </label>
                            </div>

                            <div class="space-y-4">
                                <div>
                                    <label class="block text-xs font-bold text-gray-300 uppercase mb-1">Tanggal</label>
                                    <input type="date" name="tanggal" class="glass-input w-full" required value="{{ date('Y-m-d') }}">
                                </div>

                                <div>
                                    <label class="block text-xs font-bold text-gray-300 uppercase mb-1">Keterangan</label>
                                    <input type="text" name="keterangan" class="glass-input w-full" placeholder="Contoh: Beli Spidol" required>
                                </div>

                                <div>
                                    <label class="block text-xs font-bold text-gray-300 uppercase mb-1">Jumlah (Rp)</label>
                                    <input type="number" name="jumlah" class="glass-input w-full" placeholder="0" required>
                                </div>

                                <!-- LOGIKA PEMASUKAN -->
                                <div id="section-pemasukan" class="space-y-4 pt-2 border-t border-white/10">
                                    
                                    @if($tempat->use_individual_ledger)
                                        <div class="space-y-2">
                                            <label class="block text-xs font-bold text-gray-300 uppercase">Sumber Dana</label>
                                            
                                            <label class="flex items-center p-3 border border-white/10 rounded-lg cursor-pointer hover:bg-white/5 transition">
                                                <input type="radio" name="is_personal" value="0" class="mr-3 text-pink-500 focus:ring-pink-400 bg-gray-700 border-gray-600" checked onchange="togglePersonalInput()">
                                                <div>
                                                    <span class="block font-bold text-white text-sm">Uang Bebas (Umum)</span>
                                                    <p class="text-xs text-gray-400">Hanya tambah saldo fisik.</p>
                                                </div>
                                            </label>

                                            <label class="flex items-center p-3 border border-white/10 rounded-lg cursor-pointer hover:bg-white/5 transition">
                                                <input type="radio" name="is_personal" value="1" class="mr-3 text-pink-500 focus:ring-pink-400 bg-gray-700 border-gray-600" onchange="togglePersonalInput()">
                                                <div class="w-full">
                                                    <span class="block font-bold text-white text-sm">Setor Anggota</span>
                                                    <p class="text-xs text-gray-400">Saldo user bertambah.</p>
                                                    
                                                    <div id="personal-inputs" class="hidden mt-3 space-y-2 pl-2 border-l-2 border-pink-500/50">
                                                        <label class="block text-xs font-bold text-gray-400 uppercase">Nama Anggota</label>
                                                        <select name="user_id" class="glass-input text-sm w-full choices-dark">
                                                            <option value="">-- Pilih Anggota --</option>
                                                            @foreach($tempat->users as $u)
                                                                @if($u->id != $tempat->user_id)
                                                                    <option value="{{ $u->id }}">{{ $u->name }}</option>
                                                                @endif
                                                            @endforeach
                                                        </select>

                                                        <label class="block text-xs font-bold text-gray-400 uppercase">Kategori Kas</label>
                                                        <select name="kategori_kas_id" class="glass-input text-sm w-full choices-dark">
                                                            <option value="">-- Pilih Kategori --</option>
                                                            @foreach($kategoriKas as $kat)
                                                                <option value="{{ $kat->id }}">{{ $kat->nama }} {{ $kat->tipe == 'wajib' ? '(Wajib)' : '(Sedekah)' }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>
                                            </label>
                                        </div>

                                    @else
                                        <div class="p-3 bg-blue-500/10 text-blue-200 text-xs rounded border border-blue-500/20">
                                            Mode <strong>Kas Umum</strong>. Semua pemasukan dianggap Uang Bebas.
                                        </div>
                                    @endif
                                </div>

                                <!-- LOGIKA PENGELUARAN -->
                                <div id="section-pengeluaran" class="hidden space-y-4 pt-2 border-t border-white/10">
                                    <label class="block text-xs font-bold text-gray-300 uppercase mb-1">Mode Pengeluaran</label>
                                    <div class="space-y-2">
                                        @if($tempat->shared_expense_enabled)
                                            <label class="flex items-start p-3 border border-white/10 rounded-lg cursor-pointer hover:bg-white/5 transition">
                                                <input type="radio" name="tipe_pengeluaran" value="shared" class="mt-1 mr-3 text-pink-500 bg-gray-700 border-gray-600" checked>
                                                <div>
                                                    <span class="block font-bold text-white text-sm">Dibagi Rata (Shared)</span>
                                                    <p class="text-xs text-gray-400">Biaya dibagi ke semua anggota.</p>
                                                </div>
                                            </label>
                                        @endif
                                        
                                        @if($tempat->free_expense_enabled)
                                            <label class="flex items-start p-3 border border-white/10 rounded-lg cursor-pointer hover:bg-white/5 transition">
                                                <input type="radio" name="tipe_pengeluaran" value="free" class="mt-1 mr-3 text-pink-500 bg-gray-700 border-gray-600" {{ !$tempat->shared_expense_enabled ? 'checked' : '' }}>
                                                <div>
                                                    <span class="block font-bold text-white text-sm">Ambil Saldo Total (Free)</span>
                                                    <p class="text-xs text-gray-400">Hanya kurangi uang fisik.</p>
                                                </div>
                                            </label>
                                        @endif
                                    </div>
                                </div>

                                <div>
                                    <label class="block text-xs font-bold text-gray-300 uppercase mb-1">Bukti Foto (Nota)</label>
                                    <input type="file" name="bukti_foto" class="w-full text-sm text-gray-300 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-pink-800 file:text-pink-100 hover:file:bg-pink-700 cursor-pointer">
                                </div>

                                <button type="submit" class="w-full btn-gradient-pink py-3 rounded-xl font-bold shadow-lg hover:scale-[1.02] transition text-white">
                                    Simpan Transaksi
                                </button>
                            </div>
                        </form>

                        <!-- FITUR KELOLA KATEGORI -->
                        @if($tempat->use_individual_ledger)
                            <div class="mt-6 pt-4 border-t border-white/10">
                                <div class="flex justify-between items-center mb-2">
                                    <h4 class="text-sm font-bold text-gray-300">Kelola Kategori</h4>
                                </div>

                                <div class="mb-3 space-y-1 max-h-40 overflow-y-auto custom-scrollbar">
                                    @forelse($kategoriKas as $k)
                                        <div class="flex justify-between items-center text-xs p-2 bg-white/5 rounded border border-white/10">
                                            <span class="font-bold text-gray-200">{{ $k->nama }}</span>
                                            <span class="px-2 py-0.5 rounded text-[10px] font-bold uppercase {{ $k->tipe == 'wajib' ? 'bg-pink-500/20 text-pink-300' : 'bg-green-500/20 text-green-300' }}">
                                                {{ $k->tipe }}
                                            </span>
                                        </div>
                                    @empty
                                        <div class="text-xs text-center text-gray-500 py-2 bg-yellow-500/10 rounded border border-dashed border-yellow-500/20">
                                            Belum ada kategori.
                                        </div>
                                    @endforelse
                                </div>

                                {{-- PERBAIKAN: Gunakan $tempat->slug --}}
                                <form action="{{ route('kas.kategori.store', $tempat->slug) }}" method="POST" class="space-y-2">
                                    @csrf
                                    <input type="text" name="nama" placeholder="Nama Kategori baru..." class="glass-input text-sm w-full" required>
                                    <div class="flex gap-2">
                                        <select name="tipe" class="w-1/2 glass-input text-sm choices-dark">
                                            <option value="sedekah">Sedekah</option>
                                            <option value="wajib">Wajib</option>
                                        </select>
                                        <button type="submit" class="w-1/2 bg-gray-700 hover:bg-gray-600 text-white text-sm font-bold rounded-lg transition border border-white/10">
                                            + Tambah
                                        </button>
                                    </div>
                                </form>
                            </div>
                        @endif

                    </div>
                </div>
            @else
                <!-- Read Only View -->
                <div class="lg:col-span-1">
                    <div class="glass-card p-6 text-center border-dashed border-2 border-white/10">
                        <div class="w-12 h-12 bg-gray-700 rounded-full flex items-center justify-center mx-auto mb-3">
                            <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                        </div>
                        <p class="text-gray-300 font-bold text-sm">Mode Read-Only</p>
                        <p class="text-gray-500 text-xs mt-1">Hanya Admin yang dapat mencatat transaksi.</p>
                    </div>
                </div>
            @endif

            <!-- Kolom Kanan: Stats, Chart & Table -->
            <div class="@if(auth()->id() == $tempat->user_id) lg:col-span-2 @else lg:col-span-3 @endif">
                
                <!-- SECTION STATISTIK -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                    <!-- CARD 1 -->
                    <div class="glass-card p-6 border-l-4 border-l-blue-500">
                        <h4 class="text-gray-400 font-bold uppercase text-xs tracking-wider mb-1">Saldo Total Fisik</h4>
                        <div class="flex items-end justify-between">
                            <span id="display-total-balance" class="text-3xl font-black text-white">
                                Rp {{ number_format($totalBalance, 0, ',', '.') }}
                            </span>
                            <div class="w-10 h-10 bg-blue-500/20 rounded-full flex items-center justify-center text-blue-400">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            </div>
                        </div>
                    </div>

                    <!-- CARD 2 -->
                    @if($tempat->use_individual_ledger)
                        <div class="glass-card p-6 border-l-4 border-l-pink-500">
                            <h4 class="text-gray-400 font-bold uppercase text-xs tracking-wider mb-1">Saldo Pribadi Saya</h4>
                            <div class="flex items-end justify-between">
                                <span id="display-personal-balance" class="text-3xl font-black text-white">
                                    Rp {{ number_format($personalBalance, 0, ',', '.') }}
                                </span>
                                <div class="w-10 h-10 bg-pink-500/20 rounded-full flex items-center justify-center text-pink-400">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                                </div>
                            </div>
                            @if($personalBalance < 0)
                                <p class="text-xs text-orange-400 font-bold mt-2">ℹ️ Tunggakan kas wajib.</p>
                            @endif
                        </div>
                    @endif
                </div>

                <!-- GRAFIK -->
                @if($chartData && $chartData->count() > 0)
                    <div class="glass-card p-6 mb-8">
                        <h3 class="font-bold text-white mb-4 border-b border-white/10 pb-2">Grafik Arus Kas</h3>
                        <div class="relative h-72 w-full">
                            <canvas id="kasChart"></canvas>
                        </div>
                    </div>
                @endif

                <!-- TABEL RIWAYAT -->
                <div class="glass-card min-h-[500px] overflow-hidden">
                    <div class="p-6 border-b border-white/10 flex justify-between items-center bg-white/5">
                        <h3 class="font-bold text-white">Riwayat Transaksi</h3>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="min-w-full">
                            <thead class="bg-white/5 text-xs font-bold text-gray-400 uppercase">
                                <tr>
                                    <th class="px-4 py-3 text-left">Tanggal</th>
                                    <th class="px-4 py-3 text-left">Keterangan</th>
                                    <th class="px-4 py-3 text-left">Kategori</th>
                                    <th class="px-4 py-3 text-left">Jenis</th>
                                    <th class="px-4 py-3 text-left">Nama</th>
                                    <th class="px-4 py-3 text-right">Jumlah</th>
                                    <th class="px-4 py-3 text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-white/5">
                                @forelse($laporans as $lap)
                                    <tr class="hover:bg-white/5 transition">
                                        <td class="px-4 py-4 text-sm text-gray-300 whitespace-nowrap">{{ $lap->tanggal->format('d M Y') }}</td>
                                        
                                        <td class="px-4 py-4">
                                            <div class="font-bold text-white">{{ $lap->keterangan }}</div>
                                        </td>

                                        <td class="px-4 py-4">
                                            @if($lap->kategori)
                                                <span class="text-xs text-pink-300 bg-pink-500/10 px-2 py-0.5 rounded border border-pink-500/20">{{ $lap->kategori->nama }}</span>
                                            @else
                                                <span class="text-gray-600 text-xs">-</span>
                                            @endif
                                        </td>

                                        <td class="px-4 py-4">
                                            @if($lap->user_id)
                                                <span class="px-2 py-1 bg-purple-500/10 text-purple-300 text-xs font-bold rounded border border-purple-500/20">Perorangan</span>
                                            @else
                                                <span class="px-2 py-1 bg-gray-500/10 text-gray-300 text-xs font-bold rounded border border-gray-500/20">Umum</span>
                                            @endif
                                        </td>

                                        <td class="px-4 py-4 text-sm text-gray-300">
                                            @if($lap->user_id)
                                                {{ $lap->user->name ?? 'User Dihapus' }}
                                            @else
                                                <span class="text-gray-500 italic">Umum</span>
                                            @endif
                                        </td>

                                        <td class="px-4 py-4 text-right font-bold font-mono whitespace-nowrap">
                                            <span class="{{ $lap->jenis == 'masuk' ? 'text-green-400' : 'text-red-400' }}">
                                                {{ $lap->jenis == 'masuk' ? '+' : '-' }} 
                                                Rp {{ number_format($lap->jumlah, 0, ',', '.') }}
                                            </span>
                                        </td>

                                        <td class="px-4 py-4 text-center">
                                            <div class="flex items-center justify-center gap-2">
                                                @if($lap->bukti_foto)
                                                    <button type="button" onclick="openPhotoModal('{{ asset('storage/'.$lap->bukti_foto) }}')" class="text-blue-400 hover:text-white hover:bg-blue-500/50 border border-blue-500/30 text-xs font-bold p-1.5 rounded transition-all">
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                                    </button>
                                                @endif
                                                
                                                @if(auth()->id() == $tempat->user_id)
                                                    <button onclick="hapusTransaksi({{ $lap->id }}, this)" class="text-red-400 hover:text-white hover:bg-red-500/50 border border-red-500/30 text-xs font-bold p-1.5 rounded transition-colors">
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                                    </button>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="px-6 py-10 text-center text-gray-500">Belum ada transaksi.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <div class="p-4 border-t border-white/10">
                        {{ $laporans->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- MODAL POPUP FOTO -->
<div id="photoModal" class="fixed z-50 inset-0 overflow-y-auto hidden" aria-labelledby="modal-title" role="dialog" aria-modal="true">
  <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
    <div class="fixed inset-0 bg-black/80 backdrop-blur-sm transition-opacity" onclick="closePhotoModal()"></div>
    <span class="hidden sm:inline-block sm:align-middle sm:h-screen">&#8203;</span>
    <div class="inline-block align-bottom bg-gray-900 rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-xl sm:w-full border border-white/10">
        <div class="bg-gray-900 p-2">
            <img id="modalImageContent" src="" alt="Bukti Transaksi" class="w-full h-auto max-h-[80vh] object-contain rounded">
        </div>
        <div class="bg-gray-800 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse border-t border-white/10">
            <button type="button" onclick="closePhotoModal()" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-600 shadow-sm px-4 py-2 bg-gray-700 text-base font-medium text-white hover:bg-gray-600 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                Tutup
            </button>
        </div>
  </div>
</div>

<!-- CSS Dark Mode Override -->
<style>
    /* Styling Choices.js agar Dark Mode */
    .choices__inner {
        background-color: rgba(255, 255, 255, 0.05) !important;
        border: 1px solid rgba(255, 255, 255, 0.15) !important;
        color: #fff !important;
        padding: 0.5rem;
        min-height: 42px;
    }
    .choices__list--single { padding: 4px 16px 4px 4px; }
    .choices__list--single .choices__item { color: #fff !important; }
    .choices__list--dropdown {
        background-color: #1f2937 !important;
        border: 1px solid rgba(255, 255, 255, 0.15) !important;
        color: #fff !important;
    }
    .choices__list--dropdown .choices__item { color: #d1d5db !important; padding: 10px; }
    .choices__list--dropdown .choices__item--selectable.is-highlighted {
        background-color: rgba(255, 255, 255, 0.1) !important;
        color: #fff !important;
    }
    .choices[data-type*="select-one"] .choices__input {
        background-color: #374151 !important;
        border: 1px solid rgba(255, 255, 255, 0.1) !important;
        color: #fff !important;
        padding: 0.5rem;
        margin: 0.5rem;
        width: calc(100% - 1rem);
        border-radius: 0.25rem;
    }
    .choices__input::placeholder { color: #9ca3af !important; }
    .choices::after { border-color: #ffffff transparent transparent !important; }
    .choices.is-open::after { border-color: transparent transparent #ffffff !important; }
    
    /* Pagination Dark Mode */
    .pagination span, .pagination a {
        background-color: rgba(255, 255, 255, 0.05) !important;
        color: #d1d5db !important;
        border: 1px solid rgba(255, 255, 255, 0.1) !important;
    }
    .pagination a:hover {
        background-color: rgba(255, 255, 255, 0.15) !important;
        color: #fff !important;
    }
    .pagination .active span {
        background-color: #ec4899 !important;
        color: #fff !important;
        border-color: #ec4899 !important;
    }
</style>

<script>
    // --- LOGIC HELPERS ---
    function toggleForm() {
        const sectionMasuk = document.getElementById('section-pemasukan');
        const sectionKeluar = document.getElementById('section-pengeluaran');
        const jenisInput = document.querySelector('input[name="jenis"]:checked');
        if (!sectionMasuk || !sectionKeluar || !jenisInput) return;

        if (jenisInput.value === 'masuk') {
            sectionMasuk.classList.remove('hidden');
            sectionKeluar.classList.add('hidden');
        } else {
            sectionMasuk.classList.add('hidden');
            sectionKeluar.classList.remove('hidden');
        }
    }

    function togglePersonalInput() {
        const isPersonalInput = document.querySelector('input[name="is_personal"]:checked');
        const personalInputsDiv = document.getElementById('personal-inputs');
        if (!isPersonalInput || !personalInputsDiv) return;

        if (isPersonalInput.value === "1") {
            personalInputsDiv.classList.remove('hidden');
        } else {
            personalInputsDiv.classList.add('hidden');
        }
    }

    // --- CHART LOGIC ---
    function initChart() {
        const canvas = document.getElementById('kasChart');
        if(!canvas) return;
        const ctx = canvas.getContext('2d');
        try {
            const rawData = @json($chartData); 
            let labels = rawData.map(item => item.bulan).reverse();
            let dataMasuk = rawData.map(item => parseFloat(item.pemasukan)).reverse();
            let dataKeluar = rawData.map(item => parseFloat(item.pengeluaran)).reverse();
            if (window.kasChartInstance) window.kasChartInstance.destroy();
            window.kasChartInstance = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: labels,
                    datasets: [
                        {
                            label: 'Pemasukan',
                            data: dataMasuk,
                            backgroundColor: 'rgba(34, 197, 94, 0.5)', 
                            borderColor: 'rgba(34, 197, 94, 1)',
                            borderWidth: 1,
                            borderRadius: 4,
                        },
                        {
                            label: 'Pengeluaran',
                            data: dataKeluar,
                            backgroundColor: 'rgba(239, 68, 68, 0.5)', 
                            borderColor: 'rgba(239, 68, 68, 1)',
                            borderWidth: 1,
                            borderRadius: 4,
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: { 
                        legend: { 
                            position: 'bottom',
                            labels: { color: '#e5e7eb' } 
                        } 
                    },
                    scales: {
                        y: { 
                            beginAtZero: true, 
                            grid: { color: 'rgba(255, 255, 255, 0.05)' },
                            ticks: { color: '#9ca3af' }
                        },
                        x: { 
                            grid: { display: false },
                            ticks: { color: '#9ca3af' }
                        }
                    }
                }
            });
        } catch (e) { console.error(e); }
    }

    // --- DELETE LOGIC ---
    async function hapusTransaksi(id, btnElement) {
        if(!confirm('Yakin ingin menghapus transaksi ini?')) return;
        const originalContent = btnElement.innerHTML;
        btnElement.disabled = true;
        btnElement.innerHTML = `<svg class="animate-spin w-4 h-4 text-red-400" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>`;
        try {
            const response = await fetch(`{{ url('/kas/destroy') }}/${id}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json'
                }
            });
            const result = await response.json();
            if (response.ok && result.success) window.location.reload(); 
            else throw new Error(result.message || 'Gagal menghapus');
        } catch (error) {
            alert('Gagal: ' + error.message);
            btnElement.disabled = false;
            btnElement.innerHTML = originalContent;
        }
    }

    // --- MODAL LOGIC ---
    function openPhotoModal(url) {
        document.getElementById('modalImageContent').src = url;
        document.getElementById('photoModal').classList.remove('hidden');
    }

    function closePhotoModal() {
        document.getElementById('photoModal').classList.add('hidden');
        document.getElementById('modalImageContent').src = '';
    }

    // --- INIT CHOICES.JS ---
    function initChoices() {
        const choiceElements = document.querySelectorAll('.choices-dark');
        choiceElements.forEach(el => {
            if (!el.classList.contains('choices__input')) {
                new Choices(el, {
                    searchEnabled: true,
                    itemSelectText: '',
                    shouldSort: false,
                    allowHTML: true,
                    classNames: {
                        containerOuter: 'choices',
                        containerInner: 'choices__inner',
                        input: 'choices__input',
                        listDropdown: 'choices__list--dropdown',
                        item: 'choices__item',
                    }
                });
            }
        });
    }

    // --- MAIN INIT ---
    window.addEventListener('DOMContentLoaded', () => {
        toggleForm();
        togglePersonalInput();
        initChart();
        initChoices();
    });
</script>
@endsection