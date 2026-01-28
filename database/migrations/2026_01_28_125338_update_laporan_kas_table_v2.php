<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('laporan_kas', function (Blueprint $table) {
            $table->foreignId('kategori_kas_id')->nullable()->constrained()->onDelete('set null');
            $table->enum('tipe_pengeluaran', ['shared', 'free'])->nullable()->after('jenis');
        });
    }

    public function down(): void
    {
        Schema::table('laporan_kas', function (Blueprint $table) {
            $table->dropColumn(['kategori_kas_id', 'tipe_pengeluaran']);
        });
    }
};