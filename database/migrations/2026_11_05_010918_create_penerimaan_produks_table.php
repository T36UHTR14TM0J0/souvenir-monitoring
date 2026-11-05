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
        Schema::create('penerimaan_produks', function (Blueprint $table) {
            $table->id();
            $table->string('kode_penerimaan', 50)->unique()->index();
            $table->date('tanggal');
            $table->string('nama_petugas', 150);
            $table->string('tipe_produksi', 50)->default('ready_stock');
            $table->string('no_referensi_pemakaian', 100)->nullable();
            $table->unsignedInteger('total_item')->default(0);
            $table->decimal('total_qty_bagus', 10, 2)->default(0);
            $table->decimal('total_qty_reject', 10, 2)->default(0);
            $table->text('catatan')->nullable();
            $table->string('bukti_dokumen', 255)->nullable();
            $table->foreignId('user_id')->constrained('users');
            $table->timestamps();
        });

        Schema::create('penerimaan_produk_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('penerimaan_produk_id')->constrained('penerimaan_produks')->cascadeOnDelete();
            $table->foreignId('produk_id')->constrained('produks');
            $table->string('nama_produk', 255);
            $table->decimal('qty_bagus', 10, 2);
            $table->decimal('qty_reject', 10, 2)->default(0);
            $table->string('satuan', 50)->nullable();
            $table->string('keterangan', 255)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('penerimaan_produk_details');
        Schema::dropIfExists('penerimaan_produks');
    }
};
