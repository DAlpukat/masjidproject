<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('tempat_layanans', function (Blueprint $table) {
            // Tambahkan kolom status: pending, approved, rejected
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
            // Tambahkan kolom is_public: true untuk publik, false untuk privat
            $table->boolean('is_public')->default(true);
            // Tambahkan kolom access_code untuk kelas privat
            $table->string('access_code')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('tempat_layanans', function (Blueprint $table) {
            $table->dropColumn(['status', 'is_public', 'access_code']);
        });
    }
};