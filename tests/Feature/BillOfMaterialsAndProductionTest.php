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

class BillOfMaterialsAndProductionTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;

    private User $bendahara;

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
        $this->bendahara = User::where('email', 'bendahara@gmail.com')->first();
    }

    public function test_bom_recipe_and_production_capacity_calculation(): void
    {
        $mug = Produk::where('nama_produk', 'Mug Keramik Custom Anime Jibranime (SNI)')->first();
        $blankoMug = Produk::where('nama_produk', 'Mug Polos Keramik Sublimasi SNI Super White')->first();
        $box = Produk::where('nama_produk', 'Kardus Die Cut Box Mug Souvenir + Jendela Mika')->first();

        $this->assertNotNull($mug);
        $this->assertNotNull($blankoMug);
        $this->assertNotNull($box);

        // Verify the seeded recipe
        $this->assertTrue($mug->resepBahanBaku->contains($blankoMug));
        $this->assertTrue($mug->resepBahanBaku->contains($box));

        // Capacity is min(floor(blanko / 1), floor(box / 1))
        $expectedCapacity = min($blankoMug->stok, $box->stok);
        $this->assertEquals($expectedCapacity, $mug->hitungKapasitasProduksi());
    }

    public function test_admin_can_produce_finished_stock_manually_from_raw_materials(): void
    {
        $mug = Produk::where('nama_produk', 'Mug Keramik Custom Anime Jibranime (SNI)')->first();
        $blankoMug = Produk::where('nama_produk', 'Mug Polos Keramik Sublimasi SNI Super White')->first();
        $box = Produk::where('nama_produk', 'Kardus Die Cut Box Mug Souvenir + Jendela Mika')->first();

        $initialMugStock = $mug->stok;
        $initialBlankoStock = $blankoMug->stok;
        $initialBoxStock = $box->stok;

        $qtyToProduce = 15;

        $response = $this->actingAs($this->admin)->post(route('produk.produksi-stok', $mug), [
            'qty_produksi' => $qtyToProduce,
        ]);

        $response->assertSessionHas('success');

        // Finished product stock should INCREASE
        $this->assertEquals($initialMugStock + $qtyToProduce, $mug->fresh()->stok);

        // Raw materials stock should DECREASE
        $this->assertEquals($initialBlankoStock - $qtyToProduce, $blankoMug->fresh()->stok);
        $this->assertEquals($initialBoxStock - $qtyToProduce, $box->fresh()->stok);
    }

    public function test_automatic_raw_material_deduction_when_order_enters_production_phase(): void
    {
        $mug = Produk::where('nama_produk', 'Mug Keramik Custom Anime Jibranime (SNI)')->first();
        $blankoMug = Produk::where('nama_produk', 'Mug Polos Keramik Sublimasi SNI Super White')->first();
        $box = Produk::where('nama_produk', 'Kardus Die Cut Box Mug Souvenir + Jendela Mika')->first();

        $initialBlanko = $blankoMug->stok;
        $initialBox = $box->stok;

        // 1. Create order with status 'batal' (belum dipotong bahan)
        $transaksi = Transaksi::create([
            'channel' => 'shopee',
            'kode_transaksi' => 'TRX-BOM-001',
            'no_pesanan_marketplace' => '261104SHP9999',
            'tanggal' => now()->toDateString(),
            'nama_pelanggan' => 'Andi Wijaya',
            'total_harga' => 56000,
            'total_diskon' => 0,
            'grand_total' => 56000,
            'status_pesanan' => 'batal',
            'bahan_baku_dipotong' => false,
            'user_id' => $this->admin->id,
        ]);

        $orderQty = 2;

        $transaksi->details()->create([
            'produk_id' => $mug->id,
            'nama_produk' => $mug->nama_produk,
            'harga_satuan' => 28000,
            'qty' => $orderQty,
            'subtotal' => 56000,
        ]);

        // Materials must NOT be deducted yet while in 'batal'
        $this->assertFalse($transaksi->bahan_baku_dipotong);
        $this->assertEquals($initialBlanko, $blankoMug->fresh()->stok);
        $this->assertEquals($initialBox, $box->fresh()->stok);

        // 2. Update status to 'siap_packing'
        $response = $this->actingAs($this->admin)->patch(route('transaksi-penjualan.update-status', $transaksi), [
            'status_pesanan' => 'siap_packing',
        ]);

        $response->assertSessionHas('success');
        $transaksi->refresh();

        // Materials MUST now be deducted automatically!
        $this->assertTrue($transaksi->bahan_baku_dipotong);
        $this->assertNotNull($transaksi->waktu_potong_bahan);
        $this->assertEquals($initialBlanko - $orderQty, $blankoMug->fresh()->stok);
        $this->assertEquals($initialBox - $orderQty, $box->fresh()->stok);

        // 3. Repeated updates to 'dikirim' must NOT duplicate deduction
        $this->actingAs($this->admin)->patch(route('transaksi-penjualan.update-status', $transaksi), [
            'status_pesanan' => 'dikirim',
        ]);

        $this->assertEquals($initialBlanko - $orderQty, $blankoMug->fresh()->stok);
        $this->assertEquals($initialBox - $orderQty, $box->fresh()->stok);

        // 4. If order is cancelled, materials are restored back
        $this->actingAs($this->admin)->patch(route('transaksi-penjualan.update-status', $transaksi), [
            'status_pesanan' => 'batal',
        ]);

        $transaksi->refresh();
        $this->assertFalse($transaksi->bahan_baku_dipotong);
        $this->assertEquals($initialBlanko, $blankoMug->fresh()->stok);
        $this->assertEquals($initialBox, $box->fresh()->stok);
    }

    public function test_order_packing_slip_renders_correctly(): void
    {
        $mug = Produk::where('nama_produk', 'Mug Keramik Custom Anime Jibranime (SNI)')->first();

        $transaksi = Transaksi::create([
            'channel' => 'tiktok',
            'kode_transaksi' => 'SLIP-TEST-001',
            'no_pesanan_marketplace' => 'TT882233',
            'tanggal' => now()->toDateString(),
            'nama_pelanggan' => 'Citra Lestari',
            'total_harga' => 28000,
            'total_diskon' => 0,
            'grand_total' => 28000,
            'status_pesanan' => 'siap_packing',
            'user_id' => $this->admin->id,
        ]);

        $transaksi->details()->create([
            'produk_id' => $mug->id,
            'nama_produk' => $mug->nama_produk,
            'harga_satuan' => 28000,
            'qty' => 3,
            'subtotal' => 84000,
        ]);

        $response = $this->actingAs($this->admin)->get(route('transaksi-penjualan.cetak-struk', $transaksi));

        $response->assertStatus(200);
        $response->assertSee('PACKING SLIP');
        $response->assertSee('TT882233');
        $response->assertSee('Citra Lestari');
    }
}
