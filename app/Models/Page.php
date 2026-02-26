<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Page extends Model
{
    use HasFactory;

    protected $fillable = [
        'tempat_layanan_id',
        'judul',
        'tipe',
        'urutan',
    ];

    public function tempatLayanan()
    {
        return $this->belongsTo(TempatLayanan::class, 'tempat_layanan_id');
    }

    public function posts()
    {
        return $this->hasMany(Post::class)->latest();
    }
}