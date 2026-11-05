<?php

namespace Database\Factories;

use App\Models\Kategori;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProdukFactory extends Factory
{
    public function definition(): array
    {
        $hargaBeli = $this->faker->numberBetween(5, 200) * 1000;
        $hargaJual = $hargaBeli + ($this->faker->numberBetween(20, 50) * 100) * 100;

        static $counter = 1;

        return [
            'kategori_id' => Kategori::inRandomOrder()->first()?->id ?? Kategori::factory(),
            'kode_produk' => 'PRD'.str_pad($counter++, 5, '0', STR_PAD_LEFT),
            'nama_produk' => $this->faker->words(3, true),
            'harga_beli' => $hargaBeli,
            'harga_jual' => $hargaJual,
            'stok' => $this->faker->numberBetween(0, 100),
            'stok_minimal' => 5,
            'deskripsi' => $this->faker->sentence(10),
        ];
    }
}
