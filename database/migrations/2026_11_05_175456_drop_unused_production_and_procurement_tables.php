<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Drop detail/pivot tables first to satisfy foreign key constraints
        Schema::dropIfExists('pemakaian_bahan_details');
        Schema::dropIfExists('penerimaan_produk_details');
        Schema::dropIfExists('pembelian_details');
        Schema::dropIfExists('produk_resep');

        // 2. Drop parent tables
        Schema::dropIfExists('pemakaian_bahans');
        Schema::dropIfExists('penerimaan_produks');
        Schema::dropIfExists('pembelians');
    }

    public function down(): void
    {
        // No reverse operation needed as client permanently pivoted away from production management
    }
};
