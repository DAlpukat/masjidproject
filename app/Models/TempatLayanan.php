<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class TempatLayanan extends Model
{
    use HasFactory;

    // PASTIKAN 4 KOLOM BARU INI ADA DI BAWAH INI:
    protected $fillable = [
        'nama',
        'slug',
        'deskripsi',
        'status',
        'is_public',
        'kode_referral',
        'user_id',
        
        // KOLOM UNTUK LOGIKA KAS BARU:
        'use_individual_ledger',
        'use_mandatory_cash',
        'shared_expense_enabled',
        'free_expense_enabled',
    ];

    // Casts sudah benar
    protected $casts = [
        'is_public' => 'boolean',
        'use_individual_ledger' => 'boolean',
        'use_mandatory_cash' => 'boolean',
        'shared_expense_enabled' => 'boolean',
        'free_expense_enabled' => 'boolean',
    ];

    public function pages()
    {
        return $this->hasMany(Page::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function laporanKas(): HasMany
    {
        return $this->hasMany(LaporanKas::class);
    }

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class);
    }

    public function kategoriKas()
    {
        return $this->hasMany(KategoriKas::class);
    }

    public function anggota()
    {
        return $this->belongsToMany(User::class, 'tempat_layanan_user');
    }

    public function getMembersCountAttribute()
    {
        return $this->users()->where('users.id', '!=', $this->user_id)->count();
    }
}