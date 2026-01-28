@extends('layouts.app')

@section('content')
<!-- Import Chart.js Library -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<div class="bg-app-theme min-h-screen py-8 px-4">
    <div class="max-w-7xl mx-auto">
        
        <!-- Header -->
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8 gap-4">
            <div>
                <a href="{{ route('pages.index', $tempat->id) }}" class="text-pink-600 hover:underline font-bold">&larr; Kembali ke Room</a>
                <h1 class="text-3xl font-black text-gray-800 mt-2">Kas & Keuangan: {{ $tempat->nama }}</h1>
            </div>
            
            <!-- Config Flags Indicator -->
            <div class="flex gap-2">
                @if($tempat->use_individual_ledger)
                    <span class="px-3 py-1 bg-blue-100 text-blue-700 text-xs font-bold rounded-full border border-blue-200">Mode Perorangan</span>
                @else
                    <span class="px-3 py-1 bg-gray-100 text-gray-600 text-xs font-bold rounded-full border border-gray-200">Kas Umum</span>
                @endif
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            
            <!-- Kolom Kiri: Form Transaksi (HANYA ADMIN) -->
            @if(auth()->id() == $tempat->user_id)
                <div class="lg:col-span-1">
                    <div class="glass-card p-6 sticky top-6">
                        <h2 class="text-lg font-bold mb-4 text-gray-700 border-b pb-2">Catat Transaksi</h2>
                        
                        <form action="{{ route('kas.store', $tempat->id) }}" method="POST" enctype="multipart/form-data">
                            @csrf

                            <!-- Toggle Jenis -->
                            <div class="flex mb-6 bg-gray-100 p-1 rounded-xl">
                                <label class="flex-1 text-center cursor-pointer">
                                    <input type="radio" name="jenis" value="masuk" class="peer hidden" checked onchange="toggleForm()">
                                    <div class="py-2 text-sm font-bold rounded-lg text-gray-500 peer-checked:bg-green-500 peer-checked:text-white transition">
                                        PEMASUKAN
                                    </div>
                                </label>
                                <label class="flex-1 text-center cursor-pointer">
                                    <input type="radio" name="jenis" value="keluar" class="peer hidden" onchange="toggleForm()">
                                    <div class="py-2 text-sm font-bold rounded-lg text-gray-500 peer-checked:bg-red-500 peer-checked:text-white transition">
                                        PENGELUARAN
                                    </div>
                                </label>
                            </div>

                            <div class="space-y-4">
                                <div>
                                    <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Tanggal</label>
                                    <input type="date" name="tanggal" class="glass-input" required value="{{ date('Y-m-d') }}">
                                </div>

                                <div>
                                    <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Keterangan</label>
                                    <input type="text" name="keterangan" class="glass-input" placeholder="Contoh: Beli Spidol, Setor Kas" required>
                                </div>

                                <div>
                                    <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Jumlah (Rp)</label>
                                    <input type="number" name="jumlah" class="glass-input" placeholder="0" required>
                                </div>

                                <!-- LOGIKA PEMASUKAN -->
                                <div id="section-pemasukan" class="space-y-4 pt-2 border-t border-dashed border-gray-200">
                                    
                                    @if($tempat->use_individual_ledger)
                                        <div class="space-y-2">
                                            <label class="block text-xs font-bold text-gray-500 uppercase">Sumber Dana</label>
                                            
                                            <label class="flex items-center p-3 border rounded-lg cursor-pointer hover:bg-gray-50">
                                                <input type="radio" name="is_personal" value="0" class="mr-3 text-pink-600 focus:ring-pink-500" checked onchange="togglePersonalInput()">
                                                <div>
                                                    <span class="block font-bold text-gray-800 text-sm">Uang Bebas (Umum)</span>
                                                    <p class="text-xs text-gray-500">Hanya tambah saldo fisik.</p>
                                                </div>
                                            </label>

                                            <label class="flex items-center p-3 border rounded-lg cursor-pointer hover:bg-gray-50">
                                                <input type="radio" name="is_personal" value="1" class="mr-3 text-pink-600 focus:ring-pink-500" onchange="togglePersonalInput()">
                                                <div class="w-full">
                                                    <span class="block font-bold text-gray-800 text-sm">Setor Anggota</span>
                                                    <p class="text-xs text-gray-500">Saldo user bertambah.</p>
                                                    
                                                    <div id="personal-inputs" class="hidden mt-3 space-y-2">
                                                        <label class="block text-xs font-bold text-gray-500 uppercase">Nama Anggota</label>
                                                        <select name="user_id" class="glass-input text-sm">
                                                            <option value="">-- Pilih Anggota --</option>
                                                            <!-- Menggunakan 'users' sesuai controller sebelumnya -->
                                                            @foreach($tempat->users as $u)
                                                                @if($u->id != $tempat->user_id)
                                                                    <option value="{{ $u->id }}">{{ $u->name }}</option>
                                                                @endif
                                                            @endforeach
                                                        </select>

                                                        <label class="block text-xs font-bold text-gray-500 uppercase">Kategori Kas</label>
                                                        <select name="kategori_kas_id" class="glass-input text-sm">
                                                            <option value="">-- Pilih Kategori --</option>
                                                            @foreach($kategoriKas as $kat)
                                                                <option value="{{ $kat->id }}">{{ $kat->nama }} {{ $kat->isWajib() ? '(Wajib)' : '(Sedekah)' }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>
                                            </label>
                                        </div>

                                    @else
                                        <div class="p-3 bg-blue-50 text-blue-800 text-xs rounded border border-blue-100">
                                            Mode <strong>Kas Umum</strong>. Semua pemasukan dianggap Uang Bebas.
                                        </div>
                                    @endif
                                </div>

                                <!-- LOGIKA PENGELUARAN -->
                                <div id="section-pengeluaran" class="hidden space-y-4 pt-2 border-t border-dashed border-gray-200">
                                    <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Mode Pengeluaran</label>
                                    <div class="space-y-2">
                                        @if($tempat->shared_expense_enabled)
                                            <label class="flex items-start p-3 border rounded-lg cursor-pointer hover:bg-gray-50">
                                                <input type="radio" name="tipe_pengeluaran" value="shared" class="mt-1 mr-3 text-pink-600 focus:ring-pink-500" checked>
                                                <div>
                                                    <span class="block font-bold text-gray-800 text-sm">Dibagi Rata (Shared)</span>
                                                    <p class="text-xs text-gray-500">Biaya dibagi ke semua anggota.</p>
                                                </div>
                                            </label>
                                        @endif
                                        
                                        @if($tempat->free_expense_enabled)
                                            <label class="flex items-start p-3 border rounded-lg cursor-pointer hover:bg-gray-50">
                                                <input type="radio" name="tipe_pengeluaran" value="free" class="mt-1 mr-3 text-pink-600 focus:ring-pink-500" {{ !$tempat->shared_expense_enabled ? 'checked' : '' }}>
                                                <div>
                                                    <span class="block font-bold text-gray-800 text-sm">Ambil Saldo Total (Free)</span>
                                                    <p class="text-xs text-gray-500">Hanya kurangi uang fisik.</p>
                                                </div>
                                            </label>
                                        @endif
                                    </div>
                                </div>

                                <div>
                                    <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Bukti Foto (Nota)</label>
                                    <input type="file" name="bukti_foto" class="w-full text-sm text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-pink-50 file:text-pink-700 hover:file:bg-pink-100">
                                </div>

                                <button type="submit" class="w-full btn-gradient-pink py-3 rounded-xl font-bold shadow-lg hover:scale-[1.02] transition">
                                    Simpan Transaksi
                                </button>
                            </div>
                        </form>

                        <!-- FITUR KELOLA KATEGORI -->
                        @if($tempat->use_individual_ledger)
                            <div class="mt-6 pt-4 border-t border-gray-200">
                                <div class="flex justify-between items-center mb-2">
                                    <h4 class="text-sm font-bold text-gray-600">Kelola Kategori</h4>
                                </div>

                                <div class="mb-3 space-y-1">
                                    @forelse($kategoriKas as $k)
                                        <div class="flex justify-between items-center text-xs p-2 bg-gray-50 rounded border border-gray-100">
                                            <span class="font-bold text-gray-700">{{ $k->nama }}</span>
                                            <span class="px-2 py-0.5 rounded text-[10px] font-bold uppercase {{ $k->tipe == 'wajib' ? 'bg-pink-100 text-pink-600' : 'bg-green-100 text-green-600' }}">
                                                {{ $k->tipe }}
                                            </span>
                                        </div>
                                    @empty
                                        <div class="text-xs text-center text-gray-400 py-2 bg-yellow-50 rounded border border-dashed border-yellow-200">
                                            Belum ada kategori.
                                        </div>
                                    @endforelse
                                </div>

                                <form action="{{ route('kas.kategori.store', $tempat->id) }}" method="POST" class="space-y-2">
                                    @csrf
                                    <input type="text" name="nama" placeholder="Nama Kategori baru..." class="glass-input text-sm" required>
                                    <div class="flex gap-2">
                                        <select name="tipe" class="w-1/2 glass-input text-sm">
                                            <option value="sedekah">Sedekah</option>
                                            <option value="wajib">Wajib</option>
                                        </select>
                                        <button type="submit" class="w-1/2 bg-gray-800 hover:bg-black text-white text-sm font-bold rounded-lg transition">
                                            + Tambah
                                        </button>
                                    </div>
                                </form>
                            </div>
                        @endif

                    </div>
                </div>
            @else
                <div class="lg:col-span-1">
                    <div class="glass-card p-6 text-center border-dashed border-2 border-gray-200">
                        <div class="w-12 h-12 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-3">
                            <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                        </div>
                        <p class="text-gray-500 font-bold text-sm">Mode Read-Only</p>
                        <p class="text-gray-400 text-xs mt-1">Hanya Admin yang dapat mencatat transaksi.</p>
                    </div>
                </div>
            @endif

            <!-- Kolom Kanan: Stats, Chart & Table -->
            <div class="@if(auth()->id() == $tempat->user_id) lg:col-span-2 @else lg:col-span-3 @endif">
                
                <!-- SECTION STATISTIK -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                    
                    <!-- CARD 1: SALDO TOTAL -->
                    <div class="glass-card p-6 border-l-4 border-l-blue-500">
                        <h4 class="text-gray-500 font-bold uppercase text-xs tracking-wider mb-1">Saldo Total Fisik</h4>
                        <div class="flex items-end justify-between">
                            <span id="display-total-balance" class="text-3xl font-black text-gray-800">
                                Rp {{ number_format($totalBalance, 0, ',', '.') }}
                            </span>
                            <div class="w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center text-blue-600">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            </div>
                        </div>
                    </div>

                    <!-- CARD 2: SALDO PRIBADI (Hanya jika Ledger Aktif) -->
                    @if($tempat->use_individual_ledger)
                        <div class="glass-card p-6 border-l-4 border-l-pink-500">
                            <h4 class="text-gray-500 font-bold uppercase text-xs tracking-wider mb-1">Saldo Pribadi Saya</h4>
                            <div class="flex items-end justify-between">
                                <span id="display-personal-balance" class="text-3xl font-black text-gray-800">
                                    Rp {{ number_format($personalBalance, 0, ',', '.') }}
                                </span>
                                <div class="w-10 h-10 bg-pink-100 rounded-full flex items-center justify-center text-pink-600">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                                </div>
                            </div>
                            @if($personalBalance < 0)
                                <p class="text-xs text-orange-500 font-bold mt-2">ℹ️ Tunggakan kas wajib.</p>
                            @endif
                        </div>
                    @endif

                </div>

                <!-- GRAFIK CHART JS -->
                @if($chartData && $chartData->count() > 0)
                    <div class="glass-card p-6 mb-8">
                        <h3 class="font-bold text-gray-700 mb-4 border-b pb-2">Grafik Arus Kas (6 Bulan Terakhir)</h3>
                        
                        <!-- FIX CHART USER: Batasi max-width agar tidak terlalu lebar di layar User -->
                        <!-- Gunakan mx-auto untuk centering -->
                        <div class="relative h-72 w-full max-w-full lg:max-w-4xl mx-auto">
                            <canvas id="kasChart"></canvas>
                        </div>
                    </div>
                @endif

                <!-- TABEL RIWAYAT -->
                <div class="glass-card min-h-[500px]">
                    <div class="p-6 border-b flex justify-between items-center bg-white/50">
                        <h3 class="font-bold text-gray-700">Riwayat Transaksi</h3>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="min-w-full">
                            <thead class="bg-gray-50 text-xs font-bold text-gray-500 uppercase">
                                <tr>
                                    <th class="px-6 py-3 text-left">Tanggal</th>
                                    <th class="px-6 py-3 text-left">Keterangan</th>
                                    <th class="px-6 py-3 text-left">Tipe</th>
                                    <th class="px-6 py-3 text-right">Jumlah</th>
                                    <th class="px-6 py-3 text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100">
                                @forelse($laporans as $lap)
                                    <tr class="hover:bg-pink-50/30 transition">
                                        <td class="px-6 py-4 text-sm text-gray-600">{{ $lap->tanggal->format('d M Y') }}</td>
                                        <td class="px-6 py-4">
                                            <div class="font-bold text-gray-800">{{ $lap->keterangan }}</div>
                                            @if($lap->kategori)
                                                <div class="text-xs text-pink-500 bg-pink-100 inline-block px-2 py-0.5 rounded mt-1">{{ $lap->kategori->nama }}</div>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4">
                                            @if($lap->jenis === 'masuk')
                                                <span class="px-2 py-1 bg-green-100 text-green-700 text-xs font-bold rounded">MASUK</span>
                                            @else
                                                <span class="px-2 py-1 bg-red-100 text-red-700 text-xs font-bold rounded">KELUAR</span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 text-right font-bold font-mono">
                                            {{ number_format($lap->jumlah, 0, ',', '.') }}
                                        </td>
                                        <td class="px-6 py-4 text-center">
                                            <div class="flex items-center justify-center gap-2">
                                                @if($lap->bukti_foto)
                                                    <a href="{{ asset('storage/'.$lap->bukti_foto) }}" target="_blank" class="text-blue-500 hover:text-blue-700 text-xs p-1 border rounded hover:bg-blue-50 transition">
                                                        Bukti
                                                    </a>
                                                @else
                                                    <span class="text-gray-300 text-xs">-</span>
                                                @endif
                                                
                                                <!-- TOMBOL HAPUS (ADMIN ONLY) -->
                                                @if(auth()->id() == $tempat->user_id)
                                                    <button onclick="hapusTransaksi({{ $lap->id }}, this)" class="text-red-500 hover:text-red-700 hover:bg-red-50 text-xs font-bold p-1 border rounded transition-colors">
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                                    </button>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="px-6 py-10 text-center text-gray-400">Belum ada transaksi.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <!-- Pagination -->
                    {{ $laporans->links() }}
                </div>
            </div>

        </div>
    </div>
</div>

<script>
    let kasChart; // Global variable untuk update chart

    function toggleForm() {
        const jenis = document.querySelector('input[name="jenis"]:checked').value;
        const sectionMasuk = document.getElementById('section-pemasukan');
        const sectionKeluar = document.getElementById('section-pengeluaran');

        if (jenis === 'masuk') {
            sectionMasuk.classList.remove('hidden');
            sectionKeluar.classList.add('hidden');
            
            // Reset Pengeluaran
            document.querySelectorAll('#section-pengeluaran input[type="radio"]').forEach(el => el.checked = false);

        } else {
            sectionMasuk.classList.add('hidden');
            sectionKeluar.classList.remove('hidden');
            
            // Reset Pemasukan
            document.querySelector('input[name="is_personal"][value="0"]').checked = true;
            togglePersonalInput();
        }
    }

    function togglePersonalInput() {
        const isPersonal = document.querySelector('input[name="is_personal"]:checked').value;
        const inputs = document.getElementById('personal-inputs');
        
        if (isPersonal === "1") {
            inputs.classList.remove('hidden');
        } else {
            inputs.classList.add('hidden');
        }
    }

    function initChart() {
        const canvas = document.getElementById('kasChart');
        if(!canvas) return; // Guard clause

        const ctx = canvas.getContext('2d');
        
        const labels = @json($chartData->pluck('bulan'));
        const dataMasuk = @json($chartData->pluck('pemasukan'));
        const dataKeluar = @json($chartData->pluck('pengeluaran'));

        // Jika data kosong, jangan render chart
        if (!labels || labels.length === 0) return;

        kasChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: labels,
                datasets: [
                    {
                        label: 'Pemasukan',
                        data: dataMasuk,
                        backgroundColor: 'rgba(34, 197, 94, 0.7)', // Green
                        borderColor: 'rgba(34, 197, 94, 1)',
                        borderWidth: 1,
                        borderRadius: 4,
                    },
                    {
                        label: 'Pengeluaran',
                        data: dataKeluar,
                        backgroundColor: 'rgba(239, 68, 68, 0.7)', // Red
                        borderColor: 'rgba(239, 68, 68, 1)',
                        borderWidth: 1,
                        borderRadius: 4,
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { position: 'bottom' } },
                scales: {
                    y: { 
                        beginAtZero: true, 
                        grid: { borderDash: [2, 4], color: '#f3f4f6' } 
                    },
                    x: { grid: { display: false } }
                }
            }
        });
    }

    async function hapusTransaksi(id, btnElement) {
        if(!confirm('Yakin ingin menghapus transaksi ini?')) return;

        const originalContent = btnElement.innerHTML;
        btnElement.disabled = true;
        btnElement.innerHTML = `<svg class="animate-spin w-4 h-4" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>`;

        try {
            // URL dinamis
            const url = "{{ url('/kas/destroy') }}" + '/' + id;

            const response = await fetch(url, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json'
                }
            });

            const result = await response.json();

            if (response.ok && result.success) {
                // Reload halaman untuk update data sempurna
                window.location.reload(); 
            } else {
                throw new Error(result.message || 'Gagal menghapus');
            }
        } catch (error) {
            alert('Gagal: ' + error.message);
            btnElement.disabled = false;
            btnElement.innerHTML = originalContent;
        }
    }

    // Initialize
    window.addEventListener('DOMContentLoaded', () => {
        if(document.querySelector('input[name="jenis"]')) {
            toggleForm();
            togglePersonalInput();
        }
        initChart();
    });
</script>
@endsection