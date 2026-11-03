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
        $user = User::first();
        if (!$user) {
            $this->command->warn('⚠️ Tidak ada user untuk PembelianSeeder.');
            return;
        }

        $produks = Produk::all();
        if ($produks->isEmpty()) {
            $this->command->warn('⚠️ Tidak ada produk untuk PembelianSeeder.');
            return;
        }

        $suppliers = [
            'PT Merchindo Souvenir Utama',
            'CV Anime Kreasi Nusantara',
            'Distributor Figure Import Jakarta',
            'UD Bandung Apparel & Sablon',
            'Pabrik Akrilik & Tumbler Jaya',
            'Grosir Souvenir Yogyakarta',
            'CV Cetak Grafika Bandung',
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
            $year  = $p['year'];
            $month = $p['month'];

            // 2 s.d. 3 pembelian per bulan
            $trxCount = rand(2, 3);

            for ($t = 0; $t < $trxCount; $t++) {
                $day = rand(2, 26);
                $tanggal = Carbon::create($year, $month, $day, rand(9, 16), rand(0, 59));

                $kodePembelian = 'PBL-' . $tanggal->format('Ymd') . '-' . str_pad($orderCounter++, 4, '0', STR_PAD_LEFT);

                DB::beginTransaction();
                try {
                    $pembelian = Pembelian::create([
                        'kode_pembelian' => $kodePembelian,
                        'tanggal'        => $tanggal->format('Y-m-d'),
                        'supplier'       => $suppliers[array_rand($suppliers)],
                        'total_harga'    => 0,
                        'total_diskon'   => 0,
                        'grand_total'    => 0,
                        'catatan'        => 'Restock batch produk ' . $tanggal->translatedFormat('F Y'),
                        'user_id'        => $user->id,
                    ]);

                    $totalHarga = 0;
                    // Pilih 2 - 5 produk acak
                    $itemCount = rand(2, 5);
                    $selectedProduks = $produks->random($itemCount);

                    foreach ($selectedProduks as $produk) {
                        $qty = rand(10, 35);
                        $hargaBeli = (float) $produk->harga_beli;
                        $subtotal = $qty * $hargaBeli;

                        PembelianDetail::create([
                            'pembelian_id' => $pembelian->id,
                            'produk_id'    => $produk->id,
                            'nama_produk'  => $produk->nama_produk,
                            'harga_beli'   => $hargaBeli,
                            'qty'          => $qty,
                            'subtotal'     => $subtotal,
                            'created_at'   => $tanggal,
                            'updated_at'   => $tanggal,
                        ]);

                        $totalHarga += $subtotal;
                    }

                    // Diskon supplier berkala pada beberapa pembelian
                    $diskon = (rand(1, 100) <= 30) ? (rand(1, 5) * 25000) : 0;
                    if ($diskon > $totalHarga) $diskon = 0;

                    $pembelian->update([
                        'total_harga'  => $totalHarga,
                        'total_diskon' => $diskon,
                        'grand_total'  => $totalHarga - $diskon,
                        'created_at'   => $tanggal,
                        'updated_at'   => $tanggal,
                    ]);

                    DB::commit();
                } catch (\Exception $e) {
                    DB::rollBack();
                }
            }
        }

        $this->command->info('✅ PembelianSeeder: ' . ($orderCounter - 1) . ' Transaksi Pembelian (2025-2026) berhasil dibuat.');
    }
}
