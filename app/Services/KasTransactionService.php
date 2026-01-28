<?php

namespace App\Services;

use App\Models\TempatLayanan;
use App\Models\LaporanKas;
use App\Models\KategoriKas;
use App\Models\UserKasLog;
use Illuminate\Support\Facades\DB;

class KasTransactionService
{
    protected $tempat;

    public function __construct(TempatLayanan $tempat)
    {
        $this->tempat = $tempat;
    }

    public function processTransaction(array $data)
    {
        // Validasi Konfigurasi Grup
        $this->validateGroupConfig($data);

        return DB::transaction(function () use ($data) {
            // 1. Simpan Transaksi Utama (Laporan Kas Grup)
            $laporan = LaporanKas::create([
                'tempat_layanan_id' => $this->tempat->id,
                'tanggal'           => $data['tanggal'],
                'keterangan'        => $data['keterangan'],
                'jenis'             => $data['jenis'], // 'masuk' / 'keluar'
                'jumlah'            => $data['jumlah'],
                'bukti_foto'        => $data['bukti_foto'] ?? null,
                'kategori_kas_id'   => $data['kategori_kas_id'] ?? null,
                'tipe_pengeluaran'  => $data['tipe_pengeluaran'] ?? null, // 'shared' / 'free'
            ]);

            // 2. Proses Logika Ledger Perorangan (jika diaktifkan)
            if ($this->tempat->use_individual_ledger) {
                if ($data['jenis'] === 'masuk') {
                    $this->handleIncome($laporan, $data);
                } else {
                    $this->handleExpense($laporan, $data);
                }
            }

            return $laporan;
        });
    }

    private function validateGroupConfig(array $data)
    {
        if ($data['jenis'] === 'masuk') {
            // Jika pemasukan perorangan, pastikan ledger aktif
            if (!empty($data['user_id']) && !$this->tempat->use_individual_ledger) {
                throw new \Exception("Grup ini tidak menggunakan ledger perorangan.");
            }
        } elseif ($data['jenis'] === 'keluar') {
            // Validasi tipe pengeluaran vs konfigurasi
            $type = $data['tipe_pengeluaran'] ?? 'free';
            
            if ($type === 'shared' && !$this->tempat->shared_expense_enabled) {
                throw new \Exception("Pengeluaran bersama dinonaktifkan untuk grup ini.");
            }
            if ($type === 'free' && !$this->tempat->free_expense_enabled) {
                throw new \Exception("Pengeluaran bebas dinonaktifkan untuk grup ini.");
            }
        }
    }

    private function handleIncome(LaporanKas $laporan, array $data)
    {
        // Cek apakah ini setor perorangan?
        if (empty($data['user_id']) || empty($data['kategori_kas_id'])) {
            // Jika kosong, dianggap Uang Bebas -> Tidak perlu catat ke Ledger User
            return; 
        }

        // Proses Setor Perorangan
        $kategori = KategoriKas::find($data['kategori_kas_id']);
        
        if ($kategori->tempat_layanan_id !== $this->tempat->id) {
            throw new \Exception("Kategori tidak valid.");
        }

        UserKasLog::create([
            'user_id'         => $data['user_id'],
            'kategori_kas_id' => $kategori->id,
            'laporan_kas_id'  => $laporan->id,
            'jenis'           => 'masuk',
            'jumlah'          => $laporan->jumlah,
            'keterangan'      => 'Setor ' . $kategori->nama,
        ]);
    }

    private function handleExpense(LaporanKas $laporan, array $data)
    {
        $type = $data['tipe_pengeluaran'] ?? 'free';

        if ($type === 'shared') {
            // Logika: Pengeluaran Kas Bersama
            // Mengurangi saldo kas wajib SEMUA anggota aktif
            
            // 1. Cari Kategori Wajib Utama (Bisa ditambah flag is_default di DB, disini kita cari pertama)
            $kategori = KategoriKas::where('tempat_layanan_id', $this->tempat->id)
                                  ->where('tipe', 'wajib')
                                  ->first();
                                  
            if (!$kategori) {
                throw new \Exception("Tidak ada kategori kas wajib ditemukan untuk didistribusikan.");
            }

            // 2. Ambil semua anggota
            $anggotas = $this->tempat->anggota;
            if ($anggotas->isEmpty()) {
                throw new \Exception("Tidak ada anggota untuk didistribusikan biaya.");
            }

            // 3. Hitung pembagian
            $jumlahPerOrang = $laporan->jumlah / $anggotas->count();

            // 4. Distribusikan ke Log
            foreach ($anggotas as $user) {
                // Cek Saldo Saat Ini
                $saldoSekarang = $this->getUserBalance($user->id, $kategori->id);
                $saldoBaru = $saldoSekarang - $jumlahPerOrang;

                // Validasi: Apakah boleh minus?
                if (!$kategori->allow_negative && $saldoBaru < 0) {
                    // Opsi: Throw error, atau biarkan jadi minus tapi warning. 
                    // Sesuai request "kas wajib mengizinkan saldo bernilai negatif", kita anggap kategori wajib boleh minus secara default atau via config.
                    // Tapi jika 'sedekah' dipaksa shared, harusnya error.
                    if (!$kategori->isWajib()) {
                        throw new \Exception("Saldo user {$user->name} tidak cukup dan kategori ini tidak boleh minus.");
                    }
                }

                UserKasLog::create([
                    'user_id'         => $user->id,
                    'kategori_kas_id' => $kategori->id,
                    'laporan_kas_id'  => $laporan->id,
                    'jenis'           => 'keluar',
                    'jumlah'          => $jumlahPerOrang,
                    'keterangan'      => 'Bagian Pengeluaran Bersama: ' . $laporan->keterangan,
                ]);
            }
        }
        // Jika tipe == 'free', tidak terjadi apa-apa di ledger user.
    }

    // Helper untuk hitung saldo real-time user di kategori tertentu
    public function getUserBalance($userId, $kategoriId)
    {
        return UserKasLog::where('user_id', $userId)
                         ->where('kategori_kas_id', $kategoriId)
                         ->selectRaw('SUM(IF(jenis="masuk", jumlah, -jumlah)) as saldo')
                         ->value('saldo') ?? 0;
    }
}