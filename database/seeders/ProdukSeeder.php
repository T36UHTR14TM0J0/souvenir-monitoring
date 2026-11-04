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
        // Cache kategori & satuan untuk lookup cepat
        $kategoris = Kategori::all()->keyBy('nama_kategori');
        $satuans = Satuan::all()->keyBy('singkatan');

        $pcs = $satuans['pcs']->id ?? 1;
        $box = $satuans['box']->id ?? $pcs;
        $pck = $satuans['pck']->id ?? $pcs;
        $lbr = $satuans['lbr']->id ?? $pcs;
        $set = $satuans['set']->id ?? $pcs;
        $btl = $satuans['btl']->id ?? $pcs;

        // Daftar produk komprehensif
        $produkList = [
            // 1. Gantungan Kunci & Akrilik
            [
                'kategori' => 'Gantungan Kunci & Akrilik',
                'nama' => 'Gantungan Kunci Akrilik Gojo Satoru 6cm',
                'satuan_id' => $pcs,
                'harga_beli' => 8000,
                'harga_jual' => 20000,
                'stok' => 85,
                'stok_minimal' => 15,
                'deskripsi' => 'Gantungan kunci akrilik 2 sisi laminasi glossy UV, ring putar tebal.',
            ],
            [
                'kategori' => 'Gantungan Kunci & Akrilik',
                'nama' => 'Gantungan Kunci Akrilik Anya Forger & Bond',
                'satuan_id' => $pcs,
                'harga_beli' => 8000,
                'harga_jual' => 20000,
                'stok' => 90,
                'stok_minimal' => 15,
                'deskripsi' => 'Keychain akrilik chibi karakter Spy x Family berkualitas tinggi.',
            ],
            [
                'kategori' => 'Gantungan Kunci & Akrilik',
                'nama' => 'Standee Akrilik Frieren 15cm',
                'satuan_id' => $pcs,
                'harga_beli' => 25000,
                'harga_jual' => 55000,
                'tipe_diskon' => 'persen',
                'diskon' => 10,
                'stok' => 60,
                'stok_minimal' => 10,
                'deskripsi' => 'Standee akrilik meja karakter Frieren: Beyond Journey\'s End.',
            ],
            [
                'kategori' => 'Gantungan Kunci & Akrilik',
                'nama' => 'Standee Akrilik Sung Jin-Woo Shadow Monarch',
                'satuan_id' => $pcs,
                'harga_beli' => 28000,
                'harga_jual' => 60000,
                'stok' => 50,
                'stok_minimal' => 10,
                'deskripsi' => 'Standee akrilik Solo Leveling efek shadow neon glow.',
            ],

            // 2. Action Figure & Nendoroid
            [
                'kategori' => 'Action Figure & Nendoroid',
                'nama' => 'Action Figure Luffy Gear 5 Sun God Nika 20cm',
                'satuan_id' => $box,
                'harga_beli' => 120000,
                'harga_jual' => 235000,
                'tipe_diskon' => 'nominal',
                'diskon' => 20000,
                'stok' => 35,
                'stok_minimal' => 8,
                'deskripsi' => 'Figure detail tinggi Luffy Gear 5 lengkap dengan box kemasan eksklusif.',
            ],
            [
                'kategori' => 'Action Figure & Nendoroid',
                'nama' => 'Action Figure Roronoa Zoro Santoryu Wano',
                'satuan_id' => $box,
                'harga_beli' => 130000,
                'harga_jual' => 250000,
                'stok' => 30,
                'stok_minimal' => 8,
                'deskripsi' => 'Figure Zoro pose tiga pedang lengkap dengan base effect display.',
            ],
            [
                'kategori' => 'Action Figure & Nendoroid',
                'nama' => 'Nendoroid Tanjiro Kamado Demon Slayer',
                'satuan_id' => $box,
                'harga_beli' => 165000,
                'harga_jual' => 325000,
                'stok' => 25,
                'stok_minimal' => 5,
                'deskripsi' => 'Nendoroid artikulasi Tanjiro dengan 3 faceplate dan efek pernapasan air.',
            ],
            [
                'kategori' => 'Action Figure & Nendoroid',
                'nama' => 'Figure Sukuna Jujutsu Kaisen Malevolent Shrine',
                'satuan_id' => $box,
                'harga_beli' => 145000,
                'harga_jual' => 285000,
                'stok' => 20,
                'stok_minimal' => 5,
                'deskripsi' => 'Figure Ryomen Sukuna duduk di takhta tengkorak.',
            ],

            // 3. Kaos & Pakaian Anime
            [
                'kategori' => 'Kaos & Pakaian Anime',
                'nama' => 'Kaos Oversize Attack on Titan Eren Yeager',
                'satuan_id' => $pcs,
                'harga_beli' => 45000,
                'harga_jual' => 95000,
                'tipe_diskon' => 'persen',
                'diskon' => 15,
                'stok' => 65,
                'stok_minimal' => 12,
                'deskripsi' => 'Bahan Cotton Combed 24s heavy weight, sablon DTF anti-luntur.',
            ],
            [
                'kategori' => 'Kaos & Pakaian Anime',
                'nama' => 'Kaos Distro Jujutsu Kaisen Domain Expansion',
                'satuan_id' => $pcs,
                'harga_beli' => 42000,
                'harga_jual' => 88000,
                'stok' => 70,
                'stok_minimal' => 12,
                'deskripsi' => 'Kaos hitam katun 30s lembut, sablon raster detail halus.',
            ],
            [
                'kategori' => 'Kaos & Pakaian Anime',
                'nama' => 'Hoodie Zipper Demon Slayer Kimetsu Corps',
                'satuan_id' => $pcs,
                'harga_beli' => 95000,
                'harga_jual' => 195000,
                'stok' => 35,
                'stok_minimal' => 8,
                'deskripsi' => 'Bahan fleece tebal hangat dengan bordir logo Kisatsutai di punggung.',
            ],
            [
                'kategori' => 'Kaos & Pakaian Anime',
                'nama' => 'Kemeja Hawaii Anime One Piece Joyboy',
                'satuan_id' => $pcs,
                'harga_beli' => 65000,
                'harga_jual' => 135000,
                'stok' => 40,
                'stok_minimal' => 8,
                'deskripsi' => 'Bahan rayon adem printed motif awan Gear 5 casual.',
            ],

            // 4. Mug, Tumbler & Botol
            [
                'kategori' => 'Mug, Tumbler & Botol',
                'nama' => 'Tumbler Stainless LED Suhu Jujutsu Kaisen 500ml',
                'satuan_id' => $btl,
                'harga_beli' => 45000,
                'harga_jual' => 95000,
                'tipe_diskon' => 'nominal',
                'diskon' => 10000,
                'stok' => 45,
                'stok_minimal' => 10,
                'deskripsi' => 'Tumbler vakum tahan panas/dingin 12 jam dengan display suhu touch screen.',
            ],
            [
                'kategori' => 'Mug, Tumbler & Botol',
                'nama' => 'Mug Keramik Custom Naruto x Sasuke',
                'satuan_id' => $pcs,
                'harga_beli' => 18000,
                'harga_jual' => 45000,
                'stok' => 60,
                'stok_minimal' => 10,
                'deskripsi' => 'Mug keramik putih standar SNI kualitas sublimasi tajam.',
            ],
            [
                'kategori' => 'Mug, Tumbler & Botol',
                'nama' => 'Mug Magic Heat Reactive Attack on Titan',
                'satuan_id' => $pcs,
                'harga_beli' => 28000,
                'harga_jual' => 65000,
                'stok' => 40,
                'stok_minimal' => 8,
                'deskripsi' => 'Mug berubah gambar saat dituangkan air panas.',
            ],

            // 5. Tas, Totebag & Pouch
            [
                'kategori' => 'Tas, Totebag & Pouch',
                'nama' => 'Totebag Kanvas Resleting Genshin Impact',
                'satuan_id' => $pcs,
                'harga_beli' => 25000,
                'harga_jual' => 55000,
                'stok' => 75,
                'stok_minimal' => 15,
                'deskripsi' => 'Kanvas tebal premium dengan furing dalam dan resleting penutup.',
            ],
            [
                'kategori' => 'Tas, Totebag & Pouch',
                'nama' => 'Pouch Serbaguna Spy x Family Anya Waku Waku',
                'satuan_id' => $pcs,
                'harga_beli' => 15000,
                'harga_jual' => 35000,
                'stok' => 80,
                'stok_minimal' => 15,
                'deskripsi' => 'Pouch kosmetik / alat tulis bahan kanvas water-repellent.',
            ],
            [
                'kategori' => 'Tas, Totebag & Pouch',
                'nama' => 'Drawstring Bag Anime Haikyuu Karasuno',
                'satuan_id' => $pcs,
                'harga_beli' => 20000,
                'harga_jual' => 45000,
                'stok' => 55,
                'stok_minimal' => 10,
                'deskripsi' => 'Tas serut olahraga bahan taslan tahan cipratan air.',
            ],

            // 6. Poster, Art Print & Wallscroll
            [
                'kategori' => 'Poster, Art Print & Wallscroll',
                'nama' => 'Poster A3+ Art Paper 260gr Chainsaw Man Glossy',
                'satuan_id' => $lbr,
                'harga_beli' => 4000,
                'harga_jual' => 15000,
                'stok' => 140,
                'stok_minimal' => 20,
                'deskripsi' => 'Poster cetak digital indigo anti-pudar resolusi 300dpi.',
            ],
            [
                'kategori' => 'Poster, Art Print & Wallscroll',
                'nama' => 'Poster A3+ Bleach Thousand-Year Blood War',
                'satuan_id' => $lbr,
                'harga_beli' => 4000,
                'harga_jual' => 15000,
                'stok' => 120,
                'stok_minimal' => 20,
                'deskripsi' => 'Poster laminasi doff elegan karakter Ichigo Kurosaki.',
            ],
            [
                'kategori' => 'Poster, Art Print & Wallscroll',
                'nama' => 'Wallscroll Kain Gantung One Piece Wanted Poster 60x90cm',
                'satuan_id' => $pcs,
                'harga_beli' => 40000,
                'harga_jual' => 85000,
                'stok' => 45,
                'stok_minimal' => 8,
                'deskripsi' => 'Hiasan dinding bahan kain kanvas tebal dengan tiang gantung ganda.',
            ],

            // 7. Pin, Badge & Aksesoris
            [
                'kategori' => 'Pin, Badge & Aksesoris',
                'nama' => 'Pin Enamel Metal Oshi no Ko Ai Hoshino',
                'satuan_id' => $pcs,
                'harga_beli' => 12000,
                'harga_jual' => 30000,
                'stok' => 95,
                'stok_minimal' => 15,
                'deskripsi' => 'Pin logam enamel berkilau dengan clasp karet pengunci ganda.',
            ],
            [
                'kategori' => 'Pin, Badge & Aksesoris',
                'nama' => 'Lanyard Tali ID Card Anime + Card Holder',
                'satuan_id' => $pcs,
                'harga_beli' => 10000,
                'harga_jual' => 25000,
                'stok' => 110,
                'stok_minimal' => 20,
                'deskripsi' => 'Lanyard printing 2 sisi bahan tisu halus lengkap dengan casing kartu.',
            ],
            [
                'kategori' => 'Pin, Badge & Aksesoris',
                'nama' => 'Set Can Badge Bulat 58mm Tokyo Revengers (Isi 4)',
                'satuan_id' => $set,
                'harga_beli' => 15000,
                'harga_jual' => 38000,
                'stok' => 70,
                'stok_minimal' => 12,
                'deskripsi' => 'Set 4 pin bros bulat finishing hologram bintang.',
            ],

            // 8. Alat Tulis & Notebook
            [
                'kategori' => 'Alat Tulis & Notebook',
                'nama' => 'Sticker Pack Waterproof Anime 50 Pcs No-Repeat',
                'satuan_id' => $pck,
                'harga_beli' => 12000,
                'harga_jual' => 32000,
                'stok' => 130,
                'stok_minimal' => 25,
                'deskripsi' => 'Stiker vinyl anti-air dan tahan sinar matahari untuk laptop/helm.',
            ],
            [
                'kategori' => 'Alat Tulis & Notebook',
                'nama' => 'Notebook Spiral Hardcover A5 Death Note Style',
                'satuan_id' => $pcs,
                'harga_beli' => 18000,
                'harga_jual' => 45000,
                'stok' => 65,
                'stok_minimal' => 10,
                'deskripsi' => 'Buku catatan bersampul tebal 100 lembar kertas bookpaper cream.',
            ],

            // 9. Bantal & Plushie Souvenir
            [
                'kategori' => 'Bantal & Plushie Souvenir',
                'nama' => 'Boneka Plushie Chainsaw Man Pochita 25cm',
                'satuan_id' => $pcs,
                'harga_beli' => 45000,
                'harga_jual' => 98000,
                'stok' => 50,
                'stok_minimal' => 10,
                'deskripsi' => 'Boneka lembut bahan yelvo isi dacron empuk berkualitas.',
            ],
            [
                'kategori' => 'Bantal & Plushie Souvenir',
                'nama' => 'Bantal Sofa Karakter Anime 40x40cm Custom',
                'satuan_id' => $pcs,
                'harga_beli' => 38000,
                'harga_jual' => 85000,
                'stok' => 40,
                'stok_minimal' => 8,
                'deskripsi' => 'Bantal tidur santai bahan lembut sarung dapat dilepas dan dicuci.',
            ],

            // 10. Kerajinan & Oleh-oleh Khas
            [
                'kategori' => 'Kerajinan & Oleh-oleh Khas',
                'nama' => 'Miniatur Wayang Kulit Frame Kayu Kaca',
                'satuan_id' => $pcs,
                'harga_beli' => 45000,
                'harga_jual' => 95000,
                'stok' => 30,
                'stok_minimal' => 5,
                'deskripsi' => 'Souvenir cinderamata kerajinan wayang dalam bingkai kayu estetik.',
            ],
            [
                'kategori' => 'Kerajinan & Oleh-oleh Khas',
                'nama' => 'Gantungan Kunci Kayu Ukir Souvenir Daerah',
                'satuan_id' => $pcs,
                'harga_beli' => 6000,
                'harga_jual' => 18000,
                'tipe_diskon' => 'persen',
                'diskon' => 20,
                'stok' => 100,
                'stok_minimal' => 20,
                'deskripsi' => 'Gantungan kunci kayu ukir laser finishing pernis mengkilap.',
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

        $this->command->info('✅ ProdukSeeder: 30 Produk komprehensif berhasil dibuat/disinkronkan.');
    }
}
