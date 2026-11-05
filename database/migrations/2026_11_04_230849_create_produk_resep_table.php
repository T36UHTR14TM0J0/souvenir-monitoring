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
        Schema::create('produk_resep', function (Blueprint $table) {
            $table->id();
            $table->foreignId('produk_id')->constrained('produks')->cascadeOnDelete();
            $table->foreignId('bahan_baku_id')->constrained('produks')->cascadeOnDelete();
            $table->decimal('jumlah', 10, 2)->default(1);
            $table->string('keterangan', 255)->nullable();
            $table->timestamps();

            $table->unique(['produk_id', 'bahan_baku_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('produk_resep');
    }
};
