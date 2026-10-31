<?php

namespace Database\Seeders;

use App\Models\Kategori;
use App\Models\Produk;
use Illuminate\Database\Seeder;

class ProdukSeeder extends Seeder
{
    public function run(): void
    {
        // Data dummy realistik per kategori
        $produkData = [
            'Kaos & Pakaian' => [
                ['Kaos Souvenir "I Love Indonesia"', 35000, 65000,  50],
                ['Kaos Polos Premium Cotton',        42000, 75000,  80],
                ['Kemeja Batik Modern',             120000, 185000, 25],
                ['Jaket Hoodie Custom',             155000, 245000, 15],
            ],
            'Gantungan Kunci' => [
                ['Gantungan Kunci Akrilik Custom',    5000,  15000, 200],
                ['Gantungan Kunci Kayu Ukir',        12000,  28000, 100],
                ['Gantungan Kunci Logam',            15000,  35000,  75],
                ['Gantungan Kunci Kulit',            18000,  40000,  40],
            ],
            'Mug & Tumbler' => [
                ['Mug Keramik Custom Print',         25000,  55000,  60],
                ['Mug Magic Heat Sensitive',         40000,  85000,  30],
                ['Tumbler Stainless 500ml',          65000, 120000,  45],
                ['Botol Minum Sport',                45000,  90000,  50],
            ],
            'Tas & Dompet' => [
                ['Tote Bag Canvas',                  30000,  65000,  70],
                ['Pouch Souvenir',                   20000,  45000,  90],
                ['Dompet Kulit Handmade',            85000, 150000,  20],
                ['Sling Bag Kanvas',                 55000, 110000,  35],
            ],
            'Aksesoris' => [
                ['Gelang Tali Custom',                5000,  15000, 150],
                ['Kalung Monel Ukir',                35000,  75000,  30],
                ['Bros Kain Batik',                  15000,  35000,  50],
                ['Cincin Perak Ukir',               120000, 220000,  10],
            ],
            'Kerajinan Tangan' => [
                ['Anyaman Bambu Hias',               25000,  55000,  40],
                ['Ukiran Kayu Miniatur',             75000, 145000,  15],
                ['Batik Tulis Mini Frame',          150000, 275000,   8],
                ['Wayang Kulit Mini',                85000, 165000,  12],
            ],
            'Makanan Khas' => [
                ['Keripik Singkong Balado 250gr',    12000,  25000, 100],
                ['Kopi Robusta Lokal 200gr',         35000,  65000,  50],
                ['Kue Kering Nastar 500gr',          55000,  95000,  30],
                ['Dodol Khas Daerah',                18000,  38000,  60],
            ],
            'Elektronik' => [
                ['USB Flashdisk Custom 16GB',        45000,  85000,  40],
                ['Powerbank 10.000 mAh',             95000, 175000,  25],
                ['Kabel Data Type-C',                15000,  35000,  80],
                ['Speaker Bluetooth Mini',          120000, 210000,  15],
            ],
        ];

        $kodeCounter = 1;

        foreach ($produkData as $namaKategori => $produks) {
            $kategori = Kategori::where('nama_kategori', $namaKategori)->first();

            if (!$kategori) {
                continue;
            }

            foreach ($produks as $p) {
                Produk::create([
                    'kategori_id'  => $kategori->id,
                    'kode_produk'  => 'PRD' . str_pad($kodeCounter++, 5, '0', STR_PAD_LEFT),
                    'nama_produk'  => $p[0],
                    'harga_beli'   => $p[1],
                    'harga_jual'   => $p[2],
                    'stok'         => $p[3],
                    'stok_minimal' => 5,
                    'deskripsi'    => 'Produk souvenir kategori ' . $namaKategori,
                ]);
            }
        }
    }
}