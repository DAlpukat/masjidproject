@extends('layouts.app')

@section('content')
<div class="bg-mesh-elegant min-h-screen py-8 px-4 pb-24">
    <div class="max-w-7xl mx-auto space-y-8">
        
        <!-- Header -->
        <div class="flex flex-col gap-4">
            <a href="{{ route('room.view', $tempat->slug) }}" class="inline-flex items-center text-sm font-bold text-pink-500 hover:text-pink-700 transition-colors w-max group">
                <svg class="w-4 h-4 mr-2 group-hover:-translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                Kembali ke Room
            </a>
            
            <div class="glass-card p-6 rounded-3xl border-white/50 shadow-sm">
                <h1 class="text-3xl md:text-4xl font-black text-slate-800">
                    {{ $tempat->nama }} 
                    <span class="text-transparent bg-clip-text bg-gradient-to-r from-pink-500 to-purple-600">Laporan Kas</span>
                </h1>
            </div>
        </div>

        <!-- Stats Cards Grid -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            
            <!-- CARD 1: SALDO TOTAL RUANGAN -->
            <div class="glass-card bg-gradient-to-br from-blue-600 to-blue-700 p-6 rounded-3xl text-white shadow-blue-500/30 relative overflow-hidden group">
                <div class="absolute -right-6 -top-6 w-32 h-32 bg-white/10 rounded-full blur-2xl group-hover:scale-110 transition-transform duration-700"></div>
                
                <div class="relative z-10">
                    <h3 class="text-sm font-medium opacity-90 uppercase tracking-wider mb-1 text-blue-100">Saldo Ruangan</h3>
                    <p class="text-xs opacity-60 mb-4 text-blue-100">Total Dana Tersedia</p>
                    <p id="saldo-total" class="text-3xl font-bold tracking-tight text-white">
                        Rp {{ number_format($saldoTotal, 0, ',', '.') }}
                    </p>
                </div>
            </div>

            <!-- CARD 2: SALDO PRIBADI -->
            <div class="glass-card bg-gradient-to-br from-emerald-500 to-teal-600 p-6 rounded-3xl text-white shadow-emerald-500/30 relative overflow-hidden group">
                <div class="absolute -right-6 -top-6 w-32 h-32 bg-white/10 rounded-full blur-2xl group-hover:scale-110 transition-transform duration-700"></div>
                
                <div class="relative z-10">
                    <h3 class="text-sm font-medium opacity-90 uppercase tracking-wider mb-1 text-emerald-100">Saldo Saya</h3>
                    <p class="text-xs opacity-60 mb-4 text-emerald-100">Estimasi Kas Pribadi</p>
                    
                    <p id="saldo-pribadi" class="text-3xl font-bold tracking-tight mb-2 text-white">
                        {{ number_format($saldoPribadi, 0, ',', '.') }}
                    </p>
                    
                    <div id="status-box" class="inline-flex items-center px-3 py-1 text-xs font-bold rounded-full bg-white/20 backdrop-blur-md border border-white/30 text-white">
                        @if($saldoPribadi < 0)
                            <span class="mr-1">⚠️</span> {{ number_format(abs($saldoPribadi), 0, ',', '.') }} Minus
                        @else
                            <span class="mr-1">✅</span> {{ $statusSaldo }}
                        @endif
                    </div>
                </div>
            </div>

            <!-- CARD 3: RINGKASAN ARUS KAS (PERBAIKAN: TEXT LEBIH GELAP) -->
            <div class="glass-card p-6 rounded-3xl bg-white/80 border-white/60 flex flex-col justify-center space-y-5">
                <div>
                    <!-- DIGANTI: text-slate-400 -> text-slate-600 -->
                    <p class="text-xs font-bold text-slate-600 uppercase tracking-wider mb-1">Total Pemasukan</p>
                    <div class="flex items-center">
                        <span class="w-2 h-2 rounded-full bg-emerald-500 mr-2 shadow-sm shadow-emerald-200"></span>
                        <p id="total-pemasukan" class="text-lg font-bold text-emerald-600">Rp {{ number_format($totalMasuk ?? 0, 0, ',', '.') }}</p>
                    </div>
                </div>
                <div class="w-full h-px bg-slate-100"></div>
                <div>
                    <!-- DIGANTI: text-slate-400 -> text-slate-600 -->
                    <p class="text-xs font-bold text-slate-600 uppercase tracking-wider mb-1">Total Pengeluaran</p>
                    <div class="flex items-center">
                        <span class="w-2 h-2 rounded-full bg-red-500 mr-2 shadow-sm shadow-red-200"></span>
                        <p id="total-pengeluaran" class="text-lg font-bold text-red-500">Rp {{ number_format($totalKeluar ?? 0, 0, ',', '.') }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Charts Section -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- Chart 1 -->
            <div class="glass-card p-6 rounded-3xl border-white/50 shadow-sm">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-lg font-bold text-slate-800 flex items-center">
                        <span class="w-1.5 h-6 bg-gradient-to-b from-pink-500 to-purple-600 rounded-full mr-3 shadow-lg shadow-pink-500/30"></span>
                        Arus Kas Bulanan
                    </h3>
                </div>
                <div class="relative h-64 w-full">
                    <canvas id="barChart"></canvas>
                </div>
            </div>
            <!-- Chart 2 -->
            <div class="glass-card p-6 rounded-3xl border-white/50 shadow-sm">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-lg font-bold text-slate-800 flex items-center">
                        <span class="w-1.5 h-6 bg-gradient-to-b from-purple-500 to-indigo-600 rounded-full mr-3 shadow-lg shadow-purple-500/30"></span>
                        Komposisi Keuangan
                    </h3>
                </div>
                <div class="relative h-64 w-full flex justify-center">
                    <canvas id="pieChart"></canvas>
                </div>
            </div>
        </div>

        <!-- Filter & Tabel Section (PERBAIKAN: TEXT FILTER LEBIH GELAP) -->
        <div class="glass-card p-6 md:p-8 rounded-3xl shadow-xl border-white/50">
            
            <!-- Section Header -->
            <div class="flex flex-col md:flex-row justify-between items-center mb-8 gap-4">
                <h3 class="text-xl font-bold text-slate-800">Riwayat Transaksi</h3>
                
                @if($isAdmin)
                    <a href="{{ route('laporan.create', [$tempat->slug, $pageId]) }}" class="btn-gradient-pink px-6 py-3 rounded-2xl font-bold shadow-lg shadow-pink-200/50 hover:scale-105 transition-transform">
                        + Tambah Laporan
                    </a>
                @endif
            </div>

            <!-- Filter Inputs -->
            <div class="bg-slate-50/50 p-5 rounded-xl border border-slate-100 mb-6 backdrop-blur-sm">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
                    <div class="relative">
                        <!-- DIGANTI: text-slate-500 -> text-slate-600 -->
                        <label class="block text-xs font-bold text-slate-600 uppercase tracking-wide mb-1.5 ml-1">Cari Keterangan</label>
                        <div class="relative">
                            <!-- DIGANTI: placeholder:text-slate-400 -> placeholder:text-slate-500 -->
                            <input type="text" id="search" placeholder="Ketik sesuatu..." class="glass-input pl-10 text-slate-700 placeholder:text-slate-500">
                            <!-- DIGANTI: text-slate-400 -> text-slate-500 -->
                            <span class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-500">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                            </span>
                        </div>
                    </div>
                    
                    <div>
                        <!-- DIGANTI: text-slate-500 -> text-slate-600 -->
                        <label class="block text-xs font-bold text-slate-600 uppercase tracking-wide mb-1.5 ml-1">Filter Tipe</label>
                        <select id="type" class="glass-input appearance-none cursor-pointer text-slate-700">
                            <option value="">Semua Tipe</option>
                            <option value="pemasukan">Pemasukan</option>
                            <option value="pengeluaran">Pengeluaran</option>
                        </select>
                    </div>

                    <div>
                        <!-- DIGANTI: text-slate-500 -> text-slate-600 -->
                        <label class="block text-xs font-bold text-slate-600 uppercase tracking-wide mb-1.5 ml-1">Urutkan</label>
                        <select id="sort_by" class="glass-input appearance-none cursor-pointer text-slate-700">
                            <option value="tanggal">Terbaru</option>
                            <option value="jumlah">Nominal Terbesar</option>
                        </select>
                    </div>
                </div>
            </div>

            <!-- Table Wrapper -->
            <div class="overflow-hidden rounded-xl border border-slate-100 bg-white/50">
                <div id="laporanTable">
                    @include('partials.laporan-table', ['isAdmin' => $isAdmin])
                </div>
            </div>
            
            <!-- Pagination -->
            <!-- DIGANTI: text-slate-600 -> text-slate-700 biar linknya lebih gelap -->
            <div id="pagination" class="mt-8 flex justify-center text-slate-700">
                {{ $laporans->links() }}
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    let barChart = null, pieChart = null;
    let currentOrder = 'desc';

    const debounce = (func, delay) => {
        let timeout;
        return (...args) => {
            clearTimeout(timeout);
            timeout = setTimeout(() => func(...args), delay);
        };
    };

    // --- 1. Pindahkan Data JSON ke Variabel JS ---
    const dbMonths = {!! json_encode($months) !!};
    const dbMasuk = {!! json_encode($masukData) !!};
    const dbKeluar = {!! json_encode($keluarData) !!};
    const dbPie = {!! json_encode([$totalMasuk, $totalKeluar]) !!};

    // Helper untuk format Rupiah di JS
    const formatRupiah = (number) => {
        return new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', minimumFractionDigits: 0 }).format(number);
    };

    document.addEventListener('DOMContentLoaded', () => {
        initCharts({
            months: dbMonths,
            pemasukan: dbMasuk,
            pengeluaran: dbKeluar,
            pie: dbPie
        });

        document.getElementById('search').addEventListener('input', debounce(loadLaporan, 300));
        ['type', 'sort_by'].forEach(id => document.getElementById(id).addEventListener('change', loadLaporan));
    });

    function initCharts(data) {
        Chart.defaults.font.family = "'Inter', sans-serif";
        Chart.defaults.color = '#64748b'; // Slate 500

        // 1. Bar Chart
        const ctx1 = document.getElementById('barChart').getContext('2d');
        barChart = new Chart(ctx1, {
            type: 'bar',
            data: {
                labels: data.months,
                datasets: [
                    { label: 'Pemasukan', data: data.pemasukan, backgroundColor: '#10b981', borderRadius: 6, barPercentage: 0.6 },
                    { label: 'Pengeluaran', data: data.pengeluaran, backgroundColor: '#ef4444', borderRadius: 6, barPercentage: 0.6 }
                ]
            },
            options: { 
                responsive: true, maintainAspectRatio: false,
                plugins: { legend: { position: 'bottom' } },
                scales: { y: { beginAtZero: true, grid: { color: '#f8fafc' } }, x: { grid: { display: false } } }
            }
        });

        // 2. Pie Chart
        const ctx3 = document.getElementById('pieChart').getContext('2d');
        pieChart = new Chart(ctx3, {
            type: 'doughnut',
            data: {
                labels: ['Pemasukan Total', 'Pengeluaran Total'],
                datasets: [{ data: data.pie, backgroundColor: ['#10b981', '#ef4444'], borderWidth: 0, hoverOffset: 4 }]
            },
            options: { 
                responsive: true, maintainAspectRatio: false,
                cutout: '70%',
                plugins: { legend: { position: 'bottom' } }
            }
        });
    }

    function loadLaporan() {
        const params = new URLSearchParams({
            search: document.getElementById('search').value,
            type: document.getElementById('type').value,
            sort_by: document.getElementById('sort_by').value,
            order: currentOrder
        });

        fetch("{{ route('laporan.show', [$tempat->slug, $pageId]) }}?" + params, {
            headers: { 'X-Requested-With': 'XMLHttpRequest' }
        })
        .then(r => r.json())
        .then(data => {
            // 1. Update Tabel & Pagination
            document.getElementById('laporanTable').innerHTML = data.table;
            document.getElementById('pagination').innerHTML = data.pagination;
            
            // 2. Update Stats (Saldo Total)
            document.getElementById('saldo-total').textContent = formatRupiah(data.summary.saldo);
            
            // 3. Update Ringkasan (Pemasukan & Pengeluaran)
            document.getElementById('total-pemasukan').textContent = formatRupiah(data.summary.totalPemasukan);
            document.getElementById('total-pengeluaran').textContent = formatRupiah(data.summary.totalPengeluaran);

            // 4. Update Saldo Pribadi
            const saldoPribadiEl = document.getElementById('saldo-pribadi');
            const statusBox = document.getElementById('status-box');
            
            const val = parseInt(data.saldoPribadi.replace(/\D/g, '')) || 0;
            saldoPribadiEl.textContent = formatRupiah(val);

            if (val < 0) {
                saldoPribadiEl.classList.remove('text-white');
                saldoPribadiEl.classList.add('text-red-50');
                statusBox.innerHTML = `<span class="mr-1">⚠️</span> Minus ${formatRupiah(Math.abs(val))}`;
                statusBox.className = 'inline-flex items-center px-3 py-1 text-xs font-bold rounded-full bg-red-500/20 backdrop-blur-md border border-red-500/30 text-red-50';
            } else {
                saldoPribadiEl.classList.add('text-white');
                saldoPribadiEl.classList.remove('text-red-50');
                statusBox.innerHTML = `<span class="mr-1">✅</span> ${data.statusSaldo}`;
                statusBox.className = 'inline-flex items-center px-3 py-1 text-xs font-bold rounded-full bg-white/20 backdrop-blur-md border border-white/30 text-white';
            }

            // 5. Update Charts
            if(barChart) {
                barChart.data.labels = data.chart.months;
                barChart.data.datasets[0].data = data.chart.pemasukan;
                barChart.data.datasets[1].data = data.chart.pengeluaran;
                barChart.update();
            }
            if(pieChart) {
                pieChart.data.datasets[0].data = data.chart.pie;
                pieChart.update();
            }
        })
        .catch(err => console.error("Error:", err));
    }
</script>
@endsection