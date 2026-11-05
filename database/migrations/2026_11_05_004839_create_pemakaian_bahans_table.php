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
        Schema::create('pemakaian_bahans', function (Blueprint $table) {
            $table->id();
            $table->string('kode_pemakaian', 50)->unique()->index();
            $table->date('tanggal');
            $table->string('nama_operator', 150);
            $table->string('keperluan', 255);
            $table->unsignedInteger('total_item')->default(0);
            $table->decimal('total_kuantitas', 10, 2)->default(0);
            $table->text('catatan')->nullable();
            $table->string('bukti_pengambilan', 255)->nullable();
            $table->foreignId('user_id')->constrained('users');
            $table->timestamps();
        });

        Schema::create('pemakaian_bahan_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pemakaian_bahan_id')->constrained('pemakaian_bahans')->cascadeOnDelete();
            $table->foreignId('produk_id')->constrained('produks');
            $table->string('nama_bahan', 255);
            $table->decimal('qty', 10, 2);
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
        Schema::dropIfExists('pemakaian_bahan_details');
        Schema::dropIfExists('pemakaian_bahans');
    }
};
