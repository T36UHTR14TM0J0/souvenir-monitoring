<?php

namespace Database\Seeders;

use App\Models\Pembelian;
use App\Models\PembelianDetail;
use App\Models\Produk;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PembelianSeeder extends Seeder
{
    public function run(): void
    {
        $user = User::first();

        if (!$user) {
            $this->command->warn('Tidak ada user. Jalankan UserSeeder dulu.');
            return;
        }

        $produks = Produk::take(5)->get();

        if ($produks->isEmpty()) {
            $this->command->warn('Tidak ada produk.');
            return;
        }

        $suppliers = ['Toko Grosir Jaya', 'Supplier ABC', 'UD Makmur', 'CV Sumber Rejeki', 'Toko Besar'];

        for ($i = 0; $i < 15; $i++) {
            DB::beginTransaction();
            try {
                $tanggal = now()->subDays(15 - $i)->startOfDay()->addHours(rand(8, 17));

                $pembelian = Pembelian::create([
                    'kode_pembelian' => 'PBL-' . $tanggal->format('Ymd') . '-' . str_pad($i + 1, 4, '0', STR_PAD_LEFT),
                    'tanggal'        => $tanggal->format('Y-m-d'),
                    'supplier'       => $suppliers[array_rand($suppliers)],
                    'total_harga'    => 0,
                    'total_diskon'   => 0,
                    'grand_total'    => 0,
                    'catatan'        => null,
                    'user_id'        => $user->id,
                ]);
                $pembelian->created_at = $tanggal;
                $pembelian->updated_at = $tanggal;

                $totalHarga = 0;
                $pickedProduks = $produks->random(rand(2, 4));

                foreach ($pickedProduks as $produk) {
                    $qty = rand(5, 30);
                    $harga = $produk->harga_beli;
                    $subtotal = $qty * $harga;

                    PembelianDetail::create([
                        'pembelian_id' => $pembelian->id,
                        'produk_id'    => $produk->id,
                        'nama_produk'  => $produk->nama_produk,
                        'harga_beli'   => $harga,
                        'qty'          => $qty,
                        'subtotal'     => $subtotal,
                    ]);

                    $produk->increment('stok', $qty);
                    $totalHarga += $subtotal;
                }

                $diskon = (rand(1, 100) <= 15) ? rand(1, 2) * 10000 : 0;
                $grandTotal = max(0, $totalHarga - $diskon);

                $pembelian->update([
                    'total_harga'  => $totalHarga,
                    'total_diskon' => $diskon,
                    'grand_total'  => $grandTotal,
                ]);
                $pembelian->save();

                DB::commit();
            } catch (\Exception $e) {
                DB::rollBack();
                $this->command->error('Error: ' . $e->getMessage());
            }
        }

        $this->command->info('15 pembelian dummy berhasil dibuat.');
    }
}