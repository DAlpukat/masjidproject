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
        Schema::create('tempat_layanans', function (Blueprint $table) {
            $table->id();
            $table->string('nama'); // Nama Masjid/Gereja/Sekolah
            $table->string('slug')->unique(); // Untuk URL (ex: masjid-alhidayah) & QR Code
            $table->text('deskripsi')->nullable();
            $table->enum('status', ['pending', 'aktif', 'nonaktif'])->default('pending'); // Status validasi admin
            $table->boolean('is_public')->default(true); // True = Terbuka, False = Tertutup
            $table->string('kode_referral')->nullable(); // Kode akses kalau tertutup
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // Pengurusnya
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tempat_layanans');
    }
};
