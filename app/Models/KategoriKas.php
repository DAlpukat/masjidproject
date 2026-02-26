<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KategoriKas extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function tempatLayanan()
    {
        return $this->belongsTo(TempatLayanan::class);
    }

    public function logs()
    {
        return $this->hasMany(UserKasLog::class);
    }

    public function isWajib()
    {
        return $this->tipe === 'wajib';
    }
}