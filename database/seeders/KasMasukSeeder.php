<?php

namespace Database\Seeders;

use App\Models\KasMasuk;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class KasMasukSeeder extends Seeder
{
    public function run(): void
    {
        $user = User::where('email', 'bendahara@gmail.com')->first() ?? User::first();
        if (! $user) {
            $this->command->warn('Tidak ada user untuk KasMasukSeeder.');

            return;
        }

        // 22 Bulan: Jan 2025 s.d. Des 2025 (12 bulan) & Jan 2026 s.d. Okt 2026 (10 bulan)
        $periods = [];
        for ($m = 1; $m <= 12; $m++) {
            $periods[] = ['year' => 2025, 'month' => $m];
        }
        for ($m = 1; $m <= 10; $m++) {
            $periods[] = ['year' => 2026, 'month' => $m];
        }

        $sumberPendapatanLain = [
            'Debit / Transfer',
            'Tunai / Cash',
            'Lainnya',
        ];

        $counter = 1;

        $hasClientData = file_exists('C:/Users/teguh/Downloads/KEUANGAN TAHUN 2025 (2).xlsx')
            || file_exists(database_path('data/import_keuangan_2025.json'));

        foreach ($periods as $p) {
            $year = $p['year'];
            $month = $p['month'];

            // Jika ada data historis riil client untuk Agu - Des 2025, lewati dummy seeder
            if ($hasClientData && $year === 2025 && $month >= 8) {
                continue;
            }

            // 1. Setoran Modal Pemilik pada awal tahun & kuartal tertentu
            if ($month === 1 || $month === 4 || $month === 8) {
                $tglModal = Carbon::create($year, $month, rand(3, 8), 10, 0);
                $nominalModal = match ($month) {
                    1 => ($year === 2025 ? 25000000 : 18000000),
                    4 => 10000000,
                    default => 8000000,
                };
                $ketModal = match ($month) {
                    1 => "Suntikan modal kerja awal tahun {$year} oleh pemilik toko",
                    4 => 'Tambahan modal restock besar bahan baku mug keramik & akrilik',
                    default => 'Tambahan kas investasi alat workshop & mesin cetak souvenir',
                };

                $kodeModal = 'KM-'.$tglModal->format('Ymd').'-'.str_pad($counter++, 4, '0', STR_PAD_LEFT);
                KasMasuk::firstOrCreate(
                    ['kode_transaksi' => $kodeModal],
                    [
                        'tanggal' => $tglModal->format('Y-m-d'),
                        'tipe' => 'modal_pemilik',
                        'sumber' => 'Debit / Transfer',
                        'jumlah' => $nominalModal,
                        'keterangan' => $ketModal,
                        'catatan' => 'Transfer rekening pribadi owner ke rekening operasional toko',
                        'user_id' => $user->id,
                        'created_at' => $tglModal,
                        'updated_at' => $tglModal,
                    ]
                );
            }

            // 2. Pendapatan Lain-lain / Cashback Ekspedisi & Insentif Program Kampanye (Rutin tiap bulan)
            $tglLain = Carbon::create($year, $month, rand(15, 25), 14, 30);
            $nominalLain = rand(450, 1200) * 1000;
            $sumber = $sumberPendapatanLain[array_rand($sumberPendapatanLain)];
            $kodeLain = 'KM-'.$tglLain->format('Ymd').'-'.str_pad($counter++, 4, '0', STR_PAD_LEFT);

            KasMasuk::firstOrCreate(
                ['kode_transaksi' => $kodeLain],
                [
                    'tanggal' => $tglLain->format('Y-m-d'),
                    'tipe' => 'pendapatan_lain',
                    'sumber' => $sumber,
                    'jumlah' => $nominalLain,
                    'keterangan' => "Cashback ongkir kargo, insentif kampanye {$month}.{$month} marketplace & penjualan sisa bahan",
                    'catatan' => 'Dana masuk non-reguler di luar checkout pesanan utama',
                    'user_id' => $user->id,
                    'created_at' => $tglLain,
                    'updated_at' => $tglLain,
                ]
            );
        }

        $this->command->info('KasMasukSeeder: Data modal pemilik & pendapatan lain (Jan 2025 - Des 2025 & Jan 2026 - Okt 2026) berhasil di-seed.');
    }
}
