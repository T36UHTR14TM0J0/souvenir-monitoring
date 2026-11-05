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
        Schema::table('transaksis', function (Blueprint $table) {
            $table->boolean('bahan_baku_dipotong')->default(false)->after('status_pesanan');
            $table->timestamp('waktu_potong_bahan')->nullable()->after('bahan_baku_dipotong');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('transaksis', function (Blueprint $table) {
            $table->dropColumn(['bahan_baku_dipotong', 'waktu_potong_bahan']);
        });
    }
};
