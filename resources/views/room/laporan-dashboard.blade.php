@extends('layouts.app')

@section('content')
<div class="container mx-auto py-8 px-4">
    <div class="max-w-7xl mx-auto">
        
        <!-- Header -->
        <div class="mb-6">
            <a href="{{ route('room.view', $tempat->slug) }}" class="text-blue-600 hover:underline">&larr; Kembali ke Room</a>
            <h1 class="text-3xl font-bold mt-2">{{ $tempat->nama }} - Laporan Kas</h1>
        </div>

        <!-- Stats Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
            
            <!-- CARD 1: SALDO TOTAL RUANGAN -->
            <div class="bg-gradient-to-br from-blue-500 to-blue-600 p-6 text-white rounded-lg shadow-lg">
                <h3 class="text-lg font-medium opacity-90">Saldo Total Ruangan</h3>
                <p class="text-xs opacity-75 mb-2">Kas Umum + Kas Pribadi (Total)</p>
                <p id="saldo-total" class="text-3xl font-bold">
                    Rp {{ number_format($saldoTotal, 0, ',', '.') }}
                </p>
            </div>

            <!-- CARD 2: SALDO PRIBADI -->
            <div class="bg-gradient-to-br from-emerald-500 to-emerald-600 p-6 text-white rounded-lg shadow-lg">
                <h3 class="text-lg font-medium opacity-90">Saldo Pribadi Saya</h3>
                <p class="text-xs opacity-75 mb-2">Total Setoran Pribadi - (Pengeluaran Bersama / Jml Anggota)</p>
                
                <p id="saldo-pribadi" class="text-3xl font-bold">
                    {{ number_format($saldoPribadi, 0, ',', '.') }}
                </p>
                
                <div id="status-box" class="mt-2 px-2 py-1 text-xs rounded">
                    @if($saldoPribadi < 0)
                        <div class="bg-red-500/30">
                            ⚠️ Anda {{ number_format(abs($saldoPribadi), 0, ',', '.') }} Minus
                        </div>
                    @else
                        <div class="bg-white/20">
                            ✅ {{ $statusSaldo }}
                        </div>
                    @endif
                </div>
            </div>

        <!-- Charts -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
            <div class="bg-white p-6 rounded-lg shadow">
                <h3 class="text-lg font-semibold text-gray-700 mb-4">Pemasukan vs Pengeluaran</h3>
                <canvas id="barChart"></canvas>
            </div>
            <div class="bg-white p-6 rounded-lg shadow">
                <h3 class="text-lg font-semibold text-gray-700 mb-4">Komposisi Keuangan</h3>
                <canvas id="pieChart"></canvas>
            </div>
        </div>

        <!-- Filter & Tabel -->
        <div class="bg-white p-6 rounded-lg shadow">
            <div class="flex justify-between items-center mb-6">
                <h3 class="text-lg font-medium">Riwayat Laporan</h3>
                
                <a href="{{ route('laporan.create', [$tempat->slug, $pageId]) }}" class="bg-green-600 text-white px-6 py-2 rounded hover:bg-green-700 font-bold">
                    + Tambah Laporan
                </a>
            </div>

            <!-- Filter Inputs -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
                <input type="text" id="search" placeholder="Cari keterangan..." class="px-4 py-2 border rounded-md w-full">
                <select id="type" class="px-4 py-2 border rounded-md w-full">
                    <option value="">Semua Tipe</option>
                    <option value="pemasukan">Pemasukan</option>
                    <option value="pengeluaran">Pengeluaran</option>
                </select>
                <select id="sort_by" class="px-4 py-2 border rounded-md w-full">
                    <option value="tanggal">Tanggal</option>
                    <option value="jumlah">Jumlah</option>
                </select>
            </div>

            <div id="laporanTable">
                @include('partials.laporan-table')
            </div>
            <div id="pagination" class="mt-6">{{ $laporans->links() }}</div>
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


    document.addEventListener('DOMContentLoaded', () => {
        // Panggil function initCharts dengan data tersebut
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
        // 1. Bar Chart
        const ctx1 = document.getElementById('barChart').getContext('2d');
        barChart = new Chart(ctx1, {
            type: 'bar',
            data: {
                labels: data.months,
                datasets: [
                    { label: 'Pemasukan', data: data.pemasukan, backgroundColor: 'rgba(34, 197, 94, 0.6)' },
                    { label: 'Pengeluaran', data: data.pengeluaran, backgroundColor: 'rgba(239, 68, 68, 0.6)' }
                ]
            },
            options: { responsive: true }
        });

        // 2. Pie Chart
        const ctx3 = document.getElementById('pieChart').getContext('2d');
        pieChart = new Chart(ctx3, {
            type: 'doughnut',
            data: {
                labels: ['Pemasukan Total', 'Pengeluaran Total'],
                datasets: [{
                    data: data.pie,
                    backgroundColor: ['#22c55e', '#ef4444']
                }]
            },
            options: { responsive: true }
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
            document.getElementById('total-pemasukan').textContent = data.summary.totalPemasukan;
            document.getElementById('total-pengeluaran').textContent = data.summary.totalPengeluaran;
            document.getElementById('saldo-total').textContent = data.summary.saldo;

            // 3. Update Saldo Pribadi
            const saldoPribadiEl = document.getElementById('saldo-pribadi');
            const statusBox = document.getElementById('status-box');

            saldoPribadiEl.textContent = data.saldoPribadi;

            const val = parseInt(data.saldoPribadi.replace(/\D/g, '')) || 0;

            if (val < 0) {
                saldoPribadiEl.classList.add('text-red-100');
                statusBox.innerHTML = `⚠️ ${Math.abs(val).toLocaleString('id-ID')} Minus`;
                statusBox.className = 'mt-2 bg-red-500/30 p-2 rounded text-xs';
            } else {
                saldoPribadiEl.classList.remove('text-red-100');
                statusBox.innerHTML = `✅ ${data.statusSaldo}`;
                statusBox.className = 'mt-2 bg-white/20 p-2 rounded text-xs';
            }

            // 4. Update Charts
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
        });
    }
</script>
@endsection