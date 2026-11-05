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
        if (Schema::hasTable('transaksis') && ! Schema::hasColumn('transaksis', 'bukti_transaksi')) {
            Schema::table('transaksis', function (Blueprint $table) {
                $table->string('bukti_transaksi')->nullable()->after('catatan');
            });
        }

        if (Schema::hasTable('pembelians') && ! Schema::hasColumn('pembelians', 'bukti_transaksi')) {
            Schema::table('pembelians', function (Blueprint $table) {
                $table->string('bukti_transaksi')->nullable()->after('catatan');
            });
        }

        if (Schema::hasTable('pengeluarans') && ! Schema::hasColumn('pengeluarans', 'bukti_transaksi')) {
            Schema::table('pengeluarans', function (Blueprint $table) {
                $table->string('bukti_transaksi')->nullable()->after('catatan');
            });
        }

        if (Schema::hasTable('kas_masuks') && ! Schema::hasColumn('kas_masuks', 'bukti_transaksi')) {
            Schema::table('kas_masuks', function (Blueprint $table) {
                $table->string('bukti_transaksi')->nullable()->after('catatan');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('transaksis') && Schema::hasColumn('transaksis', 'bukti_transaksi')) {
            Schema::table('transaksis', function (Blueprint $table) {
                $table->dropColumn('bukti_transaksi');
            });
        }

        if (Schema::hasTable('pembelians') && Schema::hasColumn('pembelians', 'bukti_transaksi')) {
            Schema::table('pembelians', function (Blueprint $table) {
                $table->dropColumn('bukti_transaksi');
            });
        }

        if (Schema::hasTable('pengeluarans') && Schema::hasColumn('pengeluarans', 'bukti_transaksi')) {
            Schema::table('pengeluarans', function (Blueprint $table) {
                $table->dropColumn('bukti_transaksi');
            });
        }

        if (Schema::hasTable('kas_masuks') && Schema::hasColumn('kas_masuks', 'bukti_transaksi')) {
            Schema::table('kas_masuks', function (Blueprint $table) {
                $table->dropColumn('bukti_transaksi');
            });
        }
    }
};
