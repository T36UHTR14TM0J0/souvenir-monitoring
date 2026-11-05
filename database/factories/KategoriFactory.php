<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class KategoriFactory extends Factory
{
    public function definition(): array
    {
        $kategoris = [
            'Kaos & Pakaian',
            'Gantungan Kunci',
            'Mug & Tumbler',
            'Tas & Dompet',
            'Aksesoris',
            'Kerajinan Tangan',
            'Makanan Khas',
            'Elektronik',
        ];

        return [
            'nama_kategori' => $this->faker->unique()->randomElement($kategoris),
            'deskripsi' => $this->faker->sentence(8),
        ];
    }
}
