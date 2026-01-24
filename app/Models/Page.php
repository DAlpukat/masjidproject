<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Page extends Model
{
    use HasFactory;

    // Ini diperlukan agar fungsi Store() di controller bisa jalan
    protected $fillable = [
        'tempat_layanan_id',
        'judul',
        'tipe',
        'urutan',
    ];

    // Relasi Balik ke TempatLayanan
    public function tempatLayanan(): BelongsTo
    {
        return $this->belongsTo(TempatLayanan::class);
    }
}