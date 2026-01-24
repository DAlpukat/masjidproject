<?php

namespace App\Http\Controllers;

use App\Models\TempatLayanan;
use App\Models\LaporanKas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LaporanKasController extends Controller
{
    public function create($slug, $pageId)
    {
        // Cari Room berdasarkan Slug
        $tempat = TempatLayanan::where('slug', $slug)->firstOrFail();
        
        // Cek apakah user join room ini
        if (!$tempat->users->contains(auth()->id())) {
            abort(403);
        }

        // Ambil semua user di room ini untuk dropdown
        $anggota = $tempat->users;

        return view('room.laporan-create', compact('tempat', 'pageId', 'anggota'));
    }

    public function show($slug, $pageId)
    {
        $tempat = TempatLayanan::where('slug', $slug)->firstOrFail();
        if (!$tempat->users->contains(auth()->id())) {
            abort(403);
        }

        // Filter & Pagination
        $query = $tempat->laporanKas();
        if ($search = request()->input('search')) {
            $query->where('keterangan', 'like', "%{$search}%");
        }
        if ($type = request()->input('type')) {
            $dbType = ($type == 'pemasukan') ? 'masuk' : (($type == 'pengeluaran') ? 'keluar' : 'hutang');
            $query->where('jenis', $dbType);
        }
        $sortBy = request()->input('sort_by', 'tanggal');
        $order = request()->input('order', 'desc');
        $query->orderBy($sortBy, $order);

        $laporans = $query->paginate(10)->appends(request()->query());

        // --- LOGIKA PERHITUNGAN (Tanpa number_format) ---
        
        // 1. Saldo Total Ruangan
        $totalMasuk = $tempat->laporanKas()->where('jenis', 'masuk')->sum('jumlah');
        $totalKeluar = $tempat->laporanKas()->where('jenis', 'keluar')->sum('jumlah');
        $saldoTotal = $totalMasuk - $totalKeluar;

        // 2. Saldo Pribadi User
        // A. Kas Masuk Pribadi (Hanya milik user yang login)
        $personalMasuk = $tempat->laporanKas()
            ->where('jenis', 'masuk')
            ->where('sifat_transaksi', 'personal')
            ->where('user_id', auth()->id())
            ->sum('jumlah');

        // B. Potongan Pengeluaran (Dibagi rata ke semua anggota)
        $totalPengeluaranBersama = $tempat->laporanKas()->where('jenis', 'keluar')->sum('jumlah');
        $jumlahAnggota = $tempat->users->count() > 0 ? $tempat->users->count() : 1;
        $pembagianPerOrang = $totalPengeluaranBersama / $jumlahAnggota;
        
        $saldoPribadi = $personalMasuk - $pembagianPerOrang;
        $statusSaldo = $saldoPribadi >= 0 ? 'Surplus / Lunas' : 'Kurang Bayar';

        // --- DATA CHART ---
        $months = [];
        $masukData = [];
        $keluarData = [];
        $saldoData = [];
        $currentSaldo = 0;

        for ($i = 5; $i >= 0; $i--) {
            $date = now()->startOfMonth()->subMonths($i);
            $months[] = $date->translatedFormat('M Y');

            $masuk = $tempat->laporanKas()
                ->where('jenis', 'masuk')
                ->whereMonth('tanggal', $date->month)
                ->whereYear('tanggal', $date->year)
                ->sum('jumlah');
            $keluar = $tempat->laporanKas()
                ->where('jenis', 'keluar')
                ->whereMonth('tanggal', $date->month)
                ->whereYear('tanggal', $date->year)
                ->sum('jumlah');

            $currentSaldo += ($masuk - $keluar);
            $masukData[] = $masuk;
            $keluarData[] = $keluar;
            $saldoData[] = $currentSaldo;
        }

        // --- JSON Response untuk AJAX ---
        if (request()->ajax() || request()->wantsJson()) {
            return response()->json([
                'table' => view('partials.laporan-table', compact('laporans'))->render(),
                'pagination' => $laporans->links()->render(),
                'summary' => [
                    'totalPemasukan' => 'Rp ' . number_format($totalMasuk, 0, ',', '.'),
                    'totalPengeluaran' => 'Rp ' . number_format($totalKeluar, 0, ',', '.'),
                    'saldo' => 'Rp ' . number_format($saldoTotal, 0, ',', '.'),
                ],
                'saldoPribadi' => 'Rp ' . number_format($saldoPribadi, 0, ',', '.'),
                'statusSaldo' => $statusSaldo,
                'chart' => [
                    'months' => $months,
                    'pemasukan' => $masukData,
                    'pengeluaran' => $keluarData,
                    'saldo' => $saldoData,
                    'pie' => [$totalMasuk, $totalKeluar]
                ]
            ]);
        }

        return view('room.laporan-dashboard', compact(
            'tempat',
            'pageId',
            'laporans',
            'totalMasuk',
            'totalKeluar',
            'saldoTotal',
            'saldoPribadi', 
            'statusSaldo',
            'months',
            'masukData',
            'keluarData',
            'saldoData'
        ));
    }


    public function store(Request $request)
    {
        // Validasi Input
        $validated = $request->validate([
            'tempat_layanan_id' => 'required|exists:tempat_layanans,id',
            'tanggal' => 'required|date',
            'keterangan' => 'required|string',
            'jumlah' => 'required|numeric|min:1',
            'jenis' => 'required|in:masuk,keluar',
            'sifat_transaksi' => 'required|in:personal,umum',
            'user_id' => 'nullable|exists:users,id', // Wajib isi jika memilih "Personal"
            'bukti_foto' => 'nullable|string',
        ]);

        // Logika Khusus: Validasi User Personal
        if ($validated['jenis'] == 'masuk' && $validated['sifat_transaksi'] == 'personal') {
            if (empty($validated['user_id'])) {
                return back()->with('error', 'Wajib pilih user untuk Kas Perorangan!');
            }
        }

        // Simpan Laporan
        LaporanKas::create([
            'tempat_layanan_id' => $validated['tempat_layanan_id'],
            'tanggal' => $validated['tanggal'],
            'keterangan' => $validated['keterangan'],
            'jumlah' => $validated['jumlah'],
            'jenis' => $validated['jenis'],
            'sifat_transaksi' => $validated['sifat_transaksi'],
            'user_id' => $validated['user_id'] ?? null,
            'bukti_foto' => $validated['bukti_foto'] ?? null,
        ]);

        // --- PERBAIKAN REDIRECT ---
        // Kita ambil object TempatLayanan untuk dapat slug dan pageId
        $tempat = TempatLayanan::find($validated['tempat_layanan_id']);
        $pageId = $request->page_id;

        return redirect()->route('laporan.show', [$tempat->slug, $pageId])
            ->with('success', 'Laporan berhasil ditambahkan!');
    }


    public function destroy($id) 
    {
        // Logika hapus laporan kas
        $laporan = LaporanKas::findOrFail($id);
        $tempat = $laporan->tempatLayanan;
        
        if (!$tempat->users->contains(auth()->id())) {
            abort(403);
        }

        $laporan->delete();
        return response()->json(['success' => true, 'message' => 'Laporan dihapus']);
    }
}