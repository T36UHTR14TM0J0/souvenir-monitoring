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
        $pck = $satuans['pck']->id ?? $pcs;
        $lbr = $satuans['lbr']->id ?? $pcs;
        $set = $satuans['set']->id ?? $pcs;
        $btl = $satuans['btl']->id ?? $pcs;
        $roll = $satuans['roll']->id ?? $pcs;
        $lsn = $satuans['lsn']->id ?? $pcs;

        // DAFTAR PRODUK LENGKAP: SOUVENIR JADI & BAHAN BAKU
        $produkList = [
            // ========================================================
            // A. SOUVENIR JADI (Dijual di Shopee, Lazada, TikTok)
            // ========================================================
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

            // ========================================================
            // B. BAHAN BAKU PRODUKSI (Dibeli oleh Bendahara dari Supplier)
            // ========================================================
            [
                'jenis' => 'bahan_baku',
                'kategori' => 'Bahan Baku Produksi',
                'nama' => 'Mug Polos Keramik Sublimasi SNI Super White',
                'satuan_id' => $pcs,
                'harga_beli' => 10500,
                'harga_jual' => 10500,
                'tipe_diskon' => 'none',
                'diskon' => 0,
                'stok' => 250,
                'stok_minimal' => 50,
                'deskripsi' => 'Mug keramik polos sudah di-coating siap cetak heat press sublimasi.',
            ],
            [
                'jenis' => 'bahan_baku',
                'kategori' => 'Bahan Baku Produksi',
                'nama' => 'Lembaran Akrilik Bening 2mm Ukuran A4 (21x30cm)',
                'satuan_id' => $lbr,
                'harga_beli' => 14000,
                'harga_jual' => 14000,
                'tipe_diskon' => 'none',
                'diskon' => 0,
                'stok' => 180,
                'stok_minimal' => 40,
                'deskripsi' => 'Bahan dasar gantungan kunci & standee akrilik kualitas Grade A.',
            ],
            [
                'jenis' => 'bahan_baku',
                'kategori' => 'Bahan Baku Produksi',
                'nama' => 'Ring Gantungan Kunci Besi Putar Tebal 25mm + Rantai',
                'satuan_id' => $pcs,
                'harga_beli' => 750,
                'harga_jual' => 750,
                'tipe_diskon' => 'none',
                'diskon' => 0,
                'stok' => 1200,
                'stok_minimal' => 200,
                'deskripsi' => 'Ring gantungan kunci nickel lapis anti karat untuk souvenir akrilik.',
            ],
            [
                'jenis' => 'bahan_baku',
                'kategori' => 'Bahan Baku Produksi',
                'nama' => 'Blank Tumbler Stainless Polos 500ml Siap Grafir',
                'satuan_id' => $pcs,
                'harga_beli' => 32000,
                'harga_jual' => 32000,
                'tipe_diskon' => 'none',
                'diskon' => 0,
                'stok' => 110,
                'stok_minimal' => 25,
                'deskripsi' => 'Tumbler termos polos cat doff hitam siap laser grafir custom.',
            ],
            [
                'jenis' => 'bahan_baku',
                'kategori' => 'Bahan Baku Produksi',
                'nama' => 'Blank Kaleng Pin Button 58mm + Peniti Belakang',
                'satuan_id' => $pcs,
                'harga_beli' => 1200,
                'harga_jual' => 1200,
                'tipe_diskon' => 'none',
                'diskon' => 0,
                'stok' => 800,
                'stok_minimal' => 150,
                'deskripsi' => 'Material blank kaleng, plastik mika pelindung, dan peniti siap press.',
            ],
            [
                'jenis' => 'bahan_baku',
                'kategori' => 'Bahan Baku Produksi',
                'nama' => 'Tinta Sublimasi CMYK Botol 100ml Korea',
                'satuan_id' => $btl,
                'harga_beli' => 35000,
                'harga_jual' => 35000,
                'tipe_diskon' => 'none',
                'diskon' => 0,
                'stok' => 24,
                'stok_minimal' => 6,
                'deskripsi' => 'Tinta sublimasi khusus printer Epson untuk cetak mug dan lanyard.',
            ],
            [
                'jenis' => 'bahan_baku',
                'kategori' => 'Bahan Baku Produksi',
                'nama' => 'Blank Plakat Kayu MDF Finishing Glossy 15x20cm',
                'satuan_id' => $pcs,
                'harga_beli' => 25000,
                'harga_jual' => 25000,
                'tipe_diskon' => 'none',
                'diskon' => 0,
                'stok' => 60,
                'stok_minimal' => 15,
                'deskripsi' => 'Bahan plakat kayu MDF tebal dengan dudukan kayu siap pasang akrilik.',
            ],

            // ========================================================
            // C. BAHAN PACKING & PENGIRIMAN
            // ========================================================
            [
                'jenis' => 'bahan_baku',
                'kategori' => 'Bahan Packing & Pengiriman',
                'nama' => 'Kardus Die Cut Box Mug Souvenir + Jendela Mika',
                'satuan_id' => $pcs,
                'harga_beli' => 1500,
                'harga_jual' => 1500,
                'tipe_diskon' => 'none',
                'diskon' => 0,
                'stok' => 450,
                'stok_minimal' => 100,
                'deskripsi' => 'Kardus packing khusus mug souvenir tebal dan kokoh saat pengiriman ekspedisi.',
            ],
            [
                'jenis' => 'bahan_baku',
                'kategori' => 'Bahan Packing & Pengiriman',
                'nama' => 'Roll Bubble Wrap Tebal Grade A Ukuran 50m x 1.25m',
                'satuan_id' => $roll,
                'harga_beli' => 115000,
                'harga_jual' => 115000,
                'tipe_diskon' => 'none',
                'diskon' => 0,
                'stok' => 12,
                'stok_minimal' => 3,
                'deskripsi' => 'Bubble wrap pelindung souvenir pecah belah anti benturan ekspedisi.',
            ],
            [
                'jenis' => 'bahan_baku',
                'kategori' => 'Bahan Packing & Pengiriman',
                'nama' => 'Lakban Fragile Merah Bening Tebal 48mm x 100 Yard',
                'satuan_id' => $roll,
                'harga_beli' => 11000,
                'harga_jual' => 11000,
                'tipe_diskon' => 'none',
                'diskon' => 0,
                'stok' => 30,
                'stok_minimal' => 8,
                'deskripsi' => 'Lakban bertuliskan Fragile Jangan Dibanting daya rekat kuat.',
            ],
            [
                'jenis' => 'bahan_baku',
                'kategori' => 'Bahan Baku Produksi',
                'nama' => 'Kertas Sublimasi Fast Dry A4 (Isi 100 Lembar)',
                'satuan_id' => $pck,
                'harga_beli' => 45000,
                'harga_jual' => 45000,
                'tipe_diskon' => 'none',
                'diskon' => 0,
                'stok' => 40,
                'stok_minimal' => 10,
                'deskripsi' => 'Kertas transfer sublimasi daya serap tinta tinggi cepat kering.',
            ],
            [
                'jenis' => 'bahan_baku',
                'kategori' => 'Bahan Packing & Pengiriman',
                'nama' => 'Plastik OPP Seal Kaca Tebal 0.5 Ukuran 7x15cm (Isi 100)',
                'satuan_id' => $pck,
                'harga_beli' => 8500,
                'harga_jual' => 8500,
                'tipe_diskon' => 'none',
                'diskon' => 0,
                'stok' => 85,
                'stok_minimal' => 20,
                'deskripsi' => 'Plastik pembungkus gantungan kunci dan pin button dengan perekat.',
            ],
            [
                'jenis' => 'bahan_baku',
                'kategori' => 'Bahan Packing & Pengiriman',
                'nama' => 'Pita Satin Emas Gold Lebar 2cm Roll 20 Yard',
                'satuan_id' => $roll,
                'harga_beli' => 12000,
                'harga_jual' => 12000,
                'tipe_diskon' => 'none',
                'diskon' => 0,
                'stok' => 25,
                'stok_minimal' => 5,
                'deskripsi' => 'Pita satin hiasan kemasan souvenir wedding dan cinderamata.',
            ],
            [
                'jenis' => 'bahan_baku',
                'kategori' => 'Bahan Baku Produksi',
                'nama' => 'Tali Lanyard Polos Putih Tisu 2cm Roll 50 Meter',
                'satuan_id' => $roll,
                'harga_beli' => 85000,
                'harga_jual' => 85000,
                'tipe_diskon' => 'none',
                'diskon' => 0,
                'stok' => 18,
                'stok_minimal' => 4,
                'deskripsi' => 'Bahan dasar lanyard printing tisu lembut dan tidak gatal di leher.',
            ],
            [
                'jenis' => 'bahan_baku',
                'kategori' => 'Bahan Baku Produksi',
                'nama' => 'Stopper & Kait Cantolan Logam Lanyard 2cm',
                'satuan_id' => $pcs,
                'harga_beli' => 1500,
                'harga_jual' => 1500,
                'tipe_diskon' => 'none',
                'diskon' => 0,
                'stok' => 650,
                'stok_minimal' => 150,
                'deskripsi' => 'Aksesoris cantolan kait putar besi nikel dan stopper plastik lanyard.',
            ],
            [
                'jenis' => 'bahan_baku',
                'kategori' => 'Bahan Baku Produksi',
                'nama' => 'Blank Totebag Blacu Polos 35x40cm Siap Sablon',
                'satuan_id' => $pcs,
                'harga_beli' => 9500,
                'harga_jual' => 9500,
                'tipe_diskon' => 'none',
                'diskon' => 0,
                'stok' => 150,
                'stok_minimal' => 30,
                'deskripsi' => 'Totebag bahan kain blacu tebal jahitan rapi siap cetak DTF / sablon.',
            ],
            [
                'jenis' => 'bahan_baku',
                'kategori' => 'Bahan Packing & Pengiriman',
                'nama' => 'Dus Kemasan Tumbler Box Kraft Coklat Eksklusif',
                'satuan_id' => $pcs,
                'harga_beli' => 2500,
                'harga_jual' => 2500,
                'tipe_diskon' => 'none',
                'diskon' => 0,
                'stok' => 240,
                'stok_minimal' => 50,
                'deskripsi' => 'Box kemasan kardus khusus botol tumbler termal 500ml.',
            ],
            [
                'jenis' => 'bahan_baku',
                'kategori' => 'Bahan Packing & Pengiriman',
                'nama' => 'Kertas Thermal Label Resi Pengiriman 100x150mm Roll 500',
                'satuan_id' => $roll,
                'harga_beli' => 38000,
                'harga_jual' => 38000,
                'tipe_diskon' => 'none',
                'diskon' => 0,
                'stok' => 20,
                'stok_minimal' => 5,
                'deskripsi' => 'Sticker kertas thermal barcode cetak resi pengiriman marketplace.',
            ],
            [
                'jenis' => 'bahan_baku',
                'kategori' => 'Bahan Packing & Pengiriman',
                'nama' => 'Mika Tabung Silinder Souvenir Transparan 8.5cm',
                'satuan_id' => $pcs,
                'harga_beli' => 3200,
                'harga_jual' => 3200,
                'tipe_diskon' => 'none',
                'diskon' => 0,
                'stok' => 190,
                'stok_minimal' => 40,
                'deskripsi' => 'Kemasan mika bening tebal dengan tutup kaleng emas.',
            ],
            [
                'jenis' => 'bahan_baku',
                'kategori' => 'Bahan Baku Produksi',
                'nama' => 'Lembaran Akrilik Hitam Pekat 2mm Ukuran A4',
                'satuan_id' => $lbr,
                'harga_beli' => 16500,
                'harga_jual' => 16500,
                'tipe_diskon' => 'none',
                'diskon' => 0,
                'stok' => 80,
                'stok_minimal' => 20,
                'deskripsi' => 'Bahan alas dudukan standee akrilik dan plakat penghargaan.',
            ],
            [
                'jenis' => 'bahan_baku',
                'kategori' => 'Bahan Baku Produksi',
                'nama' => 'Kait Gantungan Kunci Udang Logam Silver Putar 35mm',
                'satuan_id' => $pcs,
                'harga_beli' => 950,
                'harga_jual' => 950,
                'tipe_diskon' => 'none',
                'diskon' => 0,
                'stok' => 550,
                'stok_minimal' => 100,
                'deskripsi' => 'Kait gantungan kunci model lobster clasp untuk goci anime.',
            ],
            [
                'jenis' => 'bahan_baku',
                'kategori' => 'Bahan Baku Produksi',
                'nama' => 'Gantungan Kunci Karet Rubber Base Cetak Custom',
                'satuan_id' => $pcs,
                'harga_beli' => 4500,
                'harga_jual' => 4500,
                'tipe_diskon' => 'none',
                'diskon' => 0,
                'stok' => 8,
                'stok_minimal' => 25,
                'deskripsi' => 'Material rubber lentur siap proses cetak timbul (Stok Kritis).',
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

        // ========================================================
        // D. RESEP BAHAN BAKU (BILL OF MATERIALS / BOM WORKSHOP)
        // ========================================================
        $allProduk = Produk::all()->keyBy('nama_produk');

        $resepMap = [
            'Mug Keramik Custom Anime Jibranime (SNI)' => [
                'Mug Polos Keramik Sublimasi SNI Super White' => ['jumlah' => 1, 'keterangan' => 'Blanko mug polos siap press heat press'],
                'Kardus Die Cut Box Mug Souvenir + Jendela Mika' => ['jumlah' => 1, 'keterangan' => 'Box kemasan kardus khusus mug'],
            ],
            'Tumbler Stainless Termal Grafir Custom 500ml' => [
                'Blank Tumbler Stainless Polos 500ml Siap Grafir' => ['jumlah' => 1, 'keterangan' => 'Blank tumbler stainless tahan panas'],
            ],
            'Gantungan Kunci Akrilik 2 Sisi Gojo Satoru 6cm' => [
                'Lembaran Akrilik Bening 2mm Ukuran A4 (21x30cm)' => ['jumlah' => 0.1, 'keterangan' => 'Akrilik tebal 2mm (1 lbr muat 10 goci)'],
                'Ring Gantungan Kunci Besi Putar Tebal 25mm + Rantai' => ['jumlah' => 1, 'keterangan' => 'Ring gantungan besi anti karat'],
            ],
            'Gantungan Kunci Akrilik 2 Sisi Zoro Wano' => [
                'Lembaran Akrilik Bening 2mm Ukuran A4 (21x30cm)' => ['jumlah' => 0.1, 'keterangan' => 'Akrilik tebal 2mm (1 lbr muat 10 goci)'],
                'Ring Gantungan Kunci Besi Putar Tebal 25mm + Rantai' => ['jumlah' => 1, 'keterangan' => 'Ring gantungan besi anti karat'],
            ],
            'Pin Kaleng Peniti Anime 58mm (Isi 3)' => [
                'Blank Kaleng Pin Button 58mm + Peniti Belakang' => ['jumlah' => 3, 'keterangan' => 'Blank kaleng 58mm + mika peniti'],
            ],
            'Plakat Akrilik Custom Grafir / Kenang-kenangan 15x20cm' => [
                'Lembaran Akrilik Bening 2mm Ukuran A4 (21x30cm)' => ['jumlah' => 1, 'keterangan' => 'Akrilik bening grafir laser'],
                'Blank Plakat Kayu MDF Finishing Glossy 15x20cm' => ['jumlah' => 1, 'keterangan' => 'Dudukan kayu MDF finishing halus'],
            ],
        ];

        foreach ($resepMap as $namaSouvenir => $bahans) {
            $souvenir = $allProduk[$namaSouvenir] ?? null;
            if (! $souvenir) {
                continue;
            }

            $sync = [];
            foreach ($bahans as $namaBahan => $data) {
                $bahan = $allProduk[$namaBahan] ?? null;
                if ($bahan) {
                    $sync[$bahan->id] = $data;
                }
            }

            $souvenir->resepBahanBaku()->sync($sync);
        }

        $this->command->info('ProdukSeeder: Souvenir Jadi dan Bahan Baku berhasil dibuat/disinkronkan.');
    }
}
