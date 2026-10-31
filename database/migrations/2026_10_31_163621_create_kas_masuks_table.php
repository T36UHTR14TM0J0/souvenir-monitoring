<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('kas_masuks', function (Blueprint $table) {
            $table->id();
            $table->string('kode_transaksi', 50)->unique();
            $table->date('tanggal');
            $table->string('tipe', 50)->default('modal_pemilik'); // modal_pemilik, pendapatan_lain, dll
            $table->string('sumber', 100)->default('Debit / Transfer Pribadi'); // Debit / Transfer, Cash / Tunai
            $table->decimal('jumlah', 15, 2);
            $table->string('keterangan', 255);
            $table->text('catatan')->nullable();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->timestamps();

            $table->index('tanggal');
            $table->index('tipe');
            $table->index('kode_transaksi');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('kas_masuks');
    }
};
