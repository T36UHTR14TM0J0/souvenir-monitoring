<?php

namespace Database\Seeders;

use App\Models\Produk;
use App\Models\Transaksi;
use App\Models\TransaksiDetail;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TransaksiSeeder extends Seeder
{
    public function run(): void
    {
        $user = User::first();

        if (!$user) {
            $this->command->warn('⚠️  Tidak ada user. Jalankan UserSeeder dulu.');
            return;
        }

        $produks = Produk::where('stok', '>', 10)->take(5)->get();

        if ($produks->isEmpty()) {
            $this->command->warn('⚠️  Tidak ada produk dengan stok cukup.');
            return;
        }

        $metodeBayar = ['tunai', 'transfer', 'qris'];
        $pelanggan   = ['Budi', 'Siti', 'Andi', 'Rina', null, null, 'Walk-in Customer'];

        // Buat 30 transaksi dalam 30 hari terakhir
        for ($i = 0; $i < 30; $i++) {
            DB::beginTransaction();
            try {
                $tanggal = now()->subDays(30 - $i)->startOfDay()->addHours(rand(8, 20));

                $transaksi = Transaksi::create([
                    'kode_transaksi' => 'TRX-' . $tanggal->format('Ymd') . '-' . str_pad($i + 1, 4, '0', STR_PAD_LEFT),
                    'tanggal'        => $tanggal->format('Y-m-d'),
                    'nama_pelanggan' => $pelanggan[array_rand($pelanggan)],
                    'total_harga'    => 0,
                    'total_diskon'   => 0,
                    'grand_total'    => 0,
                    'metode_bayar'   => $metodeBayar[array_rand($metodeBayar)],
                    'catatan'        => null,
                    'user_id'        => $user->id,
                ]);
                $transaksi->created_at = $tanggal;
                $transaksi->updated_at = $tanggal;

                $totalHarga = 0;

                // 1-3 produk per transaksi
                $pickedProduks = $produks->random(rand(1, 3));

                foreach ($pickedProduks as $produk) {
                    $qty = rand(1, 3);
                    $harga = $produk->harga_jual;
                    $subtotal = $qty * $harga;

                    TransaksiDetail::create([
                        'transaksi_id'  => $transaksi->id,
                        'produk_id'     => $produk->id,
                        'nama_produk'   => $produk->nama_produk,
                        'harga_satuan'  => $harga,
                        'qty'           => $qty,
                        'subtotal'      => $subtotal,
                    ]);

                    // Kurangi stok
                    $produk->decrement('stok', $qty);

                    $totalHarga += $subtotal;
                }

                $diskon = (rand(1, 100) <= 20) ? rand(1, 3) * 5000 : 0;
                $grandTotal = max(0, $totalHarga - $diskon);

                $transaksi->update([
                    'total_harga'  => $totalHarga,
                    'total_diskon' => $diskon,
                    'grand_total'  => $grandTotal,
                ]);
                $transaksi->save();

                DB::commit();
            } catch (\Exception $e) {
                DB::rollBack();
                $this->command->error('Error: ' . $e->getMessage());
            }
        }

        $this->command->info('✅ 30 transaksi dummy berhasil dibuat.');
    }
}