<?php

namespace App\Http\Controllers;

use App\Models\TempatLayanan;
use App\Models\LaporanKas;
use App\Models\KategoriKas;
use App\Models\UserKasLog;
use App\Services\KasTransactionService;
use Illuminate\Http\Request;

class KasController extends Controller
{
    public function destroy($id)
    {
        $laporan = LaporanKas::findOrFail($id);

        // 1. Cek Admin / Owner
        if (auth()->id() != $laporan->tempatLayanan->user_id) {
            return response()->json(['error' => 'Akses Ditolak'], 403);
        }

        // 2. Hapus Transaksi (Rollback otomatis user logs)
        \DB::transaction(function () use ($id, $laporan) {
            UserKasLog::where('laporan_kas_id', $id)->delete();
            $laporan->delete();
        });

        // 3. HITUNG ULANG DATA YANG DIBUTUHKAN UNTUK AJAX UPDATE
        $tempatId = $laporan->tempat_layanan_id;
        
        // Saldo Total
        $newTotalBalance = LaporanKas::where('tempat_layanan_id', $tempatId)
            ->selectRaw("SUM(IF(jenis='masuk', jumlah, -jumlah)) as saldo")
            ->value('saldo') ?? 0;

        // Saldo Pribadi
        $newPersonalBalance = 0;
        $tempat = TempatLayanan::find($tempatId);
        if ($tempat->use_individual_ledger) {
            $newPersonalBalance = UserKasLog::whereHas('kategori', function($q) use ($tempatId) {
                    $q->where('tempat_layanan_id', $tempatId);
                })
                ->where('user_id', auth()->id())
                ->selectRaw("SUM(IF(jenis='masuk', jumlah, -jumlah)) as saldo")
                ->value('saldo') ?? 0;
        }

        // Data Grafik Baru
        $newChartData = LaporanKas::where('tempat_layanan_id', $tempatId)
            ->selectRaw("DATE_FORMAT(tanggal, '%Y-%m') as bulan, SUM(CASE WHEN jenis='masuk' THEN jumlah ELSE 0 END) as pemasukan, SUM(CASE WHEN jenis='keluar' THEN jumlah ELSE 0 END) as pengeluaran")
            ->groupBy('bulan')
            ->orderBy('bulan', 'ASC')
            ->limit(6)
            ->get();

        // Return semua data yang diperlukan untuk update UI tanpa reload
        return response()->json([
            'success' => true,
            'message' => 'Transaksi dihapus',
            'totalBalance' => $newTotalBalance,
            'personalBalance' => $newPersonalBalance,
            'chartLabels' => $newChartData->pluck('bulan'),
            'chartMasuk' => $newChartData->pluck('pemasukan'),
            'chartKeluar' => $newChartData->pluck('pengeluaran'),
        ]);
    }


    public function dashboard($tempatId)
    {
        $tempat = TempatLayanan::findOrFail($tempatId);

        // 1. Hitung Saldo Total (Fisik)
        $totalBalance = LaporanKas::where('tempat_layanan_id', $tempatId)
            ->selectRaw("SUM(IF(jenis='masuk', jumlah, -jumlah)) as saldo")
            ->value('saldo') ?? 0;

        // 2. Hitung Saldo Pribadi (Jika Ledger Aktif)
        $personalBalance = 0;
        if ($tempat->use_individual_ledger) {
            $personalBalance = UserKasLog::whereHas('kategori', function($q) use ($tempatId) {
                    $q->where('tempat_layanan_id', $tempatId);
                })
                ->where('user_id', auth()->id())
                ->selectRaw("SUM(IF(jenis='masuk', jumlah, -jumlah)) as saldo")
                ->value('saldo') ?? 0;
        }

        // 3. Data untuk Grafik (6 Bulan Terakhir)
        $chartData = LaporanKas::where('tempat_layanan_id', $tempatId)
            ->selectRaw("DATE_FORMAT(tanggal, '%Y-%m') as bulan, SUM(CASE WHEN jenis='masuk' THEN jumlah ELSE 0 END) as pemasukan, SUM(CASE WHEN jenis='keluar' THEN jumlah ELSE 0 END) as pengeluaran")
            ->groupBy('bulan')
            ->orderBy('bulan', 'ASC')
            ->limit(6)
            ->get();

        $laporans = LaporanKas::where('tempat_layanan_id', $tempatId)
                              ->latest('tanggal')
                              ->paginate(20);

        $kategoriKas = $tempat->kategoriKas;

        // Kirim variable baru ke view
        return view('kas.kas-dashboard', compact('tempat', 'laporans', 'kategoriKas', 'totalBalance', 'personalBalance', 'chartData'));
    }

    public function store(Request $request, $tempatId)
    {
        // 1. Cari Tempat
        $tempat = TempatLayanan::findOrFail($tempatId);

        // 2. VALIDASI HAK AKSES
        if (auth()->id() != $tempat->user_id) {
            abort(403, 'Anda tidak memiliki izin untuk mencatat transaksi.');
        }

        // 3. Validasi Input
        $request->validate([
            'tanggal'           => 'required|date',
            'keterangan'        => 'required|string',
            'jenis'             => 'required|in:masuk,keluar',
            'jumlah'            => 'required|numeric|min:0',
            'bukti_foto'        => 'nullable|image|max:2048',
            
            'is_personal'       => 'nullable|boolean', 
            'user_id'           => 'nullable|required_if:is_personal,1',
            'kategori_kas_id'   => 'nullable|required_if:is_personal,1',
            'tipe_pengeluaran'  => 'nullable|required_if:jenis,keluar|in:shared,free',
        ]);

        // 4. Proses Transaksi
        $service = new KasTransactionService($tempat);

        try {
            $data = $request->all();
            
            // Handle Boolean Conversion
            $data['is_personal'] = $request->boolean('is_personal');
            
            // --- LOGIKA PEMBERSIHAN ---
            
            // 1. Jika Uang Bebas (Masuk Umum)
            if ($data['jenis'] === 'masuk' && !$data['is_personal']) {
                $data['user_id'] = null;
                $data['kategori_kas_id'] = null;
            }

            // 2. Jika PEMASUKAN, pastikan tipe pengeluaran di-null kan
            // (Mencegah label "Shared Expense" muncul di transaksi Pemasukan)
            if ($data['jenis'] === 'masuk') {
                $data['tipe_pengeluaran'] = null; 
            }

            // Handle File Upload
            if ($request->hasFile('bukti_foto')) {
                $data['bukti_foto'] = $request->file('bukti_foto')->store('kas', 'public');
            }

            $service->processTransaction($data);

            return back()->with('success', 'Transaksi berhasil disimpan.');
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage())->withInput();
        }
    }

    public function storeKategori(Request $request, $tempatId)
    {
        $tempat = TempatLayanan::findOrFail($tempatId);

        if (auth()->id() != $tempat->user_id) {
            abort(403);
        }

        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'tipe' => 'required|in:wajib,sedekah',
        ]);

        KategoriKas::create([
            'tempat_layanan_id' => $tempat->id,
            'nama' => $validated['nama'],
            'tipe' => $validated['tipe'],
            'allow_negative' => ($validated['tipe'] == 'wajib'),
            'target_amount' => null,
        ]);

        return back()->with('success', 'Kategori berhasil ditambahkan.');
    }
}