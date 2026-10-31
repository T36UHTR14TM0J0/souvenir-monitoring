<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pembelians', function (Blueprint $table) {
            $table->id();
            $table->string('kode_pembelian', 50)->unique();
            $table->date('tanggal');
            $table->string('supplier', 150)->nullable();
            $table->decimal('total_harga', 15, 2)->default(0);
            $table->decimal('total_diskon', 15, 2)->default(0);
            $table->decimal('grand_total', 15, 2)->default(0);
            $table->text('catatan')->nullable();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->timestamps();

            $table->index('tanggal');
            $table->index('kode_pembelian');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pembelians');
    }
};