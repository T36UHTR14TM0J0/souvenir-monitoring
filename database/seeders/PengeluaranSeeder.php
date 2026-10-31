<?php

namespace Database\Seeders;

use App\Models\Pengeluaran;
use App\Models\User;
use Illuminate\Database\Seeder;

class PengeluaranSeeder extends Seeder
{
    public function run(): void
    {
        $user = User::first();

        if (!$user) {
            $this->command->warn('Tidak ada user. Jalankan UserSeeder dulu.');
            return;
        }

        $data = [
            ['kategori' => 'listrik',      'keterangan' => 'Bayar listrik & air bulanan',      'jumlah' => [350000, 500000]],
            ['kategori' => 'gaji',         'keterangan' => 'Gaji karyawan bulanan',            'jumlah' => [2500000, 3500000]],
            ['kategori' => 'sewa',         'keterangan' => 'Sewa tempat toko',                 'jumlah' => [2000000, 2500000]],
            ['kategori' => 'operasional',  'keterangan' => 'Pembelian kantong plastik',        'jumlah' => [50000, 150000]],
            ['kategori' => 'operasional',  'keterangan' => 'Beli kertas struk & tinta',        'jumlah' => [80000, 200000]],
            ['kategori' => 'transport',    'keterangan' => 'Ongkos kirim barang',              'jumlah' => [50000, 200000]],
            ['kategori' => 'perlengkapan', 'keterangan' => 'Beli lampu & alat kebersihan',     'jumlah' => [75000, 250000]],
            ['kategori' => 'pemeliharaan', 'keterangan' => 'Servis AC & perbaikan rak',        'jumlah' => [150000, 500000]],
            ['kategori' => 'pajak',        'keterangan' => 'Pajak bumi dan bangunan',          'jumlah' => [200000, 500000]],
            ['kategori' => 'lainnya',      'keterangan' => 'Konsumsi rapat & snack',           'jumlah' => [50000, 150000]],
        ];

        for ($i = 0; $i < 20; $i++) {
            $tanggal = now()->subDays(rand(0, 60))->startOfDay()->addHours(rand(8, 17));
            $item    = $data[array_rand($data)];
            $jumlah  = rand($item['jumlah'][0], $item['jumlah'][1]);

            $pengeluaran = Pengeluaran::create([
                'kode_pengeluaran' => 'PGL-' . $tanggal->format('Ymd') . '-' . str_pad($i + 1, 4, '0', STR_PAD_LEFT),
                'tanggal'          => $tanggal->format('Y-m-d'),
                'kategori'         => $item['kategori'],
                'keterangan'       => $item['keterangan'],
                'jumlah'           => $jumlah,
                'catatan'          => null,
                'user_id'          => $user->id,
            ]);

            $pengeluaran->created_at = $tanggal;
            $pengeluaran->updated_at = $tanggal;
            $pengeluaran->save();
        }

        $this->command->info('20 pengeluaran dummy berhasil dibuat.');
    }
}