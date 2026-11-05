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
            $table->string('channel', 30)->default('shopee')->after('id');
            $table->string('no_pesanan_shopee', 100)->nullable()->index()->after('kode_transaksi');
            $table->string('no_resi', 100)->nullable()->after('no_pesanan_shopee');
            $table->string('kurir', 50)->nullable()->after('no_resi');
            $table->decimal('biaya_admin_shopee', 15, 2)->default(0)->after('grand_total');
            $table->decimal('penghasilan_bersih', 15, 2)->default(0)->after('biaya_admin_shopee');
            $table->string('status_pesanan', 30)->default('selesai')->after('penghasilan_bersih');
            $table->string('metode_bayar', 50)->default('saldo_penjual')->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('transaksis', function (Blueprint $table) {
            $table->dropIndex(['no_pesanan_shopee']);
            $table->dropColumn([
                'channel',
                'no_pesanan_shopee',
                'no_resi',
                'kurir',
                'biaya_admin_shopee',
                'penghasilan_bersih',
                'status_pesanan',
            ]);
        });
    }
};
