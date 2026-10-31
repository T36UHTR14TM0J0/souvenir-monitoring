<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pengeluarans', function (Blueprint $table) {
            $table->string('tipe_alokasi', 30)->default('bisnis')->after('tanggal');
            $table->string('karyawan_nama', 100)->nullable()->after('keterangan');
            $table->string('status_kasbon', 20)->nullable()->after('karyawan_nama');

            $table->index('tipe_alokasi');
            $table->index('status_kasbon');
        });
    }

    public function down(): void
    {
        Schema::table('pengeluarans', function (Blueprint $table) {
            $table->dropIndex(['tipe_alokasi']);
            $table->dropIndex(['status_kasbon']);
            $table->dropColumn(['tipe_alokasi', 'karyawan_nama', 'status_kasbon']);
        });
    }
};
