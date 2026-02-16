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

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'nama',
        'slug',
        'deskripsi',
        'status', // 'pending', 'approved', 'rejected'
        'is_public', // true (publik), false (privat)
        'kode_referral', // Kode akses jika privat
        'user_id',
        
        // Konfigurasi Logika Kas
        'use_individual_ledger',
        'use_mandatory_cash',
        'shared_expense_enabled',
        'free_expense_enabled',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'is_public' => 'boolean',
        'use_individual_ledger' => 'boolean',
        'use_mandatory_cash' => 'boolean',
        'shared_expense_enabled' => 'boolean',
        'free_expense_enabled' => 'boolean',
    ];

    // RELATIONS

    public function pages(): HasMany
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

    // RELATIONS
    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'tempat_layanan_user', 'tempat_layanan_id', 'user_id');
    }

    public function kategoriKas(): HasMany
    {
        return $this->hasMany(KategoriKas::class);
    }

    public function anggota(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'tempat_layanan_user');
    }

    // ACCESSORS (Helpers)

    public function getAnggotaCountAttribute(): int
    {
        // Mengambil semua user di pivot yang bukan pemilik dan bukan super admin (berdasarkan email)
        return $this->users()
            ->where('users.id', '!=', $this->user_id)
            ->where('users.email', '!=', 'superadmin@gmail.com') // GANTI dengan email Super Admin Anda
            ->count();
    }
}