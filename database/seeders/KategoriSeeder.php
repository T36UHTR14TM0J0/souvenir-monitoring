<?php

namespace Database\Seeders;

use App\Models\Kategori;
use Illuminate\Database\Seeder;

class KategoriSeeder extends Seeder
{
    public function run(): void
    {
        $data = [
            [
                'nama_kategori' => 'Gantungan Kunci & Akrilik',
                'deskripsi' => 'Gantungan kunci akrilik anime, standee, charm, dan keychain souvenir custom',
            ],
            [
                'nama_kategori' => 'Mug, Tumbler & Botol',
                'deskripsi' => 'Mug keramik sublimasi, tumbler stainless termal, dan botol minum karakter',
            ],
            [
                'nama_kategori' => 'Pin, Badge & Aksesoris',
                'deskripsi' => 'Can badge bulat, pin enamel logam, lanyard tali ID, dan gantungan tas',
            ],
            [
                'nama_kategori' => 'Plakat & Cinderamata',
                'deskripsi' => 'Plakat akrilik custom grafir, piala kayu, dan souvenir kenang-kenangan',
            ],
            [
                'nama_kategori' => 'Tas, Totebag & Pouch',
                'deskripsi' => 'Tote bag kanvas tebal sablon, pouch serbaguna, dan drawstring bag souvenir',
            ],
            [
                'nama_kategori' => 'Action Figure & Nendoroid',
                'deskripsi' => 'Action figure karakter anime, nendoroid, scale figure, dan collectible statue',
            ],
            [
                'nama_kategori' => 'Kaos & Apparel Souvenir',
                'deskripsi' => 'Kaos distro anime sablon DTF, hoodie zipper, dan seragam merchandise',
            ],
            [
                'nama_kategori' => 'Poster & Art Print',
                'deskripsi' => 'Poster A3+ laminasi glossy, postcard art set, dan wallscroll gantung',
            ],
            [
                'nama_kategori' => 'Bahan Baku Produksi',
                'deskripsi' => 'Mug polos keramik, lembaran akrilik, ring gantungan, blank pin, dan tinta cetak',
            ],
            [
                'nama_kategori' => 'Bahan Packing & Pengiriman',
                'deskripsi' => 'Kardus packaging die cut, bubble wrap roll, lakban fragile, dan polymailer',
            ],
            [
                'nama_kategori' => 'Stiker & Decal Vinyl',
                'deskripsi' => 'Stiker die cut vinyl tahan air, kiss cut sticker sheet, dan hologram laminasi',
            ],
            [
                'nama_kategori' => 'Alat Tulis & Buku Catatan',
                'deskripsi' => 'Pulpen souvenir promosi, notebook spiral custom, pembatas buku, dan memo pad',
            ],
            [
                'nama_kategori' => 'Topi & Aksesoris Kepala',
                'deskripsi' => 'Topi snapback sablon/bordir, bucket hat merchandise, dan bandana karakter',
            ],
            [
                'nama_kategori' => 'Kemasan Souvenir & Hardbox',
                'deskripsi' => 'Hardbox kado eksklusif, pita satin souvenir, dan kantong spunbond eco-friendly',
            ],
        ];

        foreach ($data as $item) {
            Kategori::firstOrCreate(
                ['nama_kategori' => $item['nama_kategori']],
                ['deskripsi' => $item['deskripsi']]
            );
        }

        $this->command->info('KategoriSeeder: '.count($data).' Kategori Souvenir & Bahan Baku berhasil dibuat.');
    }
}
