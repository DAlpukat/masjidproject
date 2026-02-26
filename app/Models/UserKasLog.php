<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserKasLog extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    protected $casts = [
        'jumlah' => 'decimal:2'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function kategori()
    {
        return $this->belongsTo(KategoriKas::class, 'kategori_kas_id');
    }

    public function laporanKas()
    {
        return $this->belongsTo(LaporanKas::class);
    }
}