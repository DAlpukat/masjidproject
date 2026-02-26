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
        Schema::create('items', function (Blueprint $table) {
            $table->id();
            // Menghubungkan barang ke halaman (page) tertentu
            $table->foreignId('page_id')->constrained()->onDelete('cascade');
            $table->string('nama_barang');
            $table->integer('stok_total')->default(0);
            $table->integer('stok_tersedia')->default(0);
            $table->string('gambar')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('items');
    }
};
