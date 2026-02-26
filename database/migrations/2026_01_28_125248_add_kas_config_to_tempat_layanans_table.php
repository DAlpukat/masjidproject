<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('tempat_layanans', function (Blueprint $table) {
            $table->boolean('use_individual_ledger')->default(false);
            $table->boolean('use_mandatory_cash')->default(false);
            $table->boolean('shared_expense_enabled')->default(true);
            $table->boolean('free_expense_enabled')->default(true);
        });
    }

    public function down(): void
    {
        Schema::table('tempat_layanans', function (Blueprint $table) {
            $table->dropColumn([
                'use_individual_ledger',
                'use_mandatory_cash',
                'shared_expense_enabled',
                'free_expense_enabled'
            ]);
        });
    }
};