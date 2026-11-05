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
            $table->renameColumn('no_pesanan_shopee', 'no_pesanan_marketplace');
            $table->renameColumn('biaya_admin_shopee', 'biaya_admin_marketplace');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('transaksis', function (Blueprint $table) {
            $table->renameColumn('no_pesanan_marketplace', 'no_pesanan_shopee');
            $table->renameColumn('biaya_admin_marketplace', 'biaya_admin_shopee');
        });
    }
};
