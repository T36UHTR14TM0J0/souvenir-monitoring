<?php

namespace Database\Seeders;

use App\Models\Satuan;
use Illuminate\Database\Seeder;

class SatuanSeeder extends Seeder
{
    public function run(): void
    {
        $satuans = [
            [
                'nama_satuan' => 'Pieces',
                'singkatan'   => 'pcs',
                'deskripsi'   => 'Satuan per buah atau biji tunggal',
            ],
            [
                'nama_satuan' => 'Box',
                'singkatan'   => 'box',
                'deskripsi'   => 'Satuan kotak atau kardus kemasan',
            ],
            [
                'nama_satuan' => 'Lusin',
                'singkatan'   => 'lsn',
                'deskripsi'   => 'Paket berjumlah 12 buah item',
            ],
            [
                'nama_satuan' => 'Pack',
                'singkatan'   => 'pck',
                'deskripsi'   => 'Satuan paket atau kantong bungkus',
            ],
            [
                'nama_satuan' => 'Lembar',
                'singkatan'   => 'lbr',
                'deskripsi'   => 'Satuan helai atau lembaran poster dan stiker',
            ],
            [
                'nama_satuan' => 'Rim',
                'singkatan'   => 'rim',
                'deskripsi'   => 'Satuan cetak kertas 500 lembar',
            ],
            [
                'nama_satuan' => 'Set',
                'singkatan'   => 'set',
                'deskripsi'   => 'Paket beberapa item dalam satu kesatuan',
            ],
            [
                'nama_satuan' => 'Botol',
                'singkatan'   => 'btl',
                'deskripsi'   => 'Satuan kemasan botol atau tumbler',
            ],
            [
                'nama_satuan' => 'Roll',
                'singkatan'   => 'roll',
                'deskripsi'   => 'Satuan gulungan pita, kain, atau stiker rol',
            ],
        ];

        foreach ($satuans as $item) {
            Satuan::firstOrCreate(
                ['nama_satuan' => $item['nama_satuan']],
                [
                    'singkatan' => $item['singkatan'],
                    'deskripsi' => $item['deskripsi'],
                ]
            );
        }

        $this->command->info('✅ SatuanSeeder: 9 Satuan berhasil dibuat/disinkronkan.');
    }
}
