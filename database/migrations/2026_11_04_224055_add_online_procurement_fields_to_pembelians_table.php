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
        Schema::table('pembelians', function (Blueprint $table) {
            $table->string('platform', 50)->default('shopee')->after('supplier')->comment('shopee, tokopedia, tiktok, lazada, website, offline');
            $table->string('no_invoice_online', 100)->nullable()->after('platform');
            $table->decimal('ongkir', 15, 2)->default(0)->after('total_diskon');
            $table->string('status_pembelian', 30)->default('diterima')->after('grand_total')->comment('dipesan, diterima');
            $table->string('kurir_kargo', 50)->nullable()->after('status_pembelian');
            $table->string('no_resi_kargo', 100)->nullable()->after('kurir_kargo');

            $table->index('platform');
            $table->index('status_pembelian');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pembelians', function (Blueprint $table) {
            $table->dropIndex(['platform']);
            $table->dropIndex(['status_pembelian']);
            $table->dropColumn([
                'platform',
                'no_invoice_online',
                'ongkir',
                'status_pembelian',
                'kurir_kargo',
                'no_resi_kargo',
            ]);
        });
    }
};
