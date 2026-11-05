<?php

namespace Tests\Feature;

use App\Models\PemakaianBahan;
use App\Models\Produk;
use App\Models\User;
use Database\Seeders\KategoriSeeder;
use Database\Seeders\ProdukSeeder;
use Database\Seeders\RolePermissionSeeder;
use Database\Seeders\SatuanSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PemakaianBahanTest extends TestCase
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

    public function test_pemakaian_bahan_index_renders_consistent_design_kpis_and_pagination(): void
    {
        $bahan = Produk::where('jenis', 'bahan_baku')->first();

        for ($i = 1; $i <= 12; $i++) {
            $pemakaian = PemakaianBahan::create([
                'kode_pemakaian' => "PBB-20261105-00{$i}",
                'tanggal' => now()->subDays($i)->toDateString(),
                'nama_operator' => "Operator {$i}",
                'keperluan' => "Produksi Pesanan {$i}",
                'total_item' => 1,
                'total_kuantitas' => 25,
                'user_id' => $this->admin->id,
            ]);

            $pemakaian->details()->create([
                'produk_id' => $bahan->id,
                'nama_bahan' => $bahan->nama_produk,
                'qty' => 25,
                'satuan' => 'pcs',
                'keterangan' => 'Pengambilan bahan shift pagi',
            ]);
        }

        $response = $this->actingAs($this->admin)->get(route('pemakaian-bahan.index'));
        $response->assertStatus(200);

        // Verifikasi elemen konsistensi desain
        $response->assertSee('col-freeze-aksi');
        $response->assertSee('btn-filter-custom');
        $response->assertSee('btn-primary-custom');
        $response->assertSee('btn-action-view');
        $response->assertSee('pagination-wrapper');
        $response->assertSee('perm-pagination');

        // Verifikasi KPI cards & modal filter
        $response->assertSee('Total Material Keluar');
        $response->assertSee('Total Transaksi');
        $response->assertSee('Bulan Ini');
        $response->assertSee('Hari Ini');
        $response->assertSee('modalFilterPemakaian');
    }

    public function test_admin_can_record_material_withdrawal_and_stock_is_decremented(): void
    {
        $bahan = Produk::where('jenis', 'bahan_baku')->first();
        $bahan->update(['stok' => 100]);
        $initialStock = $bahan->stok;

        $payload = [
            'tanggal' => now()->toDateString(),
            'nama_operator' => 'Budi Santoso (Operator Press)',
            'keperluan' => 'Produksi 40 Mug Custom Anime Batch 1',
            'catatan' => 'Termasuk 2 mug sampel pengujian suhu',
            'items' => [
                [
                    'produk_id' => $bahan->id,
                    'qty' => 42,
                    'keterangan' => '40 order + 2 tester',
                ],
            ],
        ];

        $response = $this->actingAs($this->admin)
            ->post(route('pemakaian-bahan.store'), $payload);

        $pemakaian = PemakaianBahan::where('nama_operator', 'Budi Santoso (Operator Press)')->first();
        $this->assertNotNull($pemakaian);
        $response->assertRedirect(route('pemakaian-bahan.show', $pemakaian));
        $this->assertEquals(1, $pemakaian->total_item);
        $this->assertEquals(42, $pemakaian->total_kuantitas);

        // Stok fisik gudang berkurang 42 unit!
        $this->assertEquals($initialStock - 42, $bahan->fresh()->stok);
    }

    public function test_cannot_record_withdrawal_if_quantity_exceeds_warehouse_stock(): void
    {
        $bahan = Produk::where('jenis', 'bahan_baku')->first();
        $bahan->update(['stok' => 15]);

        $payload = [
            'tanggal' => now()->toDateString(),
            'nama_operator' => 'Joko Susilo',
            'keperluan' => 'Order massal',
            'items' => [
                [
                    'produk_id' => $bahan->id,
                    'qty' => 50, // Melebihi stok yang hanya 15
                ],
            ],
        ];

        $response = $this->actingAs($this->admin)
            ->from(route('pemakaian-bahan.create'))
            ->post(route('pemakaian-bahan.store'), $payload);

        $response->assertRedirect(route('pemakaian-bahan.create'));
        $response->assertSessionHas('error');

        // Stok gudang tidak boleh berubah
        $this->assertEquals(15, $bahan->fresh()->stok);
    }

    public function test_deleting_withdrawal_cancels_record_and_restores_stock(): void
    {
        $bahan = Produk::where('jenis', 'bahan_baku')->first();
        $bahan->update(['stok' => 80]);

        $pemakaian = PemakaianBahan::create([
            'kode_pemakaian' => 'PBB-CANCEL-001',
            'tanggal' => now()->toDateString(),
            'nama_operator' => 'Andi Wijaya',
            'keperluan' => 'Salah ambil material',
            'total_item' => 1,
            'total_kuantitas' => 30,
            'user_id' => $this->admin->id,
        ]);

        $pemakaian->details()->create([
            'produk_id' => $bahan->id,
            'nama_bahan' => $bahan->nama_produk,
            'qty' => 30,
            'satuan' => 'pcs',
        ]);

        // Simulasikan stok terpotong sebelumnya (80 - 30 = 50)
        $bahan->update(['stok' => 50]);

        // Hapus / batalkan transaksi pengambilan
        $response = $this->actingAs($this->admin)
            ->delete(route('pemakaian-bahan.destroy', $pemakaian));

        $response->assertRedirect(route('pemakaian-bahan.index'));
        $this->assertNull(PemakaianBahan::find($pemakaian->id));

        // Stok otomatis dikembalikan ke gudang (50 + 30 = 80)
        $this->assertEquals(80, $bahan->fresh()->stok);
    }

    public function test_show_page_and_printable_slip_render_correctly(): void
    {
        $bahan = Produk::where('jenis', 'bahan_baku')->first();

        $pemakaian = PemakaianBahan::create([
            'kode_pemakaian' => 'PBB-PRINT-001',
            'tanggal' => now()->toDateString(),
            'nama_operator' => 'Siti Rahmawati',
            'keperluan' => 'Packing Souvenir Tokopedia',
            'total_item' => 1,
            'total_kuantitas' => 50,
            'catatan' => 'Bahan diambil dari rak B2',
            'user_id' => $this->admin->id,
        ]);

        $pemakaian->details()->create([
            'produk_id' => $bahan->id,
            'nama_bahan' => $bahan->nama_produk,
            'qty' => 50,
            'satuan' => 'pcs',
            'keterangan' => 'Box kemasan',
        ]);

        // Halaman Detail
        $responseShow = $this->actingAs($this->admin)
            ->get(route('pemakaian-bahan.show', $pemakaian));
        $responseShow->assertStatus(200);
        $responseShow->assertSee('PBB-PRINT-001');
        $responseShow->assertSee('Siti Rahmawati');
        $responseShow->assertSee('Cetak Bon Pengambilan');

        // Halaman Cetak Slip
        $responseCetak = $this->actingAs($this->admin)
            ->get(route('pemakaian-bahan.cetak', $pemakaian));
        $responseCetak->assertStatus(200);
        $responseCetak->assertSee('BON PENGAMBILAN BAHAN BAKU WORKSHOP');
        $responseCetak->assertSee('Diserahkan Oleh');
        $responseCetak->assertSee('Diterima Oleh');
    }
}
