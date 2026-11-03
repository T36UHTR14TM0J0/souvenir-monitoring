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
                'deskripsi'     => 'Gantungan kunci akrilik anime, standee, charm, dan keychain souvenir custom',
            ],
            [
                'nama_kategori' => 'Action Figure & Nendoroid',
                'deskripsi'     => 'Action figure karakter anime, nendoroid, scale figure, dan collectible statue',
            ],
            [
                'nama_kategori' => 'Kaos & Pakaian Anime',
                'deskripsi'     => 'Kaos distro anime, hoodie zipper, kemeja hawaii anime, dan jaket souvenir',
            ],
            [
                'nama_kategori' => 'Mug, Tumbler & Botol',
                'deskripsi'     => 'Mug keramik sublimasi, tumbler stainless termal, dan botol minum karakter',
            ],
            [
                'nama_kategori' => 'Tas, Totebag & Pouch',
                'deskripsi'     => 'Tote bag kanvas tebal, pouch serbaguna, drawstring bag, dan dompet anime',
            ],
            [
                'nama_kategori' => 'Poster, Art Print & Wallscroll',
                'deskripsi'     => 'Poster A3+ laminasi glossy, postcard art set, dan wallscroll kain gantung',
            ],
            [
                'nama_kategori' => 'Pin, Badge & Aksesoris',
                'deskripsi'     => 'Can badge bulat, pin enamel logam, lanyard tali ID, dan gelang karet',
            ],
            [
                'nama_kategori' => 'Alat Tulis & Notebook',
                'deskripsi'     => 'Buku catatan hardcover, sticker pack tahan air, dan binder anime',
            ],
            [
                'nama_kategori' => 'Bantal & Plushie Souvenir',
                'deskripsi'     => 'Boneka plushie karakter, bantal sofa anime, dan gantungan mini plush',
            ],
            [
                'nama_kategori' => 'Kerajinan & Oleh-oleh Khas',
                'deskripsi'     => 'Kerajinan souvenir ukir, wayang mini, dan cenderamata khas toko',
            ],
        ];

        foreach ($data as $item) {
            Kategori::firstOrCreate(
                ['nama_kategori' => $item['nama_kategori']],
                ['deskripsi' => $item['deskripsi']]
            );
        }

        $this->command->info('✅ KategoriSeeder: 10 Kategori berhasil dibuat/disinkronkan.');
    }
}
