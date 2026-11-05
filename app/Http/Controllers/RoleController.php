<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleController extends Controller
{
    /**
     * Role default sistem yang dilindungi (tidak boleh dihapus / diubah namanya).
     */
    public const PROTECTED_ROLES = ['Pemilik', 'Admin', 'Bendahara'];

    /**
     * Metadata deskripsi bisnis untuk masing-masing role utama.
     */
    public const ROLE_META = [
        'Pemilik' => [
            'badge_bg' => '#eef2ff',
            'badge_color' => '#4f46e5',
            'badge_border' => '#c7d2fe',
            'icon' => 'fas fa-crown',
            'label' => 'Owner / Super Admin',
            'desc' => 'Akses penuh 100% ke seluruh menu: Dashboard, Pesanan & Produk, Master Data, Keuangan & Kas, 4 Laporan & Analitik, serta Pengaturan Akses (User, Role, Permission).',
        ],
        'Bendahara' => [
            'badge_bg' => '#ecfdf5',
            'badge_color' => '#059669',
            'badge_border' => '#a7f3d0',
            'icon' => 'fas fa-vault',
            'label' => 'Keuangan, Kas & Belanja Bahan',
            'desc' => 'Mengelola penuh Pencairan Saldo & Modal, Biaya Operasional/Modal Bahan/Prive/Kasbon, audit read-only Pesanan & Katalog, serta akses penuh 4 Laporan & Export PDF/Excel.',
        ],
        'Admin' => [
            'badge_bg' => '#e0f2fe',
            'badge_color' => '#0284c7',
            'badge_border' => '#bae6fd',
            'icon' => 'fas fa-headset',
            'label' => 'Operasional Marketplace & Katalog',
            'desc' => 'Mengelola penuh Pesanan Marketplace, Katalog Souvenir, Master Kategori & Satuan, serta Laporan Penjualan Marketplace.',
        ],
    ];

    /**
     * Metadata modul permission 1-to-1 sesuai urutan menu di Sidebar.
     */
    public const MODULE_META = [
        'dashboard' => ['label' => '1. Menu Utama: Dashboard', 'icon' => 'fas fa-th-large'],
        'penjualan' => ['label' => '2. Pesanan & Produk: Pesanan Marketplace', 'icon' => 'fas fa-boxes-packing'],
        'produk' => ['label' => '3. Pesanan & Produk: Katalog Souvenir', 'icon' => 'fas fa-gift'],
        'kategori' => ['label' => '4. Master Data: Kategori', 'icon' => 'fas fa-tags'],
        'satuan' => ['label' => '5. Master Data: Satuan', 'icon' => 'fas fa-ruler-combined'],
        'kas-masuk' => ['label' => '6. Keuangan & Kas: Pencairan Saldo & Modal', 'icon' => 'fas fa-hand-holding-dollar'],
        'pengeluaran' => ['label' => '7. Keuangan & Kas: Biaya Operasional & Bahan', 'icon' => 'fas fa-credit-card'],
        'laporan' => ['label' => '8. Laporan & Analitik (4 Menu Laporan & Export)', 'icon' => 'fas fa-chart-line'],
        'user' => ['label' => '9. Pengaturan Akses: Manajemen User', 'icon' => 'fas fa-users'],
        'role' => ['label' => '10. Pengaturan Akses: Role', 'icon' => 'fas fa-user-shield'],
        'permission' => ['label' => '11. Pengaturan Akses: Permission', 'icon' => 'fas fa-key'],
    ];

    /**
     * Deskripsi Bahasa Indonesia untuk setiap kode permission agar mudah dipahami di UI.
     */
    public const PERMISSION_LABELS = [
        // Dashboard
        'dashboard-view' => 'Lihat Dashboard Ringkasan Toko',

        // Pesanan Marketplace
        'penjualan-list' => 'Lihat Daftar & Detail Pesanan Marketplace',
        'penjualan-create' => 'Input Pesanan Baru & Import Shopee',
        'penjualan-edit' => 'Update Status Pesanan, Resi & Cetak Slip',
        'penjualan-delete' => 'Hapus Transaksi Pesanan Marketplace',

        // Katalog Souvenir
        'produk-list' => 'Lihat Katalog & Detail Souvenir',
        'produk-create' => 'Tambah Produk Souvenir Baru',
        'produk-edit' => 'Edit Data Souvenir, Harga & Diskon',
        'produk-delete' => 'Hapus Produk Souvenir',

        // Master Kategori
        'kategori-list' => 'Lihat Daftar Kategori',
        'kategori-create' => 'Tambah Kategori Baru',
        'kategori-edit' => 'Edit Kategori',
        'kategori-delete' => 'Hapus Kategori',

        // Master Satuan
        'satuan-list' => 'Lihat Daftar Satuan Unit',
        'satuan-create' => 'Tambah Satuan Baru',
        'satuan-edit' => 'Edit Satuan',
        'satuan-delete' => 'Hapus Satuan',

        // Pencairan Saldo & Modal
        'kas-masuk-list' => 'Lihat Daftar Pencairan Saldo & Modal Masuk',
        'kas-masuk-create' => 'Catat Setoran Modal / Pencairan Saldo Baru',
        'kas-masuk-edit' => 'Edit Data Kas Masuk',
        'kas-masuk-delete' => 'Hapus Data Kas Masuk',

        // Biaya Operasional & Bahan
        'pengeluaran-list' => 'Lihat Daftar Biaya Operasional, Modal Bahan & Kasbon',
        'pengeluaran-create' => 'Catat Pengeluaran Operasional / Modal Bahan / Kasbon',
        'pengeluaran-edit' => 'Edit Pengeluaran & Tandai Lunas Kasbon',
        'pengeluaran-delete' => 'Hapus Data Pengeluaran',

        // Laporan & Analitik (4 Menu Sidebar)
        'laporan-view' => 'Akses Modul Laporan & Analitik',
        'laporan-laba-rugi' => 'Menu: Laporan Laba/Rugi Bisnis',
        'laporan-penjualan' => 'Menu: Laporan Penjualan MP',
        'laporan-pengeluaran' => 'Menu: Laporan Pengeluaran & Belanja Bahan',
        'laporan-kasbon' => 'Menu: Laporan Kasbon Tim',
        'laporan-export' => 'Tombol Export PDF & Export Excel Laporan',

        // Pengaturan Akses
        'user-list' => 'Menu: Lihat Daftar Manajemen User',
        'user-create' => 'Tambah User Baru',
        'user-edit' => 'Edit User & Aktif/Nonaktifkan Akun',
        'user-delete' => 'Hapus User',

        'role-list' => 'Menu: Lihat Daftar Role & Hak Akses',
        'role-create' => 'Tambah Role Baru',
        'role-edit' => 'Edit Role & Atur Matriks Permission',
        'role-delete' => 'Hapus Role Kustom',

        'permission-list' => 'Menu: Lihat Daftar Permission',
        'permission-create' => 'Tambah Kode Permission Baru',
        'permission-edit' => 'Edit Kode Permission',
        'permission-delete' => 'Hapus Kode Permission',
    ];

    /**
     * Ekstrak nama grup modul dari nama permission (contoh: 'penerimaan-produk-list' -> 'penerimaan-produk').
     */
    public static function extractModuleGroup(string $permissionName): string
    {
        // Urutkan key dari yang terpanjang agar 'penerimaan-produk' dicek sebelum 'produk'
        $keys = array_keys(self::MODULE_META);
        usort($keys, fn (string $a, string $b) => strlen($b) <=> strlen($a));

        foreach ($keys as $moduleKey) {
            if ($permissionName === $moduleKey || str_starts_with($permissionName, $moduleKey.'-')) {
                return $moduleKey;
            }
        }

        return explode('-', $permissionName)[0];
    }

    /**
     * Urutkan koleksi grup permission sesuai urutan menu di Sidebar.
     */
    public static function sortPermissionGroups($groupedPermissions)
    {
        $order = array_keys(self::MODULE_META);

        return $groupedPermissions->sortBy(function ($items, $key) use ($order) {
            $pos = array_search($key, $order, true);

            return $pos === false ? 999 : $pos;
        });
    }

    /**
     * Display a listing of the roles.
     */
    public function index(Request $request)
    {
        $query = Role::with('permissions')->withCount('users');

        if ($request->filled('search')) {
            $query->where('name', 'like', '%'.trim((string) $request->search).'%');
        }

        $roles = $query->orderByRaw("CASE WHEN name = 'Pemilik' THEN 1 WHEN name = 'Bendahara' THEN 2 WHEN name = 'Admin' THEN 3 ELSE 4 END")
            ->orderBy('name')
            ->paginate(10)
            ->withQueryString();

        $totalRoles = Role::count();
        $totalProtectedRoles = count(self::PROTECTED_ROLES);
        $totalPermissions = Permission::count();
        $totalAssignedUsers = User::whereHas('roles')->count();
        $moduleMeta = self::MODULE_META;
        $roleMeta = self::ROLE_META;

        return view('roles.index', compact(
            'roles',
            'totalRoles',
            'totalProtectedRoles',
            'totalPermissions',
            'totalAssignedUsers',
            'moduleMeta',
            'roleMeta'
        ));
    }

    /**
     * Show the form for creating a new role.
     */
    public function create()
    {
        $grouped = Permission::orderBy('id')->get()
            ->groupBy(fn (Permission $p) => self::extractModuleGroup($p->name));

        $permissions = self::sortPermissionGroups($grouped);
        $moduleMeta = self::MODULE_META;
        $permLabels = self::PERMISSION_LABELS;

        return view('roles.create', compact('permissions', 'moduleMeta', 'permLabels'));
    }

    /**
     * Store a newly created role in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
                'unique:roles,name',
                'regex:/^[A-Za-z0-9_\-\s]+$/',
            ],
            'permissions' => [
                'nullable',
                'array',
            ],
            'permissions.*' => [
                'string',
                'exists:permissions,name',
            ],
        ], $this->validationMessages(), $this->validationAttributes());

        $name = trim($validated['name']);

        $role = Role::create(['name' => $name]);
        $role->syncPermissions($validated['permissions'] ?? []);

        return redirect()
            ->route('roles.index')
            ->with('success', "Role \"{$role->name}\" berhasil ditambahkan.");
    }

    /**
     * Show the form for editing the specified role.
     */
    public function edit(Role $role)
    {
        $grouped = Permission::orderBy('id')->get()
            ->groupBy(fn (Permission $p) => self::extractModuleGroup($p->name));

        $permissions = self::sortPermissionGroups($grouped);
        $moduleMeta = self::MODULE_META;
        $permLabels = self::PERMISSION_LABELS;
        $isProtected = in_array($role->name, self::PROTECTED_ROLES, true);

        return view('roles.edit', compact('role', 'permissions', 'moduleMeta', 'permLabels', 'isProtected'));
    }

    /**
     * Update the specified role in storage.
     */
    public function update(Request $request, Role $role)
    {
        $validated = $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('roles', 'name')->ignore($role->id),
                'regex:/^[A-Za-z0-9_\-\s]+$/',
            ],
            'permissions' => [
                'nullable',
                'array',
            ],
            'permissions.*' => [
                'string',
                'exists:permissions,name',
            ],
        ], $this->validationMessages(), $this->validationAttributes());

        $name = trim($validated['name']);

        $isProtected = in_array($role->name, self::PROTECTED_ROLES, true);
        if ($isProtected && strcasecmp($name, $role->name) !== 0) {
            return back()
                ->withInput()
                ->with('error', "Role default \"{$role->name}\" tidak bisa diganti namanya.");
        }

        if (! $isProtected) {
            $role->update(['name' => $name]);
        }

        $role->syncPermissions($validated['permissions'] ?? []);

        return redirect()
            ->route('roles.index')
            ->with('success', "Hak akses untuk role \"{$role->name}\" berhasil diperbarui.");
    }

    /**
     * Remove the specified role from storage.
     */
    public function destroy(Role $role)
    {
        $usersCount = $role->users()->count();
        if ($usersCount > 0) {
            return back()->with(
                'error',
                "Role \"{$role->name}\" masih digunakan oleh {$usersCount} user. Pindahkan user terlebih dahulu."
            );
        }

        if (in_array($role->name, self::PROTECTED_ROLES, true)) {
            return back()->with('error', "Role default \"{$role->name}\" tidak bisa dihapus.");
        }

        $name = $role->name;
        $role->delete();

        return redirect()
            ->route('roles.index')
            ->with('success', "Role \"{$name}\" berhasil dihapus.");
    }

    /**
     * Custom validation messages (Bahasa Indonesia).
     */
    private function validationMessages(): array
    {
        return [
            'name.required' => 'Nama role wajib diisi.',
            'name.string' => 'Nama role harus berupa teks.',
            'name.max' => 'Nama role maksimal 255 karakter.',
            'name.unique' => 'Nama role sudah digunakan, gunakan nama lain.',
            'name.regex' => 'Nama role hanya boleh berisi huruf, angka, spasi, tanda hubung (-), dan garis bawah (_).',

            'permissions.array' => 'Format permission tidak valid.',
            'permissions.*.string' => 'Setiap permission harus berupa teks.',
            'permissions.*.exists' => 'Permission yang dipilih tidak ditemukan.',
        ];
    }

    /**
     * Custom attribute names.
     */
    private function validationAttributes(): array
    {
        return [
            'name' => 'Nama Role',
            'permissions' => 'Permission',
            'permissions.*' => 'Permission',
        ];
    }
}
