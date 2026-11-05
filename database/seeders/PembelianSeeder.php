<?php

namespace Database\Seeders;

use App\Models\Pembelian;
use App\Models\PembelianDetail;
use App\Models\Produk;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PembelianSeeder extends Seeder
{
    public function run(): void
    {
        $user = User::where('email', 'bendahara@gmail.com')->first() ?? User::first();
        if (! $user) {
            $this->command->warn('Tidak ada user untuk PembelianSeeder.');

            return;
        }

        $bahanBakuProduks = Produk::where('jenis', 'bahan_baku')->get();
        if ($bahanBakuProduks->isEmpty()) {
            $bahanBakuProduks = Produk::all();
        }

        $onlineSuppliers = [
            ['nama' => 'PT Keramik Sublimasi Official Store', 'platform' => 'shopee'],
            ['nama' => 'CV Akrilik Nusantara Gemilang Store', 'platform' => 'tokopedia'],
            ['nama' => 'Distributor Kemasan & Box Bandung Official', 'platform' => 'shopee'],
            ['nama' => 'Aneka Logam & Ring Keychain Grosir', 'platform' => 'tokopedia'],
            ['nama' => 'Pabrik Botol Termal & Tumbler Indonesia', 'platform' => 'tiktok'],
            ['nama' => 'Toko Tinta Grafika Sublimasi Murah', 'platform' => 'lazada'],
            ['nama' => 'Grosir Bahan Souvenir Pasar Pagi', 'platform' => 'offline'],
        ];

        $kurirKargoList = [
            'J&T Cargo',
            'SiCepat Cargo (GOKIL)',
            'JNE Trucking (JTR)',
            'Sentral Cargo',
            'Indah Logistik Cargo',
        ];

        // 22 Bulan: Jan 2025 s.d. Des 2025 (12 bulan) & Jan 2026 s.d. Okt 2026 (10 bulan)
        $periods = [];
        for ($m = 1; $m <= 12; $m++) {
            $periods[] = ['year' => 2025, 'month' => $m];
        }
        for ($m = 1; $m <= 10; $m++) {
            $periods[] = ['year' => 2026, 'month' => $m];
        }

        $orderCounter = 1;

        foreach ($periods as $p) {
            $year = $p['year'];
            $month = $p['month'];

            // 2 s.d. 4 pembelian bahan baku per bulan
            $trxCount = rand(2, 4);

            for ($t = 0; $t < $trxCount; $t++) {
                $day = rand(2, 26);
                $tanggal = Carbon::create($year, $month, $day, rand(9, 16), rand(0, 59));

                $sup = $onlineSuppliers[array_rand($onlineSuppliers)];
                $isOffline = $sup['platform'] === 'offline';
                $kurir = $isOffline ? null : $kurirKargoList[array_rand($kurirKargoList)];
                $ongkir = $isOffline ? 0 : [25000, 35000, 45000, 50000, 75000, 90000][rand(0, 5)];
                $diskon = [0, 0, 10000, 15000, 25000][rand(0, 4)];

                // Transaksi di bulan terakhir (Okt 2026) variasikan status agar ada dipesan, dikirim, diterima
                $statusPembelian = 'diterima';
                if ($year === 2026 && $month === 10) {
                    if ($day >= 23) {
                        $statusPembelian = 'dipesan';
                    } elseif ($day >= 17) {
                        $statusPembelian = 'dikirim';
                    }
                }

                $prefixInv = strtoupper(substr($sup['platform'], 0, 3));
                $noInvoice = 'INV/'.$tanggal->format('Ymd').'/'.$prefixInv.'/'.rand(100000, 999999);
                $noResi = $isOffline ? null : strtoupper(substr($kurir, 0, 3)).$tanggal->format('ymd').rand(10000, 99999);

                $kodePembelian = 'PBL-'.$tanggal->format('Ymd').'-'.str_pad($orderCounter++, 4, '0', STR_PAD_LEFT);

                DB::beginTransaction();
                try {
                    $pembelian = Pembelian::create([
                        'kode_pembelian' => $kodePembelian,
                        'tanggal' => $tanggal->format('Y-m-d'),
                        'platform' => $sup['platform'],
                        'supplier' => $sup['nama'],
                        'no_invoice_online' => $noInvoice,
                        'ongkir' => $ongkir,
                        'status_pembelian' => $statusPembelian,
                        'kurir_kargo' => $kurir,
                        'no_resi_kargo' => $noResi,
                        'total_harga' => 0,
                        'total_diskon' => $diskon,
                        'grand_total' => 0,
                        'catatan' => $isOffline
                            ? 'Belanja langsung bahan baku & kemasan ke toko fisik'
                            : 'Restock bahan baku online via '.ucfirst($sup['platform']).' kargo '.$kurir,
                        'user_id' => $user->id,
                        'created_at' => $tanggal,
                        'updated_at' => $tanggal,
                    ]);

                    $totalHarga = 0;
                    $itemCount = min(rand(2, 4), $bahanBakuProduks->count());
                    $selectedProduks = $bahanBakuProduks->random($itemCount);

                    foreach ($selectedProduks as $produk) {
                        $qty = rand(25, 65);
                        $hargaBeli = (float) $produk->harga_beli;
                        $subtotal = $qty * $hargaBeli;

                        PembelianDetail::create([
                            'pembelian_id' => $pembelian->id,
                            'produk_id' => $produk->id,
                            'nama_produk' => $produk->nama_produk,
                            'harga_beli' => $hargaBeli,
                            'qty' => $qty,
                            'subtotal' => $subtotal,
                            'created_at' => $tanggal,
                            'updated_at' => $tanggal,
                        ]);

                        $totalHarga += $subtotal;
                    }

                    $grandTotal = max(0, ($totalHarga + $ongkir) - $diskon);

                    $pembelian->update([
                        'total_harga' => $totalHarga,
                        'grand_total' => $grandTotal,
                    ]);

                    DB::commit();
                } catch (\Throwable $e) {
                    DB::rollBack();
                }
            }
        }

        $this->command->info('PembelianSeeder: Data pembelian bahan baku (Jan 2025 - Des 2025 & Jan 2026 - Okt 2026) berhasil di-seed.');
    }
}
