<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\KasMasukController;
use App\Http\Controllers\KategoriController;
use App\Http\Controllers\LaporanKeuanganController;
use App\Http\Controllers\PengeluaranController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\ProdukController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\SatuanController;
use App\Http\Controllers\TransaksiPenjualanController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

// Auth
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::middleware(['auth', 'user.active'])->group(function () {
    Route::get('/', fn () => redirect('/dashboard'));
    Route::get('/dashboard', [DashboardController::class, 'index'])
        ->middleware('permission:dashboard-view')
        ->name('dashboard');

    // ============================================
    // USER
    // ============================================
    Route::middleware('permission:user-list')->group(function () {
        Route::get('/users', [UserController::class, 'index'])->name('users.index');
    });
    Route::middleware('permission:user-create')->group(function () {
        Route::get('/users/create', [UserController::class, 'create'])->name('users.create');
        Route::post('/users', [UserController::class, 'store'])->name('users.store');
    });
    Route::middleware('permission:user-edit')->group(function () {
        Route::get('/users/{user}/edit', [UserController::class, 'edit'])->name('users.edit');
        Route::put('/users/{user}', [UserController::class, 'update'])->name('users.update');
        Route::patch('/users/{user}/toggle-active', [UserController::class, 'toggleActive'])->name('users.toggle-active');
    });
    Route::middleware('permission:user-delete')->group(function () {
        Route::delete('/users/{user}', [UserController::class, 'destroy'])->name('users.destroy');
    });

    // ============================================
    // ROLE
    // ============================================
    Route::middleware('permission:role-list')->group(function () {
        Route::get('/roles', [RoleController::class, 'index'])->name('roles.index');
    });
    Route::middleware('permission:role-create')->group(function () {
        Route::get('/roles/create', [RoleController::class, 'create'])->name('roles.create');
        Route::post('/roles', [RoleController::class, 'store'])->name('roles.store');
    });
    Route::middleware('permission:role-edit')->group(function () {
        Route::get('/roles/{role}/edit', [RoleController::class, 'edit'])->name('roles.edit');
        Route::put('/roles/{role}', [RoleController::class, 'update'])->name('roles.update');
    });
    Route::middleware('permission:role-delete')->group(function () {
        Route::delete('/roles/{role}', [RoleController::class, 'destroy'])->name('roles.destroy');
    });

    // ============================================
    // PERMISSION
    // ============================================
    Route::middleware('permission:permission-list')->group(function () {
        Route::get('/permissions', [PermissionController::class, 'index'])->name('permissions.index');
    });
    Route::middleware('permission:permission-create')->group(function () {
        Route::post('/permissions', [PermissionController::class, 'store'])->name('permissions.store');
    });
    Route::middleware('permission:permission-edit')->group(function () {
        Route::put('/permissions/{permission}', [PermissionController::class, 'update'])->name('permissions.update');
    });
    Route::middleware('permission:permission-delete')->group(function () {
        Route::delete('/permissions/{permission}', [PermissionController::class, 'destroy'])->name('permissions.destroy');
    });

    // ============================================
    // KATEGORI PRODUK
    // ============================================
    Route::middleware('permission:kategori-list')->group(function () {
        Route::get('/kategori', [KategoriController::class, 'index'])->name('kategori.index');
    });
    Route::middleware('permission:kategori-create')->group(function () {
        Route::get('/kategori/create', [KategoriController::class, 'create'])->name('kategori.create');
        Route::post('/kategori', [KategoriController::class, 'store'])->name('kategori.store');
    });
    Route::middleware('permission:kategori-edit')->group(function () {
        Route::get('/kategori/{kategori}/edit', [KategoriController::class, 'edit'])->name('kategori.edit');
        Route::put('/kategori/{kategori}', [KategoriController::class, 'update'])->name('kategori.update');
    });
    Route::middleware('permission:kategori-delete')->group(function () {
        Route::delete('/kategori/{kategori}', [KategoriController::class, 'destroy'])->name('kategori.destroy');
    });

    // ============================================
    // SATUAN PRODUK
    // ============================================
    Route::middleware('permission:satuan-list')->group(function () {
        Route::get('/satuan', [SatuanController::class, 'index'])->name('satuan.index');
    });
    Route::middleware('permission:satuan-create')->group(function () {
        Route::get('/satuan/create', [SatuanController::class, 'create'])->name('satuan.create');
        Route::post('/satuan', [SatuanController::class, 'store'])->name('satuan.store');
    });
    Route::middleware('permission:satuan-edit')->group(function () {
        Route::get('/satuan/{satuan}/edit', [SatuanController::class, 'edit'])->name('satuan.edit');
        Route::put('/satuan/{satuan}', [SatuanController::class, 'update'])->name('satuan.update');
    });
    Route::middleware('permission:satuan-delete')->group(function () {
        Route::delete('/satuan/{satuan}', [SatuanController::class, 'destroy'])->name('satuan.destroy');
    });

    // ============================================
    // PRODUK
    // ============================================
    Route::middleware('permission:produk-list')->group(function () {
        Route::get('/produk', [ProdukController::class, 'index'])->name('produk.index');
    });
    Route::middleware('permission:produk-create')->group(function () {
        Route::get('/produk/create', [ProdukController::class, 'create'])->name('produk.create');
        Route::post('/produk', [ProdukController::class, 'store'])->name('produk.store');
    });
    Route::middleware('permission:produk-edit')->group(function () {
        Route::get('/produk/{produk}/edit', [ProdukController::class, 'edit'])->name('produk.edit');
        Route::put('/produk/{produk}', [ProdukController::class, 'update'])->name('produk.update');
    });
    Route::middleware('permission:produk-delete')->group(function () {
        Route::delete('/produk/{produk}', [ProdukController::class, 'destroy'])->name('produk.destroy');
    });
    Route::middleware('permission:produk-list')->group(function () {
        Route::get('/produk/{produk}', [ProdukController::class, 'show'])->name('produk.show');
    });

    // ============================================
    // TRANSAKSI PENJUALAN
    // ⭐ URUTAN: index → create/store → show/destroy (wildcard di paling bawah)
    // ============================================
    Route::middleware('permission:penjualan-list')->group(function () {
        Route::get('/transaksi-penjualan', [TransaksiPenjualanController::class, 'index'])
            ->name('transaksi-penjualan.index');
    });

    Route::middleware('permission:penjualan-create')->group(function () {
        Route::get('/transaksi-penjualan/create', [TransaksiPenjualanController::class, 'create'])
            ->name('transaksi-penjualan.create');
        Route::post('/transaksi-penjualan', [TransaksiPenjualanController::class, 'store'])
            ->name('transaksi-penjualan.store');
        Route::post('/transaksi-penjualan/import-shopee', [TransaksiPenjualanController::class, 'importShopee'])
            ->name('transaksi-penjualan.import-shopee');
    });

    Route::middleware('permission:penjualan-list')->group(function () {
        Route::get('/transaksi-penjualan/{transaksiPenjualan}', [TransaksiPenjualanController::class, 'show'])
            ->name('transaksi-penjualan.show');
        Route::get('/transaksi-penjualan/{transaksiPenjualan}/cetak-struk', [TransaksiPenjualanController::class, 'cetakStruk'])
            ->name('transaksi-penjualan.cetak-struk');
        Route::patch('/transaksi-penjualan/{transaksiPenjualan}/update-status', [TransaksiPenjualanController::class, 'updateStatus'])
            ->name('transaksi-penjualan.update-status');
    });

    Route::middleware('permission:penjualan-delete')->group(function () {
        Route::delete('/transaksi-penjualan/{transaksiPenjualan}', [TransaksiPenjualanController::class, 'destroy'])
            ->name('transaksi-penjualan.destroy');
    });

    Route::middleware('permission:pengeluaran-list')->group(function () {
        Route::get('/pengeluaran', [PengeluaranController::class, 'index'])
            ->name('pengeluaran.index');
    });

    Route::middleware('permission:pengeluaran-create')->group(function () {
        Route::get('/pengeluaran/create', [PengeluaranController::class, 'create'])
            ->name('pengeluaran.create');
        Route::post('/pengeluaran', [PengeluaranController::class, 'store'])
            ->name('pengeluaran.store');
    });

    Route::middleware('permission:pengeluaran-list')->group(function () {
        Route::get('/pengeluaran/{pengeluaran}', [PengeluaranController::class, 'show'])
            ->name('pengeluaran.show');
    });

    Route::middleware('permission:pengeluaran-edit')->group(function () {
        Route::get('/pengeluaran/{pengeluaran}/edit', [PengeluaranController::class, 'edit'])
            ->name('pengeluaran.edit');
        Route::put('/pengeluaran/{pengeluaran}', [PengeluaranController::class, 'update'])
            ->name('pengeluaran.update');
        Route::patch('/pengeluaran/{pengeluaran}/toggle-kasbon', [PengeluaranController::class, 'toggleKasbon'])
            ->name('pengeluaran.toggle-kasbon');
    });

    Route::middleware('permission:pengeluaran-delete')->group(function () {
        Route::delete('/pengeluaran/{pengeluaran}', [PengeluaranController::class, 'destroy'])
            ->name('pengeluaran.destroy');
    });

    // ============================================
    // KAS MASUK & MODAL PEMILIK
    // ============================================
    Route::middleware('permission:kas-masuk-list|pengeluaran-list')->group(function () {
        Route::get('/kas-masuk', [KasMasukController::class, 'index'])->name('kas-masuk.index');
    });
    Route::middleware('permission:kas-masuk-create|pengeluaran-create')->group(function () {
        Route::get('/kas-masuk/create', [KasMasukController::class, 'create'])->name('kas-masuk.create');
        Route::post('/kas-masuk', [KasMasukController::class, 'store'])->name('kas-masuk.store');
    });
    Route::middleware('permission:kas-masuk-edit|pengeluaran-edit')->group(function () {
        Route::get('/kas-masuk/{kasMasuk}/edit', [KasMasukController::class, 'edit'])->name('kas-masuk.edit');
        Route::put('/kas-masuk/{kasMasuk}', [KasMasukController::class, 'update'])->name('kas-masuk.update');
    });
    Route::middleware('permission:kas-masuk-delete|pengeluaran-delete')->group(function () {
        Route::delete('/kas-masuk/{kasMasuk}', [KasMasukController::class, 'destroy'])->name('kas-masuk.destroy');
    });

    Route::middleware('permission:laporan-view|laporan-laba-rugi|laporan-penjualan|laporan-pembelian|laporan-pengeluaran|laporan-produksi|laporan-pribadi|laporan-rekonsiliasi|laporan-kasbon')->group(function () {
        Route::get('/laporan/keuangan', [LaporanKeuanganController::class, 'index'])
            ->name('laporan.keuangan');
    });

    Route::middleware('permission:laporan-export|laporan-view')->group(function () {
        Route::get('/laporan/keuangan/export-pdf', [LaporanKeuanganController::class, 'exportPdf'])
            ->name('laporan.keuangan.export-pdf');
        Route::get('/laporan/keuangan/export-excel', [LaporanKeuanganController::class, 'exportExcel'])
            ->name('laporan.keuangan.export-excel');
        Route::get('/laporan/keuangan/export-excel-klien', [LaporanKeuanganController::class, 'exportExcelFormatKlien'])
            ->name('laporan.keuangan.export-excel-klien');
    });
});
