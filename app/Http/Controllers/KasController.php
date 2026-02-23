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
    /**
     * Menghapus transaksi (hanya untuk Admin pemilik).
     * Route: DELETE /kas/destroy/{id}
     */
    public function destroy($id)
    {
        $laporan = LaporanKas::findOrFail($id);

        // Otorisasi: Hanya pemilik tempat layanan yang bisa hapus
        if (auth()->id() != $laporan->tempatLayanan->user_id) {
            return response()->json(['error' => 'Akses Ditolak'], 403);
        }

        \DB::transaction(function () use ($id, $laporan) {
            // Hapus log kas user jika ada (pengembalian dana atau penghapusan utang)
            UserKasLog::where('laporan_kas_id', $id)->delete();
            $laporan->delete();
        });

        $tempatId = $laporan->tempat_layanan_id;
        
        // Kalkulasi ulang saldo total
        $newTotalBalance = LaporanKas::where('tempat_layanan_id', $tempatId)
            ->selectRaw("SUM(IF(jenis='masuk', jumlah, -jumlah)) as saldo")
            ->value('saldo') ?? 0;

        // Kalkulasi ulang saldo pribadi jika mode individual aktif
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

        // Data untuk chart update (opsional, jika pakai AJAX realtime)
        $newChartData = LaporanKas::where('tempat_layanan_id', $tempatId)
            ->selectRaw("DATE_FORMAT(tanggal, '%Y-%m') as bulan, SUM(CASE WHEN jenis='masuk' THEN jumlah ELSE 0 END) as pemasukan, SUM(CASE WHEN jenis='keluar' THEN jumlah ELSE 0 END) as pengeluaran")
            ->groupBy('bulan')
            ->orderBy('bulan', 'ASC')
            ->limit(6)
            ->get();

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

    /**
     * Menampilkan Dashboard Kas.
     * Route: GET /room/{slug}/kas
     */
    public function dashboard($slug)
    {
        // Cari tempat berdasarkan slug
        $tempat = TempatLayanan::where('slug', $slug)->firstOrFail();
        
        $tempatId = $tempat->id;

        // Hitung Saldo Total Fisik
        $totalBalance = LaporanKas::where('tempat_layanan_id', $tempatId)
            ->selectRaw("SUM(IF(jenis='masuk', jumlah, -jumlah)) as saldo")
            ->value('saldo') ?? 0;

        // Hitung Saldo Pribadi (jika mode individual)
        $personalBalance = 0;
        if ($tempat->use_individual_ledger) {
            $personalBalance = UserKasLog::whereHas('kategori', function($q) use ($tempatId) {
                    $q->where('tempat_layanan_id', $tempatId);
                })
                ->where('user_id', auth()->id())
                ->selectRaw("SUM(IF(jenis='masuk', jumlah, -jumlah)) as saldo")
                ->value('saldo') ?? 0;
        }

        // Data Grafik (6 bulan terakhir)
        $chartData = LaporanKas::where('tempat_layanan_id', $tempatId)
            ->selectRaw("DATE_FORMAT(tanggal, '%Y-%m') as bulan, SUM(CASE WHEN jenis='masuk' THEN jumlah ELSE 0 END) as pemasukan, SUM(CASE WHEN jenis='keluar' THEN jumlah ELSE 0 END) as pengeluaran")
            ->groupBy('bulan')
            ->orderBy('bulan', 'DESC') 
            ->limit(6)
            ->get();

        // Riwayat Transaksi dengan Pagination
        $laporans = LaporanKas::with(['user', 'kategori'])
            ->where('tempat_layanan_id', $tempatId)
            ->latest('tanggal')
            ->paginate(20);

        $kategoriKas = $tempat->kategoriKas;

        return view('kas.kas-dashboard', compact('tempat', 'laporans', 'kategoriKas', 'totalBalance', 'personalBalance', 'chartData'));
    }

    /**
     * Menyimpan Transaksi Baru.
     * Route: POST /room/{slug}/kas
     */
    public function store(Request $request, $slug)
    {
        $tempat = TempatLayanan::where('slug', $slug)->firstOrFail();

        // Otorisasi: Hanya Admin
        if (auth()->id() != $tempat->user_id) {
            abort(403, 'Anda tidak memiliki izin untuk mencatat transaksi.');
        }

        $request->validate([
            'tanggal'           => 'required|date',
            'keterangan'        => 'required|string',
            'jenis'             => 'required|in:masuk,keluar',
            'jumlah'            => 'required|numeric|min:0',
            'bukti_foto'        => 'nullable|image|max:2048',
            
            'is_personal'       => 'nullable|boolean', 
            'user_id'           => 'required_if:is_personal,1|nullable|exists:users,id',
            'kategori_kas_id'   => 'required_if:is_personal,1|nullable|exists:kategori_kas,id',
            
            'tipe_pengeluaran'  => 'nullable|required_if:jenis,keluar|in:shared,free',
        ]);

        $service = new KasTransactionService($tempat);

        try {
            $data = $request->all();
            
            $data['is_personal'] = $request->boolean('is_personal');
            
            // Set default null untuk transaksi umum
            if ($data['jenis'] === 'masuk' && !$data['is_personal']) {
                $data['user_id'] = null;
                $data['kategori_kas_id'] = null;
            }

            if ($data['jenis'] === 'masuk') {
                $data['tipe_pengeluaran'] = null; 
            }

            if ($request->hasFile('bukti_foto')) {
                $data['bukti_foto'] = $request->file('bukti_foto')->store('kas', 'public');
            }

            $service->processTransaction($data);

            return back()->with('success', 'Transaksi berhasil disimpan.');
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage())->withInput();
        }
    }

    /**
     * Menyimpan Kategori Kas Baru.
     * Route: POST /room/{slug}/kategori
     */
    public function storeKategori(Request $request, $slug)
    {
        $tempat = TempatLayanan::where('slug', $slug)->firstOrFail();

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