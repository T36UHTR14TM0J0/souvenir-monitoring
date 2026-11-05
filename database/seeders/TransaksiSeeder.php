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
        $user = User::where('email', 'admin@gmail.com')->first() ?? User::first();
        if (! $user) {
            $this->command->warn('Tidak ada user untuk TransaksiSeeder.');

            return;
        }

        $souvenirProduks = Produk::where('jenis', 'souvenir_jadi')->get();
        if ($souvenirProduks->isEmpty()) {
            $souvenirProduks = Produk::all();
        }

        $pelangganList = [
            'Dimas Pratama', 'Aulia Rahma', 'Fajar Hidayat', 'Kevin Sanjaya',
            'Siti Nurhaliza', 'Rian Ardiansyah', 'Bella Amanda', 'Naufal Rizky',
            'Putri Ayu', 'Rizky Ramadhan', 'Dian Safitri', 'Farhan Maulana',
            'Tasya Kamila', 'Wahyu Setiawan', 'Anisa Rahmawati', 'Bima Sakti',
            'Nadya Zahrani', 'Cindy Claudia', 'Eko Prasetyo', 'Dewi Lestari',
            'Panitia Event Kampus UI', 'Komunitas Anime Jakarta', 'BUMN Graha Souvenir', 'EO Wedding Nusantara',
        ];

        $channels = ['shopee', 'shopee', 'shopee', 'lazada', 'tiktok', 'tiktok'];
        $kurirList = ['SPX Standard', 'J&T Express', 'JNE Regular', 'SiCepat Reg'];

        // 22 Bulan: Jan 2025 s.d. Des 2025 (12 bulan) & Jan 2026 s.d. Okt 2026 (10 bulan)
        $periods = [];
        for ($m = 1; $m <= 12; $m++) {
            $periods[] = ['year' => 2025, 'month' => $m];
        }
        for ($m = 1; $m <= 10; $m++) {
            $periods[] = ['year' => 2026, 'month' => $m];
        }

        $trxCounter = 1;

        foreach ($periods as $p) {
            $year = $p['year'];
            $month = $p['month'];

            // 16 s.d. 24 transaksi per bulan
            $trxCount = rand(16, 24);

            for ($t = 0; $t < $trxCount; $t++) {
                $day = rand(1, 28);
                $hour = rand(8, 21);
                $minute = rand(0, 59);

                $tanggal = Carbon::create($year, $month, $day, $hour, $minute);
                $channel = $channels[array_rand($channels)];
                $kurir = $kurirList[array_rand($kurirList)];

                $prefixPesanan = match ($channel) {
                    'shopee' => $tanggal->format('ymd').'SHP'.rand(10000, 99999),
                    'lazada' => 'LZD'.$tanggal->format('ymd').rand(1000, 9999),
                    'tiktok' => 'TT'.$tanggal->format('ymd').rand(10000, 99999),
                };

                $prefixResi = match ($kurir) {
                    'SPX Standard' => 'SPXID0'.rand(10000000, 99999999),
                    'J&T Express' => 'JT'.rand(1000000000, 9999999999),
                    'JNE Regular' => 'JNE'.rand(100000000, 999999999),
                    default => 'SCP'.rand(10000000, 99999999),
                };

                // Bulan Oktober 2026 memiliki variasi pesanan aktif (siap_packing, perlu_dikirim, dikirim, selesai)
                if ($year === 2026 && $month === 10 && $day >= 20) {
                    $statuses = ['selesai', 'selesai', 'dikirim', 'perlu_dikirim', 'siap_packing'];
                    $statusPesanan = $statuses[array_rand($statuses)];
                } else {
                    $statusPesanan = (rand(1, 100) <= 93) ? 'selesai' : (rand(1, 2) === 1 ? 'retur' : 'batal');
                }

                $kodeTransaksi = 'TRX-'.$tanggal->format('Ymd').'-'.str_pad($trxCounter++, 4, '0', STR_PAD_LEFT);

                DB::beginTransaction();
                try {
                    $transaksi = Transaksi::create([
                        'channel' => $channel,
                        'kode_transaksi' => $kodeTransaksi,
                        'no_pesanan_marketplace' => $prefixPesanan,
                        'no_resi' => in_array($statusPesanan, ['siap_packing', 'batal'], true) ? null : $prefixResi,
                        'kurir' => $kurir,
                        'tanggal' => $tanggal->format('Y-m-d'),
                        'nama_pelanggan' => $pelangganList[array_rand($pelangganList)],
                        'total_harga' => 0,
                        'total_diskon' => 0,
                        'grand_total' => 0,
                        'biaya_admin_marketplace' => 0,
                        'penghasilan_bersih' => 0,
                        'status_pesanan' => $statusPesanan,
                        'bahan_baku_dipotong' => in_array($statusPesanan, ['siap_packing', 'perlu_dikirim', 'dikirim', 'selesai'], true),
                        'waktu_potong_bahan' => in_array($statusPesanan, ['siap_packing', 'perlu_dikirim', 'dikirim', 'selesai'], true) ? $tanggal : null,
                        'metode_bayar' => 'saldo_'.$channel,
                        'catatan' => (rand(1, 100) <= 35) ? 'Custom desain logo/nama + kemasan box pita souvenir' : null,
                        'user_id' => $user->id,
                        'created_at' => $tanggal,
                        'updated_at' => $tanggal,
                    ]);

                    $totalHarga = 0;
                    $totalDiskon = 0;
                    $itemCount = min(rand(1, 3), $souvenirProduks->count());
                    $selectedProduks = $souvenirProduks->random($itemCount);

                    foreach ($selectedProduks as $produk) {
                        // Kombinasi order eceran (3-10 pcs) & order grosir souvenir (15-35 pcs)
                        $isBulkOrder = rand(1, 100) <= 35;
                        $qty = $isBulkOrder ? rand(15, 35) : rand(3, 12);
                        $hargaNormal = (float) $produk->harga_jual;
                        $hargaJual = (float) ($produk->harga_setelah_diskon ?? $produk->harga_jual);
                        $subtotal = $qty * $hargaJual;
                        $hemat = max(0, ($hargaNormal - $hargaJual) * $qty);

                        TransaksiDetail::create([
                            'transaksi_id' => $transaksi->id,
                            'produk_id' => $produk->id,
                            'nama_produk' => $produk->nama_produk,
                            'harga_satuan' => $hargaJual,
                            'qty' => $qty,
                            'subtotal' => $subtotal,
                            'created_at' => $tanggal,
                            'updated_at' => $tanggal,
                        ]);

                        $totalHarga += $subtotal;
                        $totalDiskon += $hemat;
                    }

                    // Biaya potongan admin marketplace rata-rata 6.5% - 8.0%
                    $persenAdmin = rand(65, 80) / 1000;
                    $biayaAdmin = in_array($statusPesanan, ['batal', 'retur'], true) ? 0 : round($totalHarga * $persenAdmin);
                    $penghasilanBersih = max(0, $totalHarga - $biayaAdmin);

                    $transaksi->update([
                        'total_harga' => $totalHarga,
                        'total_diskon' => $totalDiskon,
                        'grand_total' => $totalHarga,
                        'biaya_admin_marketplace' => $biayaAdmin,
                        'penghasilan_bersih' => $penghasilanBersih,
                    ]);

                    DB::commit();
                } catch (\Throwable $e) {
                    DB::rollBack();
                }
            }
        }

        $this->command->info('TransaksiSeeder: Pesanan 3 Marketplace (Jan 2025 - Des 2025 & Jan 2026 - Okt 2026) berhasil di-seed.');
    }
}
