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
        Schema::table('produks', function (Blueprint $table) {
            $table->enum('tipe_diskon', ['none', 'persen', 'nominal'])
                ->default('none')
                ->after('harga_jual');
            $table->decimal('diskon', 15, 2)
                ->default(0)
                ->after('tipe_diskon');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('produks', function (Blueprint $table) {
            $table->dropColumn(['tipe_diskon', 'diskon']);
        });
    }
};
