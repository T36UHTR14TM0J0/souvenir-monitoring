<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class RolePermissionSeeder extends Seeder
{
    public function run(): void
    {
        // Reset cache
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // ============================================
        // 1. DAFTAR PERMISSION (dikelompokkan per modul)
        // ============================================
        $permissions = [
            // Dashboard
            'dashboard-view',

            // Master Produk
            'produk-list', 'produk-create', 'produk-edit', 'produk-delete',

            // Master Kategori
            'kategori-list', 'kategori-create', 'kategori-edit', 'kategori-delete',

            // Master Satuan
            'satuan-list', 'satuan-create', 'satuan-edit', 'satuan-delete',

            // Transaksi Penjualan
            'penjualan-list', 'penjualan-create', 'penjualan-delete',

            // Transaksi Pembelian
            'pembelian-list', 'pembelian-create', 'pembelian-delete',

            // Pengeluaran
            'pengeluaran-list', 'pengeluaran-create', 'pengeluaran-edit', 'pengeluaran-delete',

            // Laporan
            'laporan-view', 'laporan-export',

            // User Management
            'user-list', 'user-create', 'user-edit', 'user-delete',

            // Role Management
            'role-list', 'role-create', 'role-edit', 'role-delete',

            // Permission Management
            'permission-list', 'permission-create', 'permission-edit', 'permission-delete',
        ];

        foreach ($permissions as $perm) {
            Permission::firstOrCreate(['name' => $perm]);
        }

        // ============================================
        // 2. ROLE: PEMILIK (full akses)
        // ============================================
        $pemilik = Role::firstOrCreate(['name' => 'Pemilik']);
        $pemilik->syncPermissions(Permission::all());

        // ============================================
        // 3. ROLE: ADMIN (master data + user)
        // ============================================
        $admin = Role::firstOrCreate(['name' => 'Admin']);
        $admin->syncPermissions([
            'dashboard-view',
            'produk-list', 'produk-create', 'produk-edit', 'produk-delete',
            'kategori-list', 'kategori-create', 'kategori-edit', 'kategori-delete',
            'satuan-list', 'satuan-create', 'satuan-edit', 'satuan-delete',
            'penjualan-list', 'pembelian-list', 'pengeluaran-list',
            'laporan-view', 'laporan-export',
            'user-list', 'user-create', 'user-edit', 'user-delete',
            'role-list',
            'permission-list',
        ]);

        // ============================================
        // 4. ROLE: KASIR (transaksi saja)
        // ============================================
        $kasir = Role::firstOrCreate(['name' => 'Kasir']);
        $kasir->syncPermissions([
            'dashboard-view',
            'produk-list', 'kategori-list', 'satuan-list',
            'penjualan-list', 'penjualan-create',
            'pembelian-list',
            'pengeluaran-list',
        ]);

        // ============================================
        // 5. USER DEFAULT
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

        $kasirUser = User::firstOrCreate(
            ['email' => 'kasir@gmail.com'],
            [
                'name' => 'Kasir 1',
                'password' => Hash::make('Kasir123!'),
                'is_active' => true,
            ]
        );
        $kasirUser->syncRoles(['Kasir']);

        $this->command->info('✅ Role, Permission, dan User berhasil di-seed!');
    }
}
