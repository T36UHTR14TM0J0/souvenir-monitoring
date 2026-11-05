<?php

namespace Database\Seeders;

use App\Models\Kategori;
use App\Models\Produk;
use App\Models\Satuan;
use Illuminate\Database\Seeder;

class ProdukSeeder extends Seeder
{
    public function run(): void
    {
        $kategoris = Kategori::all()->keyBy('nama_kategori');
        $satuans = Satuan::all()->keyBy('singkatan');

        $pcs = $satuans['pcs']->id ?? 1;
        $box = $satuans['box']->id ?? $pcs;
        $set = $satuans['set']->id ?? $pcs;

        // DAFTAR PRODUK SOUVENIR JADI (Dijual di Marketplace)
        $produkList = [
            [
                'jenis' => 'souvenir_jadi',
                'kategori' => 'Mug, Tumbler & Botol',
                'nama' => 'Mug Keramik Custom Anime Jibranime (SNI)',
                'satuan_id' => $pcs,
                'harga_beli' => 12500,
                'harga_jual' => 28000,
                'tipe_diskon' => 'none',
                'diskon' => 0,
                'stok' => 120,
                'stok_minimal' => 20,
                'deskripsi' => 'Mug keramik putih cetak sublimasi full color tahan panas dan cuci.',
            ],
            [
                'jenis' => 'souvenir_jadi',
                'kategori' => 'Mug, Tumbler & Botol',
                'nama' => 'Tumbler Stainless Termal Grafir Custom 500ml',
                'satuan_id' => $pcs,
                'harga_beli' => 38000,
                'harga_jual' => 75000,
                'tipe_diskon' => 'persen',
                'diskon' => 10,
                'stok' => 65,
                'stok_minimal' => 15,
                'deskripsi' => 'Tumbler tahan dingin & panas 12 jam, grafir laser nama dan karakter presisi.',
            ],
            [
                'jenis' => 'souvenir_jadi',
                'kategori' => 'Gantungan Kunci & Akrilik',
                'nama' => 'Gantungan Kunci Akrilik 2 Sisi Gojo Satoru 6cm',
                'satuan_id' => $pcs,
                'harga_beli' => 7000,
                'harga_jual' => 18000,
                'tipe_diskon' => 'none',
                'diskon' => 0,
                'stok' => 180,
                'stok_minimal' => 30,
                'deskripsi' => 'Keychain akrilik cetak UV sandwich 2 sisi, ring putar tebal anti karat.',
            ],
            [
                'jenis' => 'souvenir_jadi',
                'kategori' => 'Gantungan Kunci & Akrilik',
                'nama' => 'Gantungan Kunci Akrilik Anya Forger & Bond',
                'satuan_id' => $pcs,
                'harga_beli' => 7000,
                'harga_jual' => 18000,
                'tipe_diskon' => 'none',
                'diskon' => 0,
                'stok' => 150,
                'stok_minimal' => 30,
                'deskripsi' => 'Keychain akrilik chibi karakter Spy x Family berkualitas tinggi.',
            ],
            [
                'jenis' => 'souvenir_jadi',
                'kategori' => 'Gantungan Kunci & Akrilik',
                'nama' => 'Standee Akrilik Meja Frieren 15cm',
                'satuan_id' => $pcs,
                'harga_beli' => 22000,
                'harga_jual' => 55000,
                'tipe_diskon' => 'persen',
                'diskon' => 10,
                'stok' => 75,
                'stok_minimal' => 15,
                'deskripsi' => 'Standee akrilik display meja karakter Frieren: Beyond Journey\'s End.',
            ],
            [
                'jenis' => 'souvenir_jadi',
                'kategori' => 'Gantungan Kunci & Akrilik',
                'nama' => 'Standee Akrilik Sung Jin-Woo Shadow Monarch',
                'satuan_id' => $pcs,
                'harga_beli' => 24000,
                'harga_jual' => 60000,
                'tipe_diskon' => 'none',
                'diskon' => 0,
                'stok' => 60,
                'stok_minimal' => 15,
                'deskripsi' => 'Standee akrilik Solo Leveling efek glow neon tajam.',
            ],
            [
                'jenis' => 'souvenir_jadi',
                'kategori' => 'Pin, Badge & Aksesoris',
                'nama' => 'Pin Button Doff 58mm Karakter Anime Populer',
                'satuan_id' => $pcs,
                'harga_beli' => 2500,
                'harga_jual' => 8000,
                'tipe_diskon' => 'none',
                'diskon' => 0,
                'stok' => 350,
                'stok_minimal' => 50,
                'deskripsi' => 'Pin kaleng peniti laminasi doff halus anti air untuk tas dan jaket.',
            ],
            [
                'jenis' => 'souvenir_jadi',
                'kategori' => 'Pin, Badge & Aksesoris',
                'nama' => 'Lanyard Tali ID Card Anime Printing 2cm + Stopper',
                'satuan_id' => $pcs,
                'harga_beli' => 9000,
                'harga_jual' => 25000,
                'tipe_diskon' => 'none',
                'diskon' => 0,
                'stok' => 110,
                'stok_minimal' => 20,
                'deskripsi' => 'Tali lanyard tisu halus printing 2 sisi karakter anime dilengkapi kait putar.',
            ],
            [
                'jenis' => 'souvenir_jadi',
                'kategori' => 'Plakat & Cinderamata',
                'nama' => 'Plakat Akrilik Custom Grafir / Kenang-kenangan 15x20cm',
                'satuan_id' => $pcs,
                'harga_beli' => 35000,
                'harga_jual' => 85000,
                'tipe_diskon' => 'none',
                'diskon' => 0,
                'stok' => 40,
                'stok_minimal' => 10,
                'deskripsi' => 'Plakat akrilik tebal 5mm dengan dudukan kayu mahoni dan box bludru.',
            ],
            [
                'jenis' => 'souvenir_jadi',
                'kategori' => 'Tas, Totebag & Pouch',
                'nama' => 'Totebag Kanvas Blacu Sablon Anime (Resleting)',
                'satuan_id' => $pcs,
                'harga_beli' => 16000,
                'harga_jual' => 40000,
                'tipe_diskon' => 'none',
                'diskon' => 0,
                'stok' => 95,
                'stok_minimal' => 20,
                'deskripsi' => 'Totebag ukuran 35x40cm bahan kanvas blacu tebal bersaku dan berresleting.',
            ],
            [
                'jenis' => 'souvenir_jadi',
                'kategori' => 'Poster & Art Print',
                'nama' => 'Paket Poster Anime A3+ Premium Laminasi Glossy (Isi 4)',
                'satuan_id' => $set,
                'harga_beli' => 12000,
                'harga_jual' => 32000,
                'tipe_diskon' => 'none',
                'diskon' => 0,
                'stok' => 80,
                'stok_minimal' => 15,
                'deskripsi' => 'Art carton 260gr resolusi ultra HD dilapisi laminasi anti luntur.',
            ],
            [
                'jenis' => 'souvenir_jadi',
                'kategori' => 'Action Figure & Nendoroid',
                'nama' => 'Action Figure Luffy Gear 5 Sun God Nika 20cm',
                'satuan_id' => $box,
                'harga_beli' => 120000,
                'harga_jual' => 235000,
                'tipe_diskon' => 'nominal',
                'diskon' => 15000,
                'stok' => 35,
                'stok_minimal' => 8,
                'deskripsi' => 'Figure detail tinggi Luffy Gear 5 lengkap dengan box kemasan eksklusif.',
            ],
            [
                'jenis' => 'souvenir_jadi',
                'kategori' => 'Action Figure & Nendoroid',
                'nama' => 'Action Figure Roronoa Zoro Santoryu Wano',
                'satuan_id' => $box,
                'harga_beli' => 130000,
                'harga_jual' => 250000,
                'tipe_diskon' => 'none',
                'diskon' => 0,
                'stok' => 28,
                'stok_minimal' => 8,
                'deskripsi' => 'Figure Zoro pose tiga pedang lengkap dengan base effect display.',
            ],
            [
                'jenis' => 'souvenir_jadi',
                'kategori' => 'Kaos & Apparel Souvenir',
                'nama' => 'Kaos Distro Anime Cotton Combed 24s Sablon DTF',
                'satuan_id' => $pcs,
                'harga_beli' => 45000,
                'harga_jual' => 95000,
                'tipe_diskon' => 'none',
                'diskon' => 0,
                'stok' => 85,
                'stok_minimal' => 15,
                'deskripsi' => 'Kaos katun adem sablon digital transfer film elastis dan tidak retak.',
            ],
        ];

        $counter = 1;
        foreach ($produkList as $item) {
            $kat = $kategoris[$item['kategori']] ?? null;
            if (! $kat) {
                continue;
            }

            $kode = 'PRD'.str_pad($counter++, 5, '0', STR_PAD_LEFT);

            Produk::updateOrCreate(
                ['kode_produk' => $kode],
                [
                    'kategori_id' => $kat->id,
                    'satuan_id' => $item['satuan_id'],
                    'jenis' => $item['jenis'],
                    'nama_produk' => $item['nama'],
                    'harga_beli' => $item['harga_beli'],
                    'harga_jual' => $item['harga_jual'],
                    'tipe_diskon' => $item['tipe_diskon'] ?? 'none',
                    'diskon' => $item['diskon'] ?? 0,
                    'stok' => $item['stok'],
                    'stok_minimal' => $item['stok_minimal'],
                    'deskripsi' => $item['deskripsi'],
                ]
            );
        }

        if ($this->command) {
            $this->command->info('ProdukSeeder: 14 Katalog Souvenir Jadi berhasil dibuat/disinkronkan.');
        }
    }
}
