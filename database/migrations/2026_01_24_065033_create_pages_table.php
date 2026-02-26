<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('pages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tempat_layanan_id')->constrained()->onDelete('cascade'); // Punya room mana
            $table->string('judul'); // Nama Tab (ex: "Kas Wajib", "Daftar Barang")
            $table->enum('tipe', ['kas', 'barang_pinjam', 'info'])->default('info'); // Tipe Kontennya
            $table->integer('urutan')->default(0); // Urutan di header
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pages');
    }
};
