<?php

namespace Tests\Feature;

use App\Models\Kategori;
use App\Models\Pembelian;
use App\Models\Produk;
use App\Models\Satuan;
use App\Models\User;
use Database\Seeders\KategoriSeeder;
use Database\Seeders\ProdukSeeder;
use Database\Seeders\RolePermissionSeeder;
use Database\Seeders\SatuanSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class OnlineProcurementAndMaterialsTest extends TestCase
{
    use RefreshDatabase;

    private User $bendahara;

    private User $admin;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed([
            RolePermissionSeeder::class,
            KategoriSeeder::class,
            SatuanSeeder::class,
            ProdukSeeder::class,
        ]);

        $this->bendahara = User::where('email', 'bendahara@gmail.com')->first();
        $this->admin = User::where('email', 'admin@gmail.com')->first();
    }

    public function test_materials_and_finished_souvenirs_are_separated(): void
    {
        $responseBahan = $this->actingAs($this->bendahara)->get(route('bahan-baku.index', ['search' => 'Mug Polos']));
        $responseBahan->assertStatus(200);
        $responseBahan->assertSee('Mug Polos Keramik Sublimasi SNI Super White');
        $responseBahan->assertDontSee('Mug Keramik Custom Anime Jibranime (SNI)');

        $responseProduk = $this->actingAs($this->admin)->get(route('produk.index'));
        $responseProduk->assertStatus(200);
        $responseProduk->assertSee('Mug Keramik Custom Anime Jibranime (SNI)');
        $responseProduk->assertDontSee('Mug Polos Keramik Sublimasi SNI Super White');
    }

    public function test_bahan_baku_pagination_renders_navigation_links(): void
    {
        $response = $this->actingAs($this->bendahara)->get(route('bahan-baku.index'));
        $response->assertStatus(200);
        $response->assertSee('pagination-wrapper');
        $response->assertSee('perm-pagination');
        $response->assertSee('Menampilkan');
        $response->assertSee('page=2');

        // Navigasi ke halaman 2
        $responsePage2 = $this->actingAs($this->bendahara)->get(route('bahan-baku.index', ['page' => 2]));
        $responsePage2->assertStatus(200);
        $responsePage2->assertSee('pagination-wrapper');
    }

    public function test_kategori_index_renders_consistent_design_and_pagination(): void
    {
        $response = $this->actingAs($this->admin)->get(route('kategori.index'));
        $response->assertStatus(200);
        $response->assertSee('col-freeze-aksi');
        $response->assertSee('btn-filter-custom');
        $response->assertSee('btn-action-edit');
        $response->assertSee('pagination-wrapper');
        $response->assertSee('perm-pagination');

        $responseCreate = $this->actingAs($this->admin)->get(route('kategori.create'));
        $responseCreate->assertStatus(200);
        $responseCreate->assertSee('btn-secondary-custom');
        $responseCreate->assertSee('btn-primary-custom');
    }

    public function test_satuan_index_renders_consistent_design_and_pagination(): void
    {
        $response = $this->actingAs($this->admin)->get(route('satuan.index'));
        $response->assertStatus(200);
        $response->assertSee('col-freeze-aksi');
        $response->assertSee('btn-filter-custom');
        $response->assertSee('btn-action-edit');
        $response->assertSee('pagination-wrapper');
        $response->assertSee('perm-pagination');

        $responseCreate = $this->actingAs($this->admin)->get(route('satuan.create'));
        $responseCreate->assertStatus(200);
        $responseCreate->assertSee('btn-secondary-custom');
        $responseCreate->assertSee('btn-primary-custom');
    }

    public function test_kas_masuk_and_pengeluaran_render_consistent_design(): void
    {
        $responseKasMasuk = $this->actingAs($this->bendahara)->get(route('kas-masuk.index'));
        $responseKasMasuk->assertStatus(200);
        $responseKasMasuk->assertSee('col-freeze-aksi');
        $responseKasMasuk->assertSee('btn-filter-custom');
        $responseKasMasuk->assertSee('modalFilterKasMasuk');

        $responseKasMasukCreate = $this->actingAs($this->bendahara)->get(route('kas-masuk.create'));
        $responseKasMasukCreate->assertStatus(200);
        $responseKasMasukCreate->assertSee('btn-secondary-custom');
        $responseKasMasukCreate->assertSee('btn-primary-custom');

        $responsePengeluaran = $this->actingAs($this->bendahara)->get(route('pengeluaran.index'));
        $responsePengeluaran->assertStatus(200);
        $responsePengeluaran->assertSee('col-freeze-aksi');
        $responsePengeluaran->assertSee('btn-filter-custom');
        $responsePengeluaran->assertSee('modalFilterPengeluaran');

        $responsePengeluaranCreate = $this->actingAs($this->bendahara)->get(route('pengeluaran.create'));
        $responsePengeluaranCreate->assertStatus(200);
        $responsePengeluaranCreate->assertSee('btn-secondary-custom');
        $responsePengeluaranCreate->assertSee('btn-primary-custom');
    }

    public function test_online_procurement_creation_with_shipping_fee_and_deferred_stock(): void
    {
        $bahan = Produk::where('jenis', 'bahan_baku')->first();
        $initialStock = $bahan->stok;

        $payload = [
            'tanggal' => now()->toDateString(),
            'platform' => 'shopee',
            'supplier' => 'PT Keramik Sublimasi Official',
            'no_invoice_online' => 'INV/20261104/SHP/998877',
            'kurir_kargo' => 'J&T Cargo',
            'no_resi_kargo' => 'JTC2611049988',
            'ongkir' => 50000,
            'total_diskon' => 10000,
            'status_pembelian' => 'dipesan',
            'catatan' => 'Restock kargo 100 pcs',
            'items' => [
                [
                    'produk_id' => $bahan->id,
                    'qty' => 100,
                    'harga_beli' => 8500,
                ],
            ],
        ];

        $response = $this->actingAs($this->bendahara)
            ->post(route('transaksi-pembelian.store'), $payload);

        $pembelian = Pembelian::where('no_invoice_online', 'INV/20261104/SHP/998877')->first();
        $this->assertNotNull($pembelian);
        $response->assertRedirect(route('transaksi-pembelian.show', $pembelian));
        $this->assertEquals('dipesan', $pembelian->status_pembelian);
        $this->assertEquals(850000, $pembelian->total_harga);
        $this->assertEquals(50000, $pembelian->ongkir);
        $this->assertEquals(10000, $pembelian->total_diskon);
        // grand_total = (850,000 + 50,000) - 10,000 = 890,000
        $this->assertEquals(890000, $pembelian->grand_total);

        // Stock should NOT increment while status is still 'dipesan'
        $this->assertEquals($initialStock, $bahan->fresh()->stok);

        // Confirmation of delivery in workshop
        $confirmResponse = $this->actingAs($this->bendahara)
            ->patch(route('transaksi-pembelian.konfirmasi-diterima', $pembelian));

        $confirmResponse->assertSessionHas('success');
        $this->assertEquals('diterima', $pembelian->fresh()->status_pembelian);
        $this->assertEquals($initialStock + 100, $bahan->fresh()->stok);

        // Subsequent confirmation should not duplicate stock increment
        $secondConfirm = $this->actingAs($this->bendahara)
            ->patch(route('transaksi-pembelian.konfirmasi-diterima', $pembelian));

        $secondConfirm->assertSessionHas('info');
        $this->assertEquals($initialStock + 100, $bahan->fresh()->stok);
    }

    public function test_procurement_detail_page_displays_online_cargo_information(): void
    {
        $bahan = Produk::where('jenis', 'bahan_baku')->first();

        $pembelian = Pembelian::create([
            'kode_pembelian' => 'PBL-TEST-001',
            'tanggal' => now()->toDateString(),
            'platform' => 'tokopedia',
            'supplier' => 'CV Akrilik Nusantara',
            'no_invoice_online' => 'INV/TKP/2026/001',
            'ongkir' => 45000,
            'status_pembelian' => 'dipesan',
            'kurir_kargo' => 'SiCepat Cargo (GOKIL)',
            'no_resi_kargo' => 'GOKIL12345678',
            'total_harga' => 200000,
            'total_diskon' => 0,
            'grand_total' => 245000,
            'catatan' => 'Bahan akrilik lembaran',
            'user_id' => $this->bendahara->id,
        ]);

        $pembelian->details()->create([
            'produk_id' => $bahan->id,
            'nama_produk' => $bahan->nama_produk,
            'harga_beli' => 50000,
            'qty' => 4,
            'subtotal' => 200000,
        ]);

        $response = $this->actingAs($this->bendahara)
            ->get(route('transaksi-pembelian.show', $pembelian));

        $response->assertStatus(200);
        $response->assertSee('Tokopedia');
        $response->assertSee('CV Akrilik Nusantara');
        $response->assertSee('INV/TKP/2026/001');
        $response->assertSee('SiCepat Cargo (GOKIL)');
        $response->assertSee('GOKIL12345678');
        $response->assertSee('45.000');
        $response->assertSee('Konfirmasi Diterima di Workshop');
    }

    public function test_admin_can_create_raw_material_without_inputting_stock_or_purchase_price(): void
    {
        $kategori = Kategori::first();
        $satuan = Satuan::first();

        $payload = [
            'kode_produk' => 'BB-NEW-01',
            'nama_produk' => 'Blank Botol Minum Plastik 600ml',
            'kategori_id' => $kategori->id,
            'satuan_id' => $satuan->id,
            'stok_minimal' => 25,
            'deskripsi' => 'Bahan botol plastik siap sablon',
        ];

        $response = $this->actingAs($this->admin)->post(route('bahan-baku.store'), $payload);

        $response->assertRedirect(route('bahan-baku.index'));

        $bahan = Produk::where('kode_produk', 'BB-NEW-01')->first();
        $this->assertNotNull($bahan);
        $this->assertEquals('bahan_baku', $bahan->jenis);
        $this->assertEquals(0, $bahan->stok); // Stok awal 0, bertambah via pembelian online
        $this->assertEquals(0, $bahan->harga_beli);
        $this->assertEquals(25, $bahan->stok_minimal);
    }

    public function test_admin_can_update_raw_material_without_affecting_existing_stock_or_purchase_price(): void
    {
        $bahan = Produk::where('jenis', 'bahan_baku')->first();
        $existingStock = $bahan->stok;
        $existingPrice = (float) $bahan->harga_beli;

        $payload = [
            'kode_produk' => $bahan->kode_produk,
            'nama_produk' => $bahan->nama_produk.' (Updated Specs)',
            'kategori_id' => $bahan->kategori_id,
            'satuan_id' => $bahan->satuan_id,
            'stok_minimal' => 35,
            'deskripsi' => 'Catatan revisi spesifikasi',
        ];

        $response = $this->actingAs($this->admin)->put(route('bahan-baku.update', $bahan), $payload);

        $response->assertRedirect(route('bahan-baku.index'));

        $bahan->refresh();
        $this->assertEquals($payload['nama_produk'], $bahan->nama_produk);
        $this->assertEquals(35, $bahan->stok_minimal);
        // Stok dan harga beli TIDAK berubah!
        $this->assertEquals($existingStock, $bahan->stok);
        $this->assertEquals($existingPrice, (float) $bahan->harga_beli);
    }

    public function test_transaksi_pembelian_index_renders_consistent_design_freeze_column_and_kpis(): void
    {
        $bahan = Produk::where('jenis', 'bahan_baku')->first();

        // Buat beberapa record pembelian agar pagination dan KPI terisi
        for ($i = 1; $i <= 12; $i++) {
            $status = ($i % 3 === 0) ? 'diterima' : (($i % 3 === 1) ? 'dipesan' : 'dikirim');
            $pembelian = Pembelian::create([
                'kode_pembelian' => "PBL-20261105-00{$i}",
                'tanggal' => now()->subDays($i)->toDateString(),
                'platform' => 'shopee',
                'supplier' => "Toko Supplier {$i}",
                'no_invoice_online' => "INV/SHP/202611/{$i}",
                'ongkir' => 20000,
                'status_pembelian' => $status,
                'kurir_kargo' => 'J&T Cargo',
                'no_resi_kargo' => "JTC{$i}9988",
                'total_harga' => 100000,
                'total_diskon' => 0,
                'grand_total' => 120000,
                'user_id' => $this->bendahara->id,
            ]);

            $pembelian->details()->create([
                'produk_id' => $bahan->id,
                'nama_produk' => $bahan->nama_produk,
                'harga_beli' => 10000,
                'qty' => 10,
                'subtotal' => 100000,
            ]);
        }

        $response = $this->actingAs($this->bendahara)->get(route('transaksi-pembelian.index'));
        $response->assertStatus(200);

        // Verifikasi elemen konsistensi desain
        $response->assertSee('col-freeze-aksi');
        $response->assertSee('btn-filter-custom');
        $response->assertSee('btn-primary-custom');
        $response->assertSee('btn-action-view');
        $response->assertSee('pagination-wrapper');
        $response->assertSee('perm-pagination');

        // Verifikasi KPI cards & modal filter
        $response->assertSee('Total Belanja Bahan');
        $response->assertSee('Dipesan (Supplier)');
        $response->assertSee('Dikirim (Kargo)');
        $response->assertSee('Diterima di Workshop');
        $response->assertSee('modalFilterPembelian');
        $response->assertSee('modalUpdateStatusPembelian');
    }

    public function test_procurement_status_update_workflow_between_dipesan_dikirim_and_diterima(): void
    {
        $bahan = Produk::where('jenis', 'bahan_baku')->first();
        $initialStock = $bahan->stok;

        $pembelian = Pembelian::create([
            'kode_pembelian' => 'PBL-WORKFLOW-01',
            'tanggal' => now()->toDateString(),
            'platform' => 'tokopedia',
            'supplier' => 'Toko Distributor Resmi',
            'no_invoice_online' => 'INV/TKP/WORKFLOW/01',
            'ongkir' => 30000,
            'status_pembelian' => 'dipesan',
            'total_harga' => 500000,
            'total_diskon' => 0,
            'grand_total' => 530000,
            'user_id' => $this->bendahara->id,
        ]);

        $pembelian->details()->create([
            'produk_id' => $bahan->id,
            'nama_produk' => $bahan->nama_produk,
            'harga_beli' => 25000,
            'qty' => 20,
            'subtotal' => 500000,
        ]);

        // 1. Status awal dipesan: stok gudang belum bertambah
        $this->assertEquals($initialStock, $bahan->fresh()->stok);

        // 2. Update status ke 'dikirim' via update-status endpoint
        $responseDikirim = $this->actingAs($this->bendahara)
            ->patch(route('transaksi-pembelian.update-status', $pembelian), [
                'status_pembelian' => 'dikirim',
                'kurir_kargo' => 'SiCepat Cargo (GOKIL)',
                'no_resi_kargo' => 'GOKIL-TEST-999',
            ]);

        $responseDikirim->assertSessionHas('success');
        $pembelian->refresh();
        $this->assertEquals('dikirim', $pembelian->status_pembelian);
        $this->assertEquals('SiCepat Cargo (GOKIL)', $pembelian->kurir_kargo);
        $this->assertEquals('GOKIL-TEST-999', $pembelian->no_resi_kargo);
        // Stok masih belum bertambah
        $this->assertEquals($initialStock, $bahan->fresh()->stok);

        // 3. Update status ke 'diterima'
        $responseDiterima = $this->actingAs($this->bendahara)
            ->patch(route('transaksi-pembelian.update-status', $pembelian), [
                'status_pembelian' => 'diterima',
            ]);

        $responseDiterima->assertSessionHas('success');
        $pembelian->refresh();
        $this->assertEquals('diterima', $pembelian->status_pembelian);
        // Stok gudang BERTAMBAH 20 unit!
        $this->assertEquals($initialStock + 20, $bahan->fresh()->stok);

        // 4. Update status kembali ke 'dikirim' (misal koreksi kesalahan input)
        $responseKoreksi = $this->actingAs($this->bendahara)
            ->patch(route('transaksi-pembelian.update-status', $pembelian), [
                'status_pembelian' => 'dikirim',
            ]);

        $responseKoreksi->assertSessionHas('success');
        $pembelian->refresh();
        $this->assertEquals('dikirim', $pembelian->status_pembelian);
        // Stok gudang OTOMATIS BERKURANG KEMBALI 20 unit dengan aman!
        $this->assertEquals($initialStock, $bahan->fresh()->stok);
    }

    public function test_laporan_keuangan_accurately_calculates_all_features_and_renders_all_tabs(): void
    {
        $souvenir = Produk::souvenirJadi()->first();
        $bahan = Produk::bahanBaku()->first();

        // 1. Buat pesanan marketplace valid (Shopee: Omzet 100.000, Fee Admin 10.000 -> Bersih 90.000)
        $this->actingAs($this->admin)->post(route('transaksi-penjualan.store'), [
            'channel' => 'shopee',
            'no_pesanan_marketplace' => 'SHP-VALID-001',
            'tanggal' => now()->toDateString(),
            'nama_pelanggan' => 'Pembeli Valid',
            'biaya_admin_marketplace' => 10000,
            'status_pesanan' => 'selesai',
            'items' => [
                [
                    'produk_id' => $souvenir->id,
                    'qty' => 2,
                    'harga_satuan' => 50000,
                ],
            ],
        ]);

        // 2. Buat pesanan marketplace BATAL (Harus dikecualikan dari omzet & laba)
        $this->actingAs($this->admin)->post(route('transaksi-penjualan.store'), [
            'channel' => 'tiktok',
            'no_pesanan_marketplace' => 'TTS-BATAL-002',
            'tanggal' => now()->toDateString(),
            'nama_pelanggan' => 'Pembeli Batal',
            'biaya_admin_marketplace' => 5000,
            'status_pesanan' => 'batal',
            'items' => [
                [
                    'produk_id' => $souvenir->id,
                    'qty' => 1,
                    'harga_satuan' => 50000,
                ],
            ],
        ]);

        // 3. Buat pembelian bahan baku (Qty 5 @ 5.000 + Ongkir 5.000 = 30.000) oleh Bendahara
        $this->actingAs($this->bendahara)->post(route('transaksi-pembelian.store'), [
            'tanggal' => now()->toDateString(),
            'supplier' => 'Supplier Test',
            'platform' => 'shopee',
            'status_pembelian' => 'diterima',
            'ongkir' => 5000,
            'items' => [
                [
                    'produk_id' => $bahan->id,
                    'qty' => 5,
                    'harga_beli' => 5000,
                ],
            ],
        ]);

        // 4. Buat Kas Masuk pendapatan_lain (20.000) & Pengeluaran Bisnis Iklan (15.000) oleh Bendahara
        $this->actingAs($this->bendahara)->post(route('kas-masuk.store'), [
            'tanggal' => now()->toDateString(),
            'tipe' => 'pendapatan_lain',
            'sumber' => 'Shopee (Pencairan Saldo Penjualan)',
            'jumlah' => 20000,
            'keterangan' => 'Cashback & Bonus Ekspedisi',
        ]);

        $this->actingAs($this->bendahara)->post(route('pengeluaran.store'), [
            'tanggal' => now()->toDateString(),
            'tipe_alokasi' => 'bisnis',
            'kategori' => 'iklan',
            'keterangan' => 'Topup Shopee Ads',
            'jumlah' => 15000,
        ]);

        // Verifikasi Tab Laba Rugi oleh Bendahara/Pemilik:
        // Penghasilan Bersih MP = 90.000, Pendapatan Lain = 20.000, Pembelian = 30.000 -> Laba Kotor = 80.000
        // Biaya Bisnis = 15.000 -> Laba Bersih = 65.000
        $resLabaRugi = $this->actingAs($this->bendahara)->get(route('laporan.keuangan', [
            'tab' => 'laba-rugi',
            'bulan' => now()->month,
            'tahun' => now()->year,
        ]));
        $resLabaRugi->assertStatus(200);
        $resLabaRugi->assertViewHas('total', function (array $total) {
            return (int) $total['penjualan'] === 100000
                && (int) $total['biaya_admin'] === 10000
                && (int) $total['penghasilan_bersih'] === 90000
                && (int) $total['pendapatan_lain'] === 20000
                && (int) $total['pembelian'] === 30000
                && (int) $total['laba_kotor'] === 80000
                && (int) $total['pengeluaran'] === 15000
                && (int) $total['laba_bersih'] === 65000
                && (int) $total['count_batal_retur'] === 1;
        });

        // Verifikasi Tab Pembelian menghitung qty item (5 unit) dan ongkir (5.000) dengan benar
        $resPembelian = $this->actingAs($this->bendahara)->get(route('laporan.keuangan', [
            'tab' => 'pembelian',
            'bulan' => now()->month,
            'tahun' => now()->year,
        ]));
        $resPembelian->assertStatus(200);
        $resPembelian->assertViewHas('total', function (array $total) {
            return (int) $total['item'] === 5
                && (int) $total['ongkir'] === 5000
                && (int) $total['pembelian'] === 30000;
        });

        // Verifikasi semua tab lain dapat diakses oleh Bendahara (HTTP 200)
        foreach (['penjualan', 'pengeluaran', 'produksi', 'pribadi', 'rekonsiliasi', 'kasbon'] as $tab) {
            $this->actingAs($this->bendahara)
                ->get(route('laporan.keuangan', ['tab' => $tab, 'bulan' => 'all', 'tahun' => now()->year]))
                ->assertStatus(200);
        }

        // Verifikasi hak akses Admin: hanya boleh membuka laporan operasional (penjualan & produksi), ditolak (403) pada laporan keuangan sensitif
        foreach (['penjualan', 'produksi'] as $allowedTab) {
            $this->actingAs($this->admin)
                ->get(route('laporan.keuangan', ['tab' => $allowedTab, 'bulan' => 'all', 'tahun' => now()->year]))
                ->assertStatus(200);
        }
        foreach (['laba-rugi', 'pribadi', 'rekonsiliasi', 'kasbon'] as $restrictedTab) {
            $this->actingAs($this->admin)
                ->get(route('laporan.keuangan', ['tab' => $restrictedTab, 'bulan' => 'all', 'tahun' => now()->year]))
                ->assertStatus(403);
        }
    }

    public function test_user_role_and_permission_menus_render_consistent_design_and_menu_aligned_permissions(): void
    {
        $pemilik = User::where('email', 'pemilik@gmail.com')->first();

        // 1. Manajemen User
        $resUsers = $this->actingAs($pemilik)->get(route('users.index'));
        $resUsers->assertStatus(200);
        $resUsers->assertSee('col-freeze-aksi');
        $resUsers->assertSee('btn-filter-custom');
        $resUsers->assertSee('modalFilterUser');

        // 2. Manajemen Role & Edit Role
        $resRoles = $this->actingAs($pemilik)->get(route('roles.index'));
        $resRoles->assertStatus(200);
        $resRoles->assertSee('col-freeze-aksi');
        $resRoles->assertSee('Manajemen Role');
        $resRoles->assertSee('Pemilik');
        $resRoles->assertSee('Bendahara');
        $resRoles->assertSee('Admin');

        // 3. Manajemen Permission (57 permission, tidak ada lagi spk-* / packing-slip-*)
        $resPerms = $this->actingAs($pemilik)->get(route('permissions.index'));
        $resPerms->assertStatus(200);
        $resPerms->assertSee('col-freeze-aksi');
        $resPerms->assertSee('Keterangan Hak Akses');
        $resPerms->assertDontSee('spk-list');
        $resPerms->assertDontSee('packing-slip-view');

        // 4. Dashboard Eksekutif & Teknologi Forecasting
        $resDashboard = $this->actingAs($pemilik)->get(route('dashboard', ['horizon' => 3]));
        $resDashboard->assertStatus(200);
        $resDashboard->assertSee('Dashboard Monitoring & Predictive Forecasting');
        $resDashboard->assertSee('chartTrenForecast');
        $resDashboard->assertSee('chartMarketplace');
        $resDashboard->assertSee('chartProduksiBahan');
        $resDashboard->assertSee('chartArusKasModal');
        $resDashboard->assertViewHas('forecastTable', function (array $table) {
            return count($table) === 3 && isset($table[0]['omzet'], $table[0]['laba']);
        });
    }
}
