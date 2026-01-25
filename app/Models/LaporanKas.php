<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\TempatLayanan; 
use App\Models\User;

class LaporanKas extends Model
{
    use HasFactory;

    protected $fillable = [
        'tempat_layanan_id',
        'tanggal',
        'keterangan',
        'jumlah',
        'jenis',
        'bukti_foto',
        'output_foto',
        'sifat_transaksi',
        'user_id',
    ];

    public function tempatLayanan(): BelongsTo
    {
        return $this->belongsTo(TempatLayanan::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}