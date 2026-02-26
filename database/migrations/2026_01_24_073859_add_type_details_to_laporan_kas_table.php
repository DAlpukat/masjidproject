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
        Schema::table('laporan_kas', function (Blueprint $table) {
            // Tipe uang: Personal (Milik user) atau Umum (Sedekah/Dana Sosial)
            $table->enum('sifat_transaksi', ['personal', 'umum'])->default('umum')->after('jenis');
            
            // Siapa user yang bayar/hutang? (Kosongkan jika Umum)
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('set null')->after('sifat_transaksi');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('laporan_kas', function (Blueprint $table) {
            //
        });
    }
};
