<?php

namespace Tests\Feature;

use App\Models\Produk;
use App\Models\Transaksi;
use App\Models\User;
use Database\Seeders\KategoriSeeder;
use Database\Seeders\ProdukSeeder;
use Database\Seeders\RolePermissionSeeder;
use Database\Seeders\SatuanSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TransaksiPenjualanRedesignTest extends TestCase
{
    use RefreshDatabase;

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

        $this->admin = User::where('email', 'admin@gmail.com')->first();
    }

    public function test_transaksi_penjualan_create_renders(): void
    {
        $response = $this->actingAs($this->admin)->get(route('transaksi-penjualan.create'));
        $response->assertStatus(200);
        $response->assertSee('id="quickSelectProduk"', false);
        $response->assertDontSee('class="form-select form-select-modern no-select2"', false);
        $response->assertSee('initQuickSelect2', false);
    }

    public function test_transaksi_penjualan_index_renders_consistent_design_and_kpis(): void
    {
        $souvenir = Produk::where('jenis', 'souvenir_jadi')->first();

        // Create sample orders (> 15 to test pagination)
        for ($i = 1; $i <= 18; $i++) {
            $trx = Transaksi::create([
                'channel' => 'shopee',
                'kode_transaksi' => "TRX-TEST-00{$i}",
                'no_pesanan_marketplace' => "SHP-998877{$i}",
                'no_resi' => "SPXID123456{$i}",
                'kurir' => 'SPX Standard',
                'tanggal' => now()->toDateString(),
                'nama_pelanggan' => "Pembeli Shopee {$i}",
                'total_harga' => 50000,
                'total_diskon' => 0,
                'grand_total' => 50000,
                'biaya_admin_marketplace' => 5000,
                'penghasilan_bersih' => 45000,
                'status_pesanan' => 'siap_packing',
                'user_id' => $this->admin->id,
            ]);

            $trx->details()->create([
                'produk_id' => $souvenir->id,
                'nama_produk' => $souvenir->nama_produk,
                'harga_satuan' => 25000,
                'qty' => 2,
                'subtotal' => 50000,
            ]);
        }

        $response = $this->actingAs($this->admin)->get(route('transaksi-penjualan.index'));
        $response->assertStatus(200);

        // Design consistency verification
        $response->assertSee('col-freeze-aksi');
        $response->assertSee('btn-primary-custom');
        $response->assertSee('btn-secondary-custom');
        $response->assertSee('btn-filter-custom');
        $response->assertSee('btn-action-view');
        $response->assertSee('btn-action-status');
        $response->assertSee('btn-action-print');

        // Pagination verification
        $response->assertSee('pagination-wrapper');
        $response->assertSee('perm-pagination');

        // Modal triggers
        $response->assertSee('modalUpdateStatus');
        $response->assertSee('modalFilterPesanan');
        $response->assertSee('modalImportMarketplace');

        // Verify obsolete statuses are NOT shown as filter options
        $response->assertDontSee('value="menunggu_produksi"', false);
        $response->assertDontSee('value="dalam_produksi"', false);

        // Verify SPK button is NOT present
        $response->assertDontSee('cetak-spk');
    }

    public function test_can_update_status_via_patch_and_supports_ajax(): void
    {
        $trx = Transaksi::create([
            'channel' => 'tiktok',
            'kode_transaksi' => 'TRX-STATUS-01',
            'no_pesanan_marketplace' => 'TT-778899',
            'tanggal' => now()->toDateString(),
            'nama_pelanggan' => 'Pembeli TikTok',
            'total_harga' => 30000,
            'grand_total' => 30000,
            'status_pesanan' => 'siap_packing',
            'user_id' => $this->admin->id,
        ]);

        // Standard PATCH form submit
        $response = $this->actingAs($this->admin)->patch(route('transaksi-penjualan.update-status', $trx), [
            'status_pesanan' => 'dikirim',
            'kurir' => 'J&T Express',
            'no_resi' => 'JT1234567890',
        ]);

        $response->assertRedirect();
        $this->assertEquals('dikirim', $trx->fresh()->status_pesanan);
        $this->assertEquals('JT1234567890', $trx->fresh()->no_resi);
        $this->assertEquals('J&T Express', $trx->fresh()->kurir);

        // AJAX JSON PATCH submit
        $responseAjax = $this->actingAs($this->admin)->patchJson(route('transaksi-penjualan.update-status', $trx), [
            'status_pesanan' => 'selesai',
        ]);

        $responseAjax->assertOk();
        $responseAjax->assertJson([
            'success' => true,
            'status' => 'selesai',
            'status_label' => 'Selesai',
        ]);
        $this->assertEquals('selesai', $trx->fresh()->status_pesanan);
    }

    public function test_show_page_renders_redesigned_layout_without_spk(): void
    {
        $souvenir = Produk::where('jenis', 'souvenir_jadi')->first();

        $trx = Transaksi::create([
            'channel' => 'lazada',
            'kode_transaksi' => 'TRX-SHOW-01',
            'no_pesanan_marketplace' => 'LZD-554433',
            'tanggal' => now()->toDateString(),
            'nama_pelanggan' => 'Pembeli Lazada',
            'total_harga' => 40000,
            'grand_total' => 40000,
            'biaya_admin_marketplace' => 4000,
            'penghasilan_bersih' => 36000,
            'status_pesanan' => 'perlu_dikirim',
            'user_id' => $this->admin->id,
        ]);

        $trx->details()->create([
            'produk_id' => $souvenir->id,
            'nama_produk' => $souvenir->nama_produk,
            'harga_satuan' => 40000,
            'qty' => 1,
            'subtotal' => 40000,
        ]);

        $response = $this->actingAs($this->admin)->get(route('transaksi-penjualan.show', $trx));

        $response->assertOk();
        $response->assertSee('TRX-SHOW-01');
        $response->assertSee('Detail Pesanan Marketplace');
        $response->assertSee('Cetak Packing Slip');
        $response->assertSee('Update Status Pesanan &amp; Resi', false);

        // SPK button must be absent
        $response->assertDontSee('Cetak SPK Produksi');
        $response->assertDontSee('cetak-spk');
    }

    public function test_admin_can_store_marketplace_order_and_decrements_stock(): void
    {
        $souvenir = Produk::where('jenis', 'souvenir_jadi')->first();
        $initialStock = $souvenir->stok;

        $payload = [
            'channel' => 'shopee',
            'no_pesanan_marketplace' => '24110599AABB',
            'no_resi' => 'SPXID99887766',
            'kurir' => 'SPX Standard',
            'tanggal' => now()->toDateString(),
            'nama_pelanggan' => 'Siti Shopee Lover',
            'biaya_admin_marketplace' => 2500,
            'status_pesanan' => 'siap_packing',
            'catatan' => 'Packing bubble tebal ya kak',
            'items' => [
                [
                    'produk_id' => $souvenir->id,
                    'qty' => 3,
                    'harga_satuan' => 25000,
                ],
            ],
        ];

        $response = $this->actingAs($this->admin)->post(route('transaksi-penjualan.store'), $payload);

        $response->assertRedirect();
        $this->assertDatabaseHas('transaksis', [
            'channel' => 'shopee',
            'no_pesanan_marketplace' => '24110599AABB',
            'no_resi' => 'SPXID99887766',
            'status_pesanan' => 'siap_packing',
            'grand_total' => 75000,
            'biaya_admin_marketplace' => 2500,
            'penghasilan_bersih' => 72500,
        ]);

        $this->assertEquals($initialStock - 3, $souvenir->fresh()->stok);
    }

    public function test_admin_cannot_store_order_exceeding_stock(): void
    {
        $souvenir = Produk::where('jenis', 'souvenir_jadi')->first();

        $payload = [
            'channel' => 'tiktok',
            'tanggal' => now()->toDateString(),
            'items' => [
                [
                    'produk_id' => $souvenir->id,
                    'qty' => $souvenir->stok + 999,
                    'harga_satuan' => 20000,
                ],
            ],
        ];

        $response = $this->actingAs($this->admin)->post(route('transaksi-penjualan.store'), $payload);
        $response->assertSessionHas('error');
    }
}
