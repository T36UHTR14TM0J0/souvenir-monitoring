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
            TransaksiSeeder::class,
            PengeluaranSeeder::class,
        ]);

        // Rekalkulasi stok produk jualan (Stok Awal - Total Terjual)
        $this->command->info('Mengkalkulasi ulang stok akhir produk...');
        foreach (Produk::all() as $p) {
            $totalJual = (int) $p->transaksiDetails()->sum('qty');
            $stokAkhir = max(15, $p->stok - $totalJual);
            $p->update(['stok' => $stokAkhir]);
        }

        // Jalankan import data Excel / JSON 2025 jika filenya tersedia
        $excelPath = 'C:/Users/teguh/Downloads/KEUANGAN TAHUN 2025 (2).xlsx';
        $jsonPath = database_path('data/import_keuangan_2025.json');

        if (file_exists($excelPath)) {
            $this->command->info('Mengimpor data historis Excel 2025...');
            $this->command->call('import:keuangan-2025', [
                'file' => $excelPath,
                '--force' => true,
            ]);
        } elseif (file_exists($jsonPath)) {
            $this->command->info('Mengimpor data historis Keuangan 2025 (JSON)...');
            $this->command->call('import:keuangan-2025', [
                'file' => $jsonPath,
                '--force' => true,
            ]);
        }

        $this->command->info('Seluruh Database Seeder Monitoring Keuangan selesai dijalankan dengan sempurna!');
    }
}
