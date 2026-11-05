<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class RolePermissionSeeder extends Seeder
{
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        // ============================================
        // 1. DAFTAR PERMISSION 1-TO-1 SESUAI MENU SIDEBAR APLIKASI
        // ============================================
        $permissions = [
            // [Menu Utama] 1. Dashboard
            'dashboard-view',

            // [Pesanan & Produk] 2. Pesanan Marketplace
            'penjualan-list',
            'penjualan-create',
            'penjualan-edit',
            'penjualan-delete',

            // [Pesanan & Produk] 3. Katalog Souvenir
            'produk-list',
            'produk-create',
            'produk-edit',
            'produk-delete',

            // [Master Data] 4. Kategori
            'kategori-list',
            'kategori-create',
            'kategori-edit',
            'kategori-delete',

            // [Master Data] 5. Satuan
            'satuan-list',
            'satuan-create',
            'satuan-edit',
            'satuan-delete',

            // [Keuangan & Kas] 6. Pencairan Saldo & Modal
            'kas-masuk-list',
            'kas-masuk-create',
            'kas-masuk-edit',
            'kas-masuk-delete',

            // [Keuangan & Kas] 7. Biaya Operasional & Bahan
            'pengeluaran-list',
            'pengeluaran-create',
            'pengeluaran-edit',
            'pengeluaran-delete',

            // [Laporan & Analitik] 8. 4 Menu Laporan & Export
            'laporan-view',
            'laporan-laba-rugi',
            'laporan-penjualan',
            'laporan-pengeluaran',
            'laporan-kasbon',
            'laporan-export',

            // [Pengaturan Akses] 9. Manajemen User
            'user-list',
            'user-create',
            'user-edit',
            'user-delete',

            // [Pengaturan Akses] 10. Role
            'role-list',
            'role-create',
            'role-edit',
            'role-delete',

            // [Pengaturan Akses] 11. Permission
            'permission-list',
            'permission-create',
            'permission-edit',
            'permission-delete',
        ];

        // Bersihkan permission lama yang sudah tidak sesuai dengan menu aplikasi
        Permission::whereNotIn('name', $permissions)->delete();

        foreach ($permissions as $perm) {
            Permission::firstOrCreate(['name' => $perm, 'guard_name' => 'web']);
        }

        // ============================================
        // 2. ROLE: PEMILIK (Owner / Super Admin — Akses Penuh 100% Semua Menu)
        // ============================================
        $pemilik = Role::firstOrCreate(['name' => 'Pemilik', 'guard_name' => 'web']);
        $pemilik->syncPermissions(Permission::all());

        // ============================================
        // 3. ROLE: BENDAHARA (Keuangan, Kas, Biaya Bahan & Laporan Keuangan)
        // ============================================
        $bendahara = Role::firstOrCreate(['name' => 'Bendahara', 'guard_name' => 'web']);
        $bendahara->syncPermissions([
            // Menu Utama: Dashboard
            'dashboard-view',

            // Pesanan & Produk (Read-Only)
            'penjualan-list',
            'produk-list',

            // Master Data (Read-Only)
            'kategori-list',
            'satuan-list',

            // Keuangan & Kas (Full CRUD Pencairan Saldo & Modal + Biaya Operasional & Bahan)
            'kas-masuk-list',
            'kas-masuk-create',
            'kas-masuk-edit',
            'kas-masuk-delete',
            'pengeluaran-list',
            'pengeluaran-create',
            'pengeluaran-edit',
            'pengeluaran-delete',

            // Laporan & Analitik (Akses Penuh ke-4 Menu Laporan + Export PDF & Excel)
            'laporan-view',
            'laporan-laba-rugi',
            'laporan-penjualan',
            'laporan-pengeluaran',
            'laporan-kasbon',
            'laporan-export',
        ]);

        // ============================================
        // 4. ROLE: ADMIN (Operasional Marketplace, Katalog & Laporan Penjualan)
        // ============================================
        $admin = Role::firstOrCreate(['name' => 'Admin', 'guard_name' => 'web']);
        $admin->syncPermissions([
            // Menu Utama: Dashboard
            'dashboard-view',

            // Pesanan & Produk (Full CRUD Pesanan Marketplace & Katalog Souvenir)
            'penjualan-list',
            'penjualan-create',
            'penjualan-edit',
            'penjualan-delete',
            'produk-list',
            'produk-create',
            'produk-edit',
            'produk-delete',

            // Master Data (Full CRUD Kategori & Satuan)
            'kategori-list',
            'kategori-create',
            'kategori-edit',
            'kategori-delete',
            'satuan-list',
            'satuan-create',
            'satuan-edit',
            'satuan-delete',

            // Laporan & Analitik (Khusus Laporan Operasional: Penjualan MP)
            'laporan-view',
            'laporan-penjualan',
        ]);

        // ============================================
        // 5. USER DEFAULT (3 Pengguna Utama)
        // ============================================
        $owner = User::firstOrCreate(
            ['email' => 'pemilik@gmail.com'],
            [
                'name' => 'Pemilik Toko',
                'password' => Hash::make('Pemilik123!'),
                'is_active' => true,
            ]
        );
        $owner->syncRoles(['Pemilik']);

        $adminUser = User::firstOrCreate(
            ['email' => 'admin@gmail.com'],
            [
                'name' => 'Admin Toko',
                'password' => Hash::make('Admin123!'),
                'is_active' => true,
            ]
        );
        $adminUser->syncRoles(['Admin']);

        $bendaharaUser = User::firstOrCreate(
            ['email' => 'bendahara@gmail.com'],
            [
                'name' => 'Bendahara Keuangan',
                'password' => Hash::make('Bendahara123!'),
                'is_active' => true,
            ]
        );
        $bendaharaUser->syncRoles(['Bendahara']);

        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        if ($this->command) {
            $this->command->info('Role (Pemilik, Bendahara, Admin), Permission 1-to-1 Menu Sidebar, dan User berhasil di-seed!');
        }
    }
}
