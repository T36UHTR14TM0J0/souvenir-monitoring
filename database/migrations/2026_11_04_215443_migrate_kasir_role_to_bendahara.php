<?php

use App\Models\User;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        // 1. Pastikan semua permission terdaftar
        $allPermissions = [
            'dashboard-view',
            'produk-list', 'produk-create', 'produk-edit', 'produk-delete',
            'kategori-list', 'kategori-create', 'kategori-edit', 'kategori-delete',
            'satuan-list', 'satuan-create', 'satuan-edit', 'satuan-delete',
            'penjualan-list', 'penjualan-create', 'penjualan-delete',
            'spk-list', 'spk-cetak', 'spk-status-update',
            'packing-slip-cetak',
            'pembelian-list', 'pembelian-create', 'pembelian-delete',
            'pengeluaran-list', 'pengeluaran-create', 'pengeluaran-edit', 'pengeluaran-delete',
            'kas-masuk-list', 'kas-masuk-create', 'kas-masuk-edit', 'kas-masuk-delete',
            'laporan-view', 'laporan-export',
            'user-list', 'user-create', 'user-edit', 'user-delete',
            'role-list', 'role-create', 'role-edit', 'role-delete',
            'permission-list', 'permission-create', 'permission-edit', 'permission-delete',
        ];

        foreach ($allPermissions as $perm) {
            Permission::firstOrCreate(['name' => $perm]);
        }

        // 2. Rename Role Kasir -> Bendahara
        $kasirRole = Role::where('name', 'Kasir')->first();
        if ($kasirRole) {
            $kasirRole->name = 'Bendahara';
            $kasirRole->save();
            $bendahara = $kasirRole;
        } else {
            $bendahara = Role::firstOrCreate(['name' => 'Bendahara']);
        }

        // 3. Update permissions untuk Bendahara
        $bendahara->syncPermissions([
            'dashboard-view',
            'produk-list',
            'pembelian-list',
            'pembelian-create',
            'pembelian-delete',
            'pengeluaran-list',
            'pengeluaran-create',
            'pengeluaran-edit',
            'pengeluaran-delete',
            'kas-masuk-list',
            'kas-masuk-create',
            'kas-masuk-edit',
            'kas-masuk-delete',
            'laporan-view',
            'laporan-export',
        ]);

        // 4. Update permissions untuk Admin
        $admin = Role::firstOrCreate(['name' => 'Admin']);
        $admin->syncPermissions([
            'dashboard-view',
            'produk-list',
            'produk-create',
            'produk-edit',
            'produk-delete',
            'kategori-list',
            'kategori-create',
            'kategori-edit',
            'kategori-delete',
            'satuan-list',
            'satuan-create',
            'satuan-edit',
            'satuan-delete',
            'penjualan-list',
            'penjualan-create',
            'penjualan-delete',
            'spk-list',
            'spk-cetak',
            'spk-status-update',
            'packing-slip-cetak',
            'laporan-view',
            'user-list',
            'user-create',
            'user-edit',
            'user-delete',
            'role-list',
            'permission-list',
        ]);

        // 5. Update permissions untuk Pemilik (Full Akses)
        $pemilik = Role::firstOrCreate(['name' => 'Pemilik']);
        $pemilik->syncPermissions(Permission::all());

        // 6. Migrasikan akun user kasir@gmail.com -> bendahara@gmail.com
        $userKasir = User::where('email', 'kasir@gmail.com')->first();
        if ($userKasir) {
            $userKasir->update([
                'name' => 'Bendahara Keuangan',
                'email' => 'bendahara@gmail.com',
                'password' => Hash::make('Bendahara123!'),
            ]);
            $userKasir->syncRoles(['Bendahara']);
        } else {
            $userBendahara = User::firstOrCreate(
                ['email' => 'bendahara@gmail.com'],
                [
                    'name' => 'Bendahara Keuangan',
                    'password' => Hash::make('Bendahara123!'),
                    'is_active' => true,
                ]
            );
            $userBendahara->syncRoles(['Bendahara']);
        }

        app()[PermissionRegistrar::class]->forgetCachedPermissions();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $bendahara = Role::where('name', 'Bendahara')->first();
        if ($bendahara) {
            $bendahara->name = 'Kasir';
            $bendahara->save();
        }

        $userBendahara = User::where('email', 'bendahara@gmail.com')->first();
        if ($userBendahara) {
            $userBendahara->update([
                'name' => 'Kasir 1',
                'email' => 'kasir@gmail.com',
                'password' => Hash::make('Kasir123!'),
            ]);
            $userBendahara->syncRoles(['Kasir']);
        }
    }
};
