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
        Schema::create('laporan_kas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tempat_layanan_id')->constrained()->onDelete('cascade'); // Milik tempat mana
            
            // Detail Transaksi
            $table->date('tanggal');
            $table->string('keterangan'); // Barang/Jasa
            $table->enum('jenis', ['masuk', 'keluar'])->default('keluar'); // Pemasukan atau Pengeluaran
            $table->decimal('jumlah', 15, 2); // Besaran uang
            
            // Bukti/Foto (Kita simpan nama filenya saja)
            $table->string('bukti_foto')->nullable(); // Foto nota
            $table->string('output_foto')->nullable(); // Foto hasil barang/jasa
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('laporan_kas');
    }
};
