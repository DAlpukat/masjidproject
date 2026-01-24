<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LaporanKas extends Model
{
    use HasFactory;

    protected $fillable = ['tempat_layanan_id', 'tanggal', 'keterangan', 'jenis', 'jumlah', 'bukti_foto', 'output_foto'];

    public function tempatLayanan(): BelongsTo
    {
        return $this->belongsTo(TempatLayanan::class);
    }
}