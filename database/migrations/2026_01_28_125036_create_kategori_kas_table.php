<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('kategori_kas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tempat_layanan_id')->constrained()->onDelete('cascade');
            
            // Nama Kategori: "Kas Wajib Bulanan", "Infaq Jumat", "Donasi Pembangunan"
            $table->string('nama'); 
            
            // Tipe Kategori
            $table->enum('tipe', ['wajib', 'sedekah'])->default('sedekah'); // wajib = Mandatory, sedekah = Voluntary
            
            // Aturan Saldo
            $table->boolean('allow_negative')->default(false); // Apakah boleh minus (tunggakan)?
            $table->decimal('target_amount', 15, 2)->nullable(); // Target periodik (misal: 50rb/bulan)
            
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('kategori_kas');
    }
};