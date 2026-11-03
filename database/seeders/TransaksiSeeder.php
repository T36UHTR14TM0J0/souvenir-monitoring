<?php

namespace Database\Seeders;

use App\Models\Produk;
use App\Models\Transaksi;
use App\Models\TransaksiDetail;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TransaksiSeeder extends Seeder
{
    public function run(): void
    {
        $user = User::first();
        if (!$user) {
            $this->command->warn('⚠️ Tidak ada user untuk TransaksiSeeder.');
            return;
        }

        $produks = Produk::all();
        if ($produks->isEmpty()) {
            $this->command->warn('⚠️ Tidak ada produk untuk TransaksiSeeder.');
            return;
        }

        $pelangganList = [
            'Dimas Pratama', 'Aulia Rahma', 'Fajar Hidayat', 'Kevin Sanjaya',
            'Siti Nurhaliza', 'Rian Ardiansyah', 'Bella Amanda', 'Naufal Rizky',
            'Putri Ayu', 'Rizky Ramadhan', 'Dian Safitri', 'Farhan Maulana',
            'Tasya Kamila', 'Wahyu Setiawan', 'Anisa Rahmawati', 'Bima Sakti',
            'Nadya Zahrani', 'Aldi Taher', 'Cindy Claudia', 'Eko Prasetyo',
            null, null, null, // Walk-in customer tanpa nama
        ];

        $metodeBayarList = ['qris', 'tunai', 'transfer'];

        // 22 Bulan: Jan 2025 s.d. Des 2025 & Jan 2026 s.d. Okt 2026
        $periods = [];
        for ($m = 1; $m <= 12; $m++) {
            $periods[] = ['year' => 2025, 'month' => $m];
        }
        for ($m = 1; $m <= 10; $m++) {
            $periods[] = ['year' => 2026, 'month' => $m];
        }

        $trxCounter = 1;

        foreach ($periods as $p) {
            $year  = $p['year'];
            $month = $p['month'];

            // 15 s.d. 22 transaksi per bulan agar data ramai dan grafik keuangan terlihat hidup
            $trxCount = rand(15, 22);

            for ($t = 0; $t < $trxCount; $t++) {
                $day = rand(1, 28);
                $hour = rand(9, 20); // Jam 09:00 - 20:00
                $minute = rand(0, 59);

                $tanggal = Carbon::create($year, $month, $day, $hour, $minute);
                $kodeTransaksi = 'TRX-' . $tanggal->format('Ymd') . '-' . str_pad($trxCounter++, 4, '0', STR_PAD_LEFT);

                DB::beginTransaction();
                try {
                    $transaksi = Transaksi::create([
                        'kode_transaksi' => $kodeTransaksi,
                        'tanggal'        => $tanggal->format('Y-m-d'),
                        'nama_pelanggan' => $pelangganList[array_rand($pelangganList)],
                        'total_harga'    => 0,
                        'total_diskon'   => 0,
                        'grand_total'    => 0,
                        'metode_bayar'   => $metodeBayarList[array_rand($metodeBayarList)],
                        'catatan'        => null,
                        'user_id'        => $user->id,
                    ]);

                    $totalHarga = 0;
                    $itemCount = rand(1, 4);
                    $selectedProduks = $produks->random($itemCount);

                    foreach ($selectedProduks as $produk) {
                        $qty = rand(1, 3);
                        $hargaJual = (float) $produk->harga_jual;
                        $subtotal = $qty * $hargaJual;

                        TransaksiDetail::create([
                            'transaksi_id' => $transaksi->id,
                            'produk_id'    => $produk->id,
                            'nama_produk'  => $produk->nama_produk,
                            'harga_satuan' => $hargaJual,
                            'qty'          => $qty,
                            'subtotal'     => $subtotal,
                            'created_at'   => $tanggal,
                            'updated_at'   => $tanggal,
                        ]);

                        $totalHarga += $subtotal;
                    }

                    // Diskon promo sesekali (~20% dari transaksi)
                    $diskon = 0;
                    if ($totalHarga >= 100000 && rand(1, 100) <= 25) {
                        $diskonPilihan = [5000, 10000, 15000, 20000];
                        $diskon = $diskonPilihan[array_rand($diskonPilihan)];
                    }

                    $transaksi->update([
                        'total_harga'  => $totalHarga,
                        'total_diskon' => $diskon,
                        'grand_total'  => max(0, $totalHarga - $diskon),
                        'created_at'   => $tanggal,
                        'updated_at'   => $tanggal,
                    ]);

                    DB::commit();
                } catch (\Exception $e) {
                    DB::rollBack();
                }
            }
        }

        $this->command->info('✅ TransaksiSeeder: ' . ($trxCounter - 1) . ' Transaksi Penjualan (2025-2026) berhasil dibuat.');
    }
}
