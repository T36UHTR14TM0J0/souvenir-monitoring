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
        $user = User::first();
        if (!$user) {
            $this->command->warn('⚠️ Tidak ada user untuk KasMasukSeeder.');
            return;
        }

        $kasMasukList = [
            // Tahun 2025
            [
                'tanggal'    => '2025-01-05',
                'tipe'       => 'modal_pemilik',
                'sumber'     => 'Debit / Transfer',
                'jumlah'     => 35000000,
                'keterangan' => 'Suntikan modal awal operasional toko tahun 2025 oleh pemilik',
            ],
            [
                'tanggal'    => '2025-03-20',
                'tipe'       => 'pendapatan_lain',
                'sumber'     => 'Tunai / Cash',
                'jumlah'     => 1500000,
                'keterangan' => 'Pendapatan booth mini di Festival Komik Kampus',
            ],
            [
                'tanggal'    => '2025-04-12',
                'tipe'       => 'modal_pemilik',
                'sumber'     => 'Debit / Transfer',
                'jumlah'     => 15000000,
                'keterangan' => 'Suntikan modal restock besar menjelang libur lebaran 2025',
            ],
            [
                'tanggal'    => '2025-07-15',
                'tipe'       => 'pendapatan_lain',
                'sumber'     => 'Debit / Transfer',
                'jumlah'     => 2750000,
                'keterangan' => 'Pendapatan jasa titip & pre-order event anime Jepang',
            ],
            [
                'tanggal'    => '2025-08-25',
                'tipe'       => 'modal_pemilik',
                'sumber'     => 'Debit / Transfer',
                'jumlah'     => 10000000,
                'keterangan' => 'Tambahan modal ekspansi display etalase & rak kaca baru',
            ],
            [
                'tanggal'    => '2025-11-10',
                'tipe'       => 'modal_pemilik',
                'sumber'     => 'Debit / Transfer',
                'jumlah'     => 8000000,
                'keterangan' => 'Suntikan kas cadangan persiapan event akhir tahun',
            ],

            // Tahun 2026
            [
                'tanggal'    => '2026-01-06',
                'tipe'       => 'modal_pemilik',
                'sumber'     => 'Debit / Transfer',
                'jumlah'     => 25000000,
                'keterangan' => 'Suntikan modal kerja awal tahun 2026 oleh pemilik',
            ],
            [
                'tanggal'    => '2026-03-18',
                'tipe'       => 'pendapatan_lain',
                'sumber'     => 'Tunai / Cash',
                'jumlah'     => 2000000,
                'keterangan' => 'Penjualan poster & stiker event pop-up community',
            ],
            [
                'tanggal'    => '2026-05-14',
                'tipe'       => 'modal_pemilik',
                'sumber'     => 'Debit / Transfer',
                'jumlah'     => 12000000,
                'keterangan' => 'Tambahan modal restock action figure & produk impor batch baru',
            ],
            [
                'tanggal'    => '2026-07-28',
                'tipe'       => 'pendapatan_lain',
                'sumber'     => 'Debit / Transfer',
                'jumlah'     => 3500000,
                'keterangan' => 'Cashback loyalty supplier & sponsor merchandise lokal',
            ],
            [
                'tanggal'    => '2026-08-20',
                'tipe'       => 'modal_pemilik',
                'sumber'     => 'Debit / Transfer',
                'jumlah'     => 10000000,
                'keterangan' => 'Suntikan kas peremajaan perangkat kasir & sistem toko',
            ],
            [
                'tanggal'    => '2026-10-05',
                'tipe'       => 'pendapatan_lain',
                'sumber'     => 'Tunai / Cash',
                'jumlah'     => 1800000,
                'keterangan' => 'Pendapatan jasa custom sablon kaos partai kecil',
            ],
        ];

        $counter = 1;
        foreach ($kasMasukList as $item) {
            $tgl = Carbon::parse($item['tanggal'])->startOfDay()->addHours(rand(9, 15));
            $kode = 'KM-' . $tgl->format('Ymd') . '-' . str_pad($counter++, 4, '0', STR_PAD_LEFT);

            KasMasuk::updateOrCreate(
                ['kode_transaksi' => $kode],
                [
                    'tanggal'    => $tgl->format('Y-m-d'),
                    'tipe'       => $item['tipe'],
                    'sumber'     => $item['sumber'],
                    'jumlah'     => $item['jumlah'],
                    'keterangan' => $item['keterangan'],
                    'catatan'    => null,
                    'user_id'    => $user->id,
                    'created_at' => $tgl,
                    'updated_at' => $tgl,
                ]
            );
        }

        $this->command->info('✅ KasMasukSeeder: ' . count($kasMasukList) . ' data kas masuk 2025-2026 berhasil dibuat.');
    }
}
