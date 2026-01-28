<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('user_kas_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('kategori_kas_id')->constrained('kategori_kas')->onDelete('cascade');
            $table->foreignId('laporan_kas_id')->nullable()->constrained()->onDelete('set null'); // Referensi ke transaksi induk
            
            // Jenis perubahan: 'masuk' (bayar kas), 'keluar' (distribusi pengeluaran bersama)
            $table->enum('jenis', ['masuk', 'keluar'])->default('masuk'); 
            
            $table->decimal('jumlah', 15, 2); // Selalu positif, jenis ditentukan kolom 'jenis'
            $table->text('keterangan')->nullable();
            $table->timestamps();
            
            // Index agar cepat saat hitung saldo
            $table->index(['user_id', 'kategori_kas_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('user_kas_logs');
    }
};