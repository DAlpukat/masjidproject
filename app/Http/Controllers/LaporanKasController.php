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
        $tempat = TempatLayanan::where('slug', $slug)->firstOrFail();
        
        // Hanya Admin (Pemilik) yang bisa masuk halaman tambah
        if ($tempat->user_id !== auth()->id()) {
            abort(403, 'Hanya Admin yang bisa menambah laporan.');
        }

        $anggota = $tempat->users;
        return view('room.laporan-create', compact('tempat', 'pageId', 'anggota'));
    }

    public function show($slug, $pageId)
    {
        $tempat = TempatLayanan::where('slug', $slug)->firstOrFail();
        
        // Cek apakah user yang login adalah anggota ruangan ATAU admin
        if (!$tempat->users->contains(auth()->id())) {
            abort(403, 'Kamu belum bergabung di ruangan ini.');
        }

        // Tentukan apakah user yang sedang login adalah Admin Ruangan ini
        $isAdmin = ($tempat->user_id === auth()->id());

        // Ambil data laporan
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

        // Hitung pembagian pengeluaran bersama
        $totalPengeluaranBersama = $tempat->laporanKas()->where('jenis', 'keluar')->sum('jumlah');
        // Gunakan count user yang join (bukan termasuk admin jika join)
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

        // Respon JSON
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
                'isAdmin' => $isAdmin, // Kirim status admin untuk JS jika diperlukan
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
            'saldoData',
            'isAdmin' // PENTING: Kirim variabel ini ke view
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
            'bukti_foto' => 'nullable|string',
        ]);

        // Hanya Admin yang bisa store
        $tempat = TempatLayanan::find($validated['tempat_layanan_id']);
        if ($tempat->user_id !== auth()->id()) {
            abort(403, 'Anda tidak berhak menambah laporan di room ini.');
        }

        if ($validated['jenis'] == 'masuk' && $validated['sifat_transaksi'] == 'personal') {
            if (empty($validated['user_id'])) {
                return back()->with('error', 'Wajib pilih user untuk Kas Perorangan!');
            }
        }

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

        $pageId = $request->page_id;

        return redirect()->route('laporan.show', [$tempat->slug, $pageId])
            ->with('success', 'Laporan berhasil ditambahkan!');
    }

    public function destroy($id) 
    {
        $laporan = LaporanKas::findOrFail($id);
        $tempat = $laporan->tempatLayanan;
        
        // Hanya Admin yang bisa hapus
        if ($tempat->user_id !== auth()->id()) {
            abort(403, 'Akses ditolak.');
        }

        $laporan->delete();
        return response()->json(['success' => true, 'message' => 'Laporan dihapus']);
    }
}