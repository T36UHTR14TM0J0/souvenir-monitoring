<?php

namespace Tests\Feature;

use App\Models\Kategori;
use App\Models\Produk;
use App\Models\Satuan;
use App\Models\Transaksi;
use App\Models\User;
use App\Services\ShopeeImportService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Spatie\Permission\Models\Permission;
use Tests\TestCase;

class ShopeeMonitoringTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        Permission::firstOrCreate(['name' => 'penjualan-list']);
        Permission::firstOrCreate(['name' => 'penjualan-create']);
        Permission::firstOrCreate(['name' => 'penjualan-delete']);
    }

    public function test_user_can_create_multi_marketplace_orders(): void
    {
        $user = User::factory()->create(['is_active' => true]);
        $user->givePermissionTo(['penjualan-list', 'penjualan-create']);

        $kategori = Kategori::create(['nama_kategori' => 'Souvenir']);
        $satuan = Satuan::create(['nama_satuan' => 'Pcs', 'singkatan' => 'pcs']);

        $produk = Produk::create([
            'kategori_id' => $kategori->id,
            'satuan_id' => $satuan->id,
            'kode_produk' => 'PRD-MUG-01',
            'nama_produk' => 'Mug Keramik Custom',
            'harga_beli' => 15000,
            'harga_jual' => 25000,
            'stok' => 50,
            'stok_minimal' => 5,
        ]);

        // 1. Test Pesanan TikTok Shop
        $response = $this->actingAs($user)->post(route('transaksi-penjualan.store'), [
            'channel' => 'tiktok',
            'no_pesanan_marketplace' => 'TT-9876543210',
            'no_resi' => 'JX9928172635',
            'kurir' => 'J&T Express',
            'tanggal' => now()->toDateString(),
            'nama_pelanggan' => 'customer_tiktok',
            'biaya_admin_marketplace' => 4000,
            'status_pesanan' => 'perlu_dikirim',
            'items' => [
                [
                    'produk_id' => $produk->id,
                    'qty' => 2,
                    'harga_satuan' => 25000,
                ],
            ],
        ]);

        $response->assertRedirect();

        $this->assertDatabaseHas('transaksis', [
            'channel' => 'tiktok',
            'no_pesanan_marketplace' => 'TT-9876543210',
            'grand_total' => 50000,
            'biaya_admin_marketplace' => 4000,
            'penghasilan_bersih' => 46000,
            'status_pesanan' => 'perlu_dikirim',
        ]);

        $this->assertEquals(48, $produk->fresh()->stok);

        // 2. Test Pesanan Lazada
        $responseLazada = $this->actingAs($user)->post(route('transaksi-penjualan.store'), [
            'channel' => 'lazada',
            'no_pesanan_marketplace' => 'LZD-1122334455',
            'no_resi' => 'LEXID88271625',
            'kurir' => 'LEX',
            'tanggal' => now()->toDateString(),
            'nama_pelanggan' => 'customer_lazada',
            'biaya_admin_marketplace' => 3500,
            'status_pesanan' => 'dikirim',
            'items' => [
                [
                    'produk_id' => $produk->id,
                    'qty' => 1,
                    'harga_satuan' => 25000,
                ],
            ],
        ]);

        $responseLazada->assertRedirect();
        $this->assertEquals(47, $produk->fresh()->stok);
    }

    public function test_user_can_view_packing_slip(): void
    {
        $user = User::factory()->create(['is_active' => true]);
        $user->givePermissionTo(['penjualan-list']);

        $kategori = Kategori::create(['nama_kategori' => 'Souvenir']);
        $satuan = Satuan::create(['nama_satuan' => 'Pcs', 'singkatan' => 'pcs']);
        $produk = Produk::create([
            'kategori_id' => $kategori->id,
            'satuan_id' => $satuan->id,
            'kode_produk' => 'PRD-MUG-02',
            'nama_produk' => 'Mug Keramik Custom Hitam',
            'harga_beli' => 15000,
            'harga_jual' => 25000,
            'stok' => 20,
            'stok_minimal' => 5,
        ]);

        $transaksi = Transaksi::create([
            'channel' => 'tiktok',
            'kode_transaksi' => 'TRX-20261104-0001',
            'no_pesanan_marketplace' => 'TT-1234567890',
            'no_resi' => 'SPXID99281726',
            'kurir' => 'SPX Express',
            'tanggal' => now()->toDateString(),
            'nama_pelanggan' => 'customer_test',
            'total_harga' => 25000,
            'grand_total' => 25000,
            'biaya_admin_marketplace' => 2500,
            'penghasilan_bersih' => 22500,
            'status_pesanan' => 'perlu_dikirim',
            'metode_bayar' => 'saldo_penjual',
            'user_id' => $user->id,
        ]);

        $response = $this->actingAs($user)->get(route('transaksi-penjualan.cetak-struk', $transaksi));
        $response->assertOk();
        $response->assertSee('PACKING SLIP');
        $response->assertSee('TT-1234567890');
        $response->assertSee('TikTok Shop');
    }

    public function test_marketplace_import_service_parses_excel_and_creates_records(): void
    {
        $user = User::factory()->create(['is_active' => true]);

        $kategori = Kategori::create(['nama_kategori' => 'Souvenir Gantungan']);
        $satuan = Satuan::create(['nama_satuan' => 'Pcs', 'singkatan' => 'pcs']);

        $produk = Produk::create([
            'kategori_id' => $kategori->id,
            'satuan_id' => $satuan->id,
            'kode_produk' => 'SKU-GANTUNGAN-KAYU',
            'nama_produk' => 'Gantungan Kunci Kayu Custom',
            'harga_beli' => 5000,
            'harga_jual' => 12000,
            'stok' => 100,
            'stok_minimal' => 10,
        ]);

        $spreadsheet = new Spreadsheet;
        $sheet = $spreadsheet->getActiveSheet();

        $sheet->fromArray([
            [
                'No. Pesanan',
                'Status Pesanan',
                'Waktu Pesanan Dibuat',
                'Nomor Referensi SKU',
                'Nama Produk',
                'Nama Variasi',
                'Harga Awal',
                'Jumlah',
                'Total Pembayaran',
                'Biaya Administrasi',
                'Total Penghasilan',
                'Username (Pembeli)',
                'No. Resi',
                'Opsi Pengiriman',
            ],
            [
                '241105XYZ9999',
                'Selesai',
                '2026-11-04 10:00:00',
                'SKU-GANTUNGAN-KAYU',
                'Gantungan Kunci Kayu Custom',
                'Warna Cokelat',
                12000,
                5,
                60000,
                6000,
                54000,
                'customer_kayu',
                'JP192837465',
                'J&T Express',
            ],
        ]);

        $tempPath = tempnam(sys_get_temp_dir(), 'marketplace_test_').'.xlsx';
        $writer = new Xlsx($spreadsheet);
        $writer->save($tempPath);

        $uploadedFile = new UploadedFile(
            $tempPath,
            'Laporan_Pesanan.xlsx',
            'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            null,
            true
        );

        $service = new ShopeeImportService;
        $result = $service->import($uploadedFile, $user->id, 'shopee');

        $this->assertEquals(1, $result['imported']);
        $this->assertEquals(60000, $result['total_omzet']);
        $this->assertEquals(6000, $result['total_admin']);
        $this->assertEquals(54000, $result['total_bersih']);

        $this->assertDatabaseHas('transaksis', [
            'no_pesanan_marketplace' => '241105XYZ9999',
            'channel' => 'shopee',
            'grand_total' => 60000,
            'biaya_admin_marketplace' => 6000,
            'penghasilan_bersih' => 54000,
            'status_pesanan' => 'selesai',
        ]);

        $this->assertEquals(95, $produk->fresh()->stok);

        // Test anti duplikasi
        $secondResult = $service->import($uploadedFile, $user->id, 'shopee');
        $this->assertEquals(0, $secondResult['imported']);
        $this->assertEquals(1, $secondResult['skipped']);
        $this->assertEquals(95, $produk->fresh()->stok);

        @unlink($tempPath);
    }
}
