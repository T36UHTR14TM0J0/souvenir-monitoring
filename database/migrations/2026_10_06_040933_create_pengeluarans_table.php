<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pengeluarans', function (Blueprint $table) {
            $table->id();
            $table->string('kode_pengeluaran', 50)->unique();
            $table->date('tanggal');
            $table->string('kategori', 50);
            $table->string('keterangan', 255);
            $table->decimal('jumlah', 15, 2);
            $table->text('catatan')->nullable();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->timestamps();

            $table->index('tanggal');
            $table->index('kategori');
            $table->index('kode_pengeluaran');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pengeluarans');
    }
};