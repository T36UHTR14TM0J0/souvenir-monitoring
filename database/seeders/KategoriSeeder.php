<?php

namespace Database\Seeders;

use App\Models\Kategori;
use Illuminate\Database\Seeder;

class KategoriSeeder extends Seeder
{
    public function run(): void
    {
        $data = [
            ['nama_kategori' => 'Kaos & Pakaian',    'deskripsi' => 'Kaos, kemeja, dan pakaian souvenir lainnya'],
            ['nama_kategori' => 'Gantungan Kunci',   'deskripsi' => 'Gantungan kunci akrilik, kayu, dan logam'],
            ['nama_kategori' => 'Mug & Tumbler',     'deskripsi' => 'Mug keramik, tumbler, dan botol minum'],
            ['nama_kategori' => 'Tas & Dompet',      'deskripsi' => 'Tote bag, pouch, dan dompet souvenir'],
            ['nama_kategori' => 'Aksesoris',         'deskripsi' => 'Gelang, kalung, dan aksesoris lainnya'],
            ['nama_kategori' => 'Kerajinan Tangan',  'deskripsi' => 'Produk handmade khas daerah'],
            ['nama_kategori' => 'Makanan Khas',      'deskripsi' => 'Oleh-oleh dan makanan khas daerah'],
            ['nama_kategori' => 'Elektronik',        'deskripsi' => 'USB, powerbank, dan aksesoris elektronik'],
        ];

        foreach ($data as $item) {
            Kategori::create($item);
        }
    }
}