<?php

namespace Tests\Feature;

use App\Models\PenerimaanProduk;
use App\Models\Produk;
use App\Models\User;
use Database\Seeders\KategoriSeeder;
use Database\Seeders\ProdukSeeder;
use Database\Seeders\RolePermissionSeeder;
use Database\Seeders\SatuanSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PenerimaanProdukTest extends TestCase
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

    public function test_penerimaan_produk_index_renders_consistent_design_kpis_and_pagination(): void
    {
        $souvenir = Produk::where('jenis', 'souvenir_jadi')->first();

        for ($i = 1; $i <= 12; $i++) {
            $penerimaan = PenerimaanProduk::create([
                'kode_penerimaan' => "HP-20261105-00{$i}",
                'tanggal' => now()->subDays($i)->toDateString(),
                'tipe_produksi' => 'ready_stock',
                'nama_penanggung_jawab' => "QC Lead {$i}",
                'total_item' => 1,
                'total_bagus' => 30,
                'total_reject' => 2,
                'user_id' => $this->admin->id,
            ]);

            $penerimaan->details()->create([
                'produk_id' => $souvenir->id,
                'nama_produk' => $souvenir->nama_produk,
                'qty_bagus' => 30,
                'qty_reject' => 2,
                'satuan' => 'pcs',
                'keterangan' => 'Batch lolos QC',
            ]);
        }

        $response = $this->actingAs($this->admin)->get(route('penerimaan-produk.index'));
        $response->assertStatus(200);

        // Verifikasi elemen konsistensi desain
        $response->assertSee('col-freeze-aksi');
        $response->assertSee('btn-filter-custom');
        $response->assertSee('btn-primary-custom');
        $response->assertSee('btn-action-view');
        $response->assertSee('pagination-wrapper');
        $response->assertSee('perm-pagination');

        // Verifikasi KPI cards & modal filter
        $response->assertSee('Total Produk Jadi Masuk');
        $response->assertSee('Total Gagal / Reject');
        $response->assertSee('Total Batch Selesai');
        $response->assertSee('Produksi Bulan Ini');
        $response->assertSee('modalFilterPenerimaan');
    }

    public function test_admin_can_record_finished_goods_receipt_and_stock_is_incremented(): void
    {
        $souvenir = Produk::where('jenis', 'souvenir_jadi')->first();
        $initialStock = $souvenir->stok;

        $payload = [
            'tanggal' => now()->toDateString(),
            'tipe_produksi' => 'ready_stock',
            'nama_petugas' => 'Ahmad Fauzi (QC)',
            'no_referensi_pemakaian' => 'PBB-20261105-0001',
            'catatan' => 'Hasil cetak sublim batch 1 selesai packaging',
            'items' => [
                [
                    'produk_id' => $souvenir->id,
                    'qty_bagus' => 50,
                    'qty_reject' => 3,
                    'keterangan' => '3 mug retak saat heating press',
                ],
            ],
        ];

        $response = $this->actingAs($this->admin)
            ->post(route('penerimaan-produk.store'), $payload);

        $penerimaan = PenerimaanProduk::where('nama_petugas', 'Ahmad Fauzi (QC)')->first();
        $this->assertNotNull($penerimaan);
        $response->assertRedirect(route('penerimaan-produk.show', $penerimaan));
        $this->assertEquals(1, $penerimaan->total_item);
        $this->assertEquals(50, $penerimaan->total_bagus);
        $this->assertEquals(3, $penerimaan->total_reject);

        // Stok produk jadi bertambah 50 unit (hanya yang lolos QC/bagus)
        $this->assertEquals($initialStock + 50, $souvenir->fresh()->stok);
    }

    public function test_deleting_production_receipt_cancels_record_and_decrements_stock(): void
    {
        $souvenir = Produk::where('jenis', 'souvenir_jadi')->first();
        $initialStock = $souvenir->stok;

        $penerimaan = PenerimaanProduk::create([
            'kode_penerimaan' => 'HP-CANCEL-001',
            'tanggal' => now()->toDateString(),
            'tipe_produksi' => 'ready_stock',
            'nama_penanggung_jawab' => 'Hendri Gunawan',
            'total_item' => 1,
            'total_bagus' => 20,
            'total_reject' => 0,
            'user_id' => $this->admin->id,
        ]);

        $penerimaan->details()->create([
            'produk_id' => $souvenir->id,
            'nama_produk' => $souvenir->nama_produk,
            'qty_bagus' => 20,
            'qty_reject' => 0,
            'satuan' => 'pcs',
        ]);

        // Simulasikan stok bertambah saat input (+20)
        $souvenir->update(['stok' => $initialStock + 20]);

        // Batalkan / hapus penerimaan
        $response = $this->actingAs($this->admin)
            ->delete(route('penerimaan-produk.destroy', $penerimaan));

        $response->assertRedirect(route('penerimaan-produk.index'));
        $this->assertNull(PenerimaanProduk::find($penerimaan->id));

        // Stok berkurang kembali ke stok semula
        $this->assertEquals($initialStock, $souvenir->fresh()->stok);
    }

    public function test_show_page_and_printable_slip_render_correctly(): void
    {
        $souvenir = Produk::where('jenis', 'souvenir_jadi')->first();

        $penerimaan = PenerimaanProduk::create([
            'kode_penerimaan' => 'HP-PRINT-001',
            'tanggal' => now()->toDateString(),
            'tipe_produksi' => 'pesanan_custom',
            'nama_penanggung_jawab' => 'Dewi Lestari',
            'total_item' => 1,
            'total_bagus' => 45,
            'total_reject' => 1,
            'user_id' => $this->admin->id,
        ]);

        $penerimaan->details()->create([
            'produk_id' => $souvenir->id,
            'nama_produk' => $souvenir->nama_produk,
            'qty_bagus' => 45,
            'qty_reject' => 1,
            'satuan' => 'pcs',
            'keterangan' => 'Pesanan custom instansi',
        ]);

        // Halaman Detail
        $responseShow = $this->actingAs($this->admin)
            ->get(route('penerimaan-produk.show', $penerimaan));
        $responseShow->assertStatus(200);
        $responseShow->assertSee('HP-PRINT-001');
        $responseShow->assertSee('Dewi Lestari');
        $responseShow->assertSee('Cetak Bon Penerimaan');

        // Halaman Cetak Slip
        $responseCetak = $this->actingAs($this->admin)
            ->get(route('penerimaan-produk.cetak', $penerimaan));
        $responseCetak->assertStatus(200);
        $responseCetak->assertSee('BON PENERIMAAN HASIL PRODUKSI');
        $responseCetak->assertSee('Diserahkan Oleh');
        $responseCetak->assertSee('Diterima &amp; Disimpan Oleh', false);
    }

    public function test_produk_show_displays_production_receipt_history(): void
    {
        $souvenir = Produk::where('jenis', 'souvenir_jadi')->first();

        $penerimaan = PenerimaanProduk::create([
            'kode_penerimaan' => 'HP-PROD-SHOW-01',
            'tanggal' => now()->toDateString(),
            'tipe_produksi' => 'ready_stock',
            'nama_penanggung_jawab' => 'QC Tester',
            'total_item' => 1,
            'total_bagus' => 75,
            'total_reject' => 2,
            'user_id' => $this->admin->id,
        ]);

        $penerimaan->details()->create([
            'produk_id' => $souvenir->id,
            'nama_produk' => $souvenir->nama_produk,
            'qty_bagus' => 75,
            'qty_reject' => 2,
            'satuan' => 'pcs',
            'keterangan' => 'Ready stock masuk',
        ]);

        $response = $this->actingAs($this->admin)
            ->get(route('produk.show', $souvenir));

        $response->assertStatus(200);
        $response->assertSee('Riwayat Hasil Produksi Masuk');
        $response->assertSee('HP-PROD-SHOW-01');
        $response->assertSee('75');
    }
}
