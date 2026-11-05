<?php

namespace Database\Seeders;

use App\Models\PemakaianBahan;
use App\Models\PemakaianBahanDetail;
use App\Models\Produk;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PemakaianBahanSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::where('email', 'admin@gmail.com')->first() ?? User::first();
        if (! $admin) {
            return;
        }

        $bahanBakus = Produk::where('jenis', 'bahan_baku')->with('satuan')->get();
        if ($bahanBakus->isEmpty()) {
            return;
        }

        $operators = [
            'Budi Santoso (Operator Sublim & Press)',
            'Joko Susilo (Operator Laser Akrilik)',
            'Siti Rahmawati (Operator Packing & QC)',
            'Andi Wijaya (Operator Sablon & Finishing)',
            'Rian Pratama (Operator Tumbler UV)',
        ];

        $keperluans = [
            'Produksi Batch Mug Custom Anime Shopee & TikTok',
            'Produksi Gantungan Kunci Akrilik Event Komunitas',
            'Packing & Pengemasan Paket Souvenir Marketplace',
            'Produksi Tumbler Custom Grafir Laser pesanan korporat',
            'Produksi Pin Peniti & Medali Akrilik Souvenir',
            'Restock Etalase Souvenir Siap Kirim Gudang Utama',
        ];

        // 22 Bulan: Jan 2025 s.d. Des 2025 (12 bulan) & Jan 2026 s.d. Okt 2026 (10 bulan)
        $periods = [];
        for ($m = 1; $m <= 12; $m++) {
            $periods[] = ['year' => 2025, 'month' => $m];
        }
        for ($m = 1; $m <= 10; $m++) {
            $periods[] = ['year' => 2026, 'month' => $m];
        }

        $counter = 1;

        foreach ($periods as $p) {
            $year = $p['year'];
            $month = $p['month'];

            // 2 s.d. 3 pengambilan bahan per bulan
            $batchCount = rand(2, 3);

            for ($b = 0; $b < $batchCount; $b++) {
                $day = rand(3, 25);
                $tanggal = Carbon::create($year, $month, $day, rand(8, 15), rand(0, 59));
                $operator = $operators[array_rand($operators)];
                $keperluan = $keperluans[array_rand($keperluans)];

                $kodePemakaian = 'PBB-'.$tanggal->format('Ymd').'-'.str_pad($counter++, 4, '0', STR_PAD_LEFT);

                DB::beginTransaction();
                try {
                    $selectedBahan = $bahanBakus->random(min(rand(2, 3), $bahanBakus->count()));

                    $pemakaian = PemakaianBahan::create([
                        'kode_pemakaian' => $kodePemakaian,
                        'tanggal' => $tanggal->format('Y-m-d'),
                        'nama_operator' => $operator,
                        'keperluan' => $keperluan,
                        'total_item' => $selectedBahan->count(),
                        'total_kuantitas' => 0,
                        'catatan' => ($counter % 3 === 0) ? 'Termasuk cadangan bahan untuk kalibrasi mesin press.' : null,
                        'user_id' => $admin->id,
                        'created_at' => $tanggal,
                        'updated_at' => $tanggal,
                    ]);

                    $totalQty = 0;
                    foreach ($selectedBahan as $bahan) {
                        $qty = rand(15, 45);

                        PemakaianBahanDetail::create([
                            'pemakaian_bahan_id' => $pemakaian->id,
                            'produk_id' => $bahan->id,
                            'nama_bahan' => $bahan->nama_produk,
                            'qty' => $qty,
                            'satuan' => $bahan->satuan->singkatan ?? 'pcs',
                            'keterangan' => 'Pengambilan bahan baku produksi workshop',
                            'created_at' => $tanggal,
                            'updated_at' => $tanggal,
                        ]);

                        $totalQty += $qty;
                    }

                    $pemakaian->update(['total_kuantitas' => $totalQty]);
                    DB::commit();
                } catch (\Throwable $e) {
                    DB::rollBack();
                }
            }
        }

        $this->command->info('PemakaianBahanSeeder: Data pengambilan bahan baku (Jan 2025 - Des 2025 & Jan 2026 - Okt 2026) berhasil di-seed.');
    }
}
