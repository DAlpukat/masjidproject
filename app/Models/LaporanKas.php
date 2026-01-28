<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

use App\Models\TempatLayanan; 
use App\Models\User;
use App\Models\KategoriKas;

class LaporanKas extends Model
{
    use HasFactory;

    // Kita masukkan semua kolom (lama & baru) agar aman
    protected $fillable = [
        'tempat_layanan_id',
        'tanggal',
        'keterangan',
        'jumlah',
        'jenis',
        'bukti_foto',
        'output_foto',
        
        // Kolom Baru (Logika Kas Rombakan)
        'kategori_kas_id', 
        'tipe_pengeluaran', 
        
        // Kolom Lama (Migrasi sebelumnya)
        'sifat_transaksi',
        'user_id',
    ];

    // CASTS PENTING: Agar tanggal bisa diformat dan jumlah terbaca desimal
    protected $casts = [
        'jumlah' => 'decimal:2',
        'tanggal' => 'date', // <--- Ini yang memperbaiki Error sebelumnya
    ];

    public function tempatLayanan(): BelongsTo
    {
        return $this->belongsTo(TempatLayanan::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    // Relasi Baru untuk Kategori
    public function kategori(): BelongsTo
    {
        return $this->belongsTo(KategoriKas::class);
    }
}