<?php

namespace Database\Seeders;

use App\Models\Produk;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            RolePermissionSeeder::class,
            KategoriSeeder::class,
            SatuanSeeder::class,
            ProdukSeeder::class,
            KasMasukSeeder::class,
            PembelianSeeder::class,
            TransaksiSeeder::class,
            PengeluaranSeeder::class,
        ]);

        // Rekalkulasi stok akhir produk agar stok realistis (Stok Awal + Total Masuk - Total Terjual)
        $this->command->info('🔄 Mengkalkulasi ulang stok akhir produk...');
        foreach (Produk::all() as $p) {
            $totalBeli = (int) $p->pembelianDetails()->sum('qty');
            $totalJual = (int) $p->transaksiDetails()->sum('qty');
            $stokAkhir = max(15, ($p->stok + $totalBeli) - $totalJual);
            $p->update(['stok' => $stokAkhir]);
        }
        $this->command->info('✅ Seluruh Database Seeder 2025-2026 selesai dijalankan dengan sempurna!');
    }
}
