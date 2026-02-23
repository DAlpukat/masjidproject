<?php

namespace App\Http\Controllers;

use App\Models\TempatLayanan;
use App\Models\LaporanKas;
use App\Models\KategoriKas; // Tambahkan ini
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LaporanKasController extends Controller
{
    public function create($slug, $pageId)
    {
        $tempat = TempatLayanan::where('slug', $slug)->firstOrFail();
        
        if ($tempat->user_id !== auth()->id()) {
            abort(403, 'Hanya Admin yang bisa menambah laporan.');
        }

        $anggota = $tempat->users;
        $kategoriKas = $tempat->kategoriKas; // Tambahkan data kategori untuk dropdown

        return view('room.laporan-create', compact('tempat', 'pageId', 'anggota', 'kategoriKas'));
    }

    public function show($slug, $pageId)
    {
        $tempat = TempatLayanan::where('slug', $slug)->firstOrFail();
        
        if (!$tempat->users->contains(auth()->id())) {
            abort(403, 'Kamu belum bergabung di ruangan ini.');
        }

        $isAdmin = ($tempat->user_id === auth()->id());

        // FIX: Tambahkan Eager Loading
        $query = $tempat->laporanKas()->with(['user', 'kategori']);
        
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

        // Hitung total saldo ruangan
        $totalMasuk = $tempat->laporanKas()->where('jenis', 'masuk')->sum('jumlah');
        $totalKeluar = $tempat->laporanKas()->where('jenis', 'keluar')->sum('jumlah');
        $saldoTotal = $totalMasuk - $totalKeluar;

        // Hitung saldo pribadi
        $personalMasuk = $tempat->laporanKas()
            ->where('jenis', 'masuk')
            ->where('sifat_transaksi', 'personal')
            ->where('user_id', auth()->id())
            ->sum('jumlah');

        $totalPengeluaranBersama = $tempat->laporanKas()->where('jenis', 'keluar')->sum('jumlah');
        $jumlahAnggota = $tempat->users->count() > 0 ? $tempat->users->count() : 1; 
        $pembagianPerOrang = $totalPengeluaranBersama / $jumlahAnggota;
        
        $saldoPribadi = $personalMasuk - $pembagianPerOrang;
        $statusSaldo = $saldoPribadi >= 0 ? 'Surplus / Lunas' : 'Kurang Bayar';

        // Data Chart
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

        if (request()->ajax() || request()->wantsJson()) {
            return response()->json([
                'table' => view('partials.laporan-table', compact('laporans', 'isAdmin'))->render(),
                'pagination' => $laporans->links()->render(),
                'summary' => [
                    'totalPemasukan' => 'Rp ' . number_format($totalMasuk, 0, ',', '.'),
                    'totalPengeluaran' => 'Rp ' . number_format($totalKeluar, 0, ',', '.'),
                    'saldo' => 'Rp ' . number_format($saldoTotal, 0, ',', '.'),
                ],
                'saldoPribadi' => 'Rp ' . number_format($saldoPribadi, 0, ',', '.'),
                'statusSaldo' => $statusSaldo,
                'isAdmin' => $isAdmin,
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
            'tempat', 'pageId', 'laporans', 'totalMasuk', 'totalKeluar', 
            'saldoTotal', 'saldoPribadi', 'statusSaldo', 'months', 
            'masukData', 'keluarData', 'saldoData', 'isAdmin'
        ));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'tempat_layanan_id' => 'required|exists:tempat_layanans,id',
            'tanggal' => 'required|date',
            'keterangan' => 'required|string',
            'jumlah' => 'required|numeric|min:1',
            'jenis' => 'required|in:masuk,keluar',
            'sifat_transaksi' => 'required|in:personal,umum',
            'user_id' => 'nullable|exists:users,id',
            'kategori_kas_id' => 'nullable|exists:kategori_kas,id', // Tambah validasi kategori
            'bukti_foto' => 'nullable|string',
        ]);

        $tempat = TempatLayanan::find($validated['tempat_layanan_id']);
        if ($tempat->user_id !== auth()->id()) {
            abort(403, 'Anda tidak berhak menambah laporan di room ini.');
        }

        // Logika Khusus: Jika Personal, user_id wajib
        if ($validated['sifat_transaksi'] == 'personal' && empty($validated['user_id'])) {
            return back()->with('error', 'Wajib pilih user untuk Kas Perorangan!')->withInput();
        }

        LaporanKas::create([
            'tempat_layanan_id' => $validated['tempat_layanan_id'],
            'tanggal' => $validated['tanggal'],
            'keterangan' => $validated['keterangan'],
            'jumlah' => $validated['jumlah'],
            'jenis' => $validated['jenis'],
            'sifat_transaksi' => $validated['sifat_transaksi'],
            'user_id' => $validated['user_id'] ?? null,
            'kategori_kas_id' => $validated['kategori_kas_id'] ?? null, // Simpan kategori
            'bukti_foto' => $validated['bukti_foto'] ?? null,
        ]);

        $pageId = $request->page_id;

        return redirect()->route('laporan.show', [$tempat->slug, $pageId])
            ->with('success', 'Laporan berhasil ditambahkan!');
    }

    public function destroy($id) 
    {
        $laporan = LaporanKas::findOrFail($id);
        $tempat = $laporan->tempatLayanan;
        
        if ($tempat->user_id !== auth()->id()) {
            abort(403, 'Akses ditolak.');
        }

        $laporan->delete();
        return response()->json(['success' => true, 'message' => 'Laporan dihapus']);
    }
}