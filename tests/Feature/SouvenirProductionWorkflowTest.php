<?php

namespace Tests\Feature;

use App\Models\Kategori;
use App\Models\Produk;
use App\Models\Satuan;
use App\Models\Transaksi;
use App\Models\TransaksiDetail;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SouvenirProductionWorkflowTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;

    private User $bendahara;

    private User $pemilik;

    private Kategori $kategori;

    private Satuan $satuan;

    protected function setUp(): void
    {
        parent::setUp();

        $this->artisan('migrate');

        $this->pemilik = User::factory()->create([
            'email' => 'pemilik_test@gmail.com',
            'name' => 'Pemilik Toko',
            'is_active' => true,
        ]);
        $this->pemilik->assignRole('Pemilik');

        $this->admin = User::factory()->create([
            'email' => 'admin_test@gmail.com',
            'name' => 'Admin Toko',
            'is_active' => true,
        ]);
        $this->admin->assignRole('Admin');

        $this->bendahara = User::factory()->create([
            'email' => 'bendahara_test@gmail.com',
            'name' => 'Bendahara Keuangan',
            'is_active' => true,
        ]);
        $this->bendahara->assignRole('Bendahara');

        $this->kategori = Kategori::create([
            'nama_kategori' => 'Mug Souvenir',
            'deskripsi' => 'Kategori Mug Custom',
        ]);

        $this->satuan = Satuan::create([
            'nama_satuan' => 'Pieces',
            'singkatan' => 'pcs',
        ]);
    }

    public function test_admin_can_view_and_print_spk_produksi(): void
    {
        $produk = Produk::create([
            'kategori_id' => $this->kategori->id,
            'satuan_id' => $this->satuan->id,
            'jenis' => 'souvenir_jadi',
            'kode_produk' => 'MUG-CUSTOM-01',
            'nama_produk' => 'Mug Custom Anime Jibran',
            'harga_beli' => 12000,
            'harga_jual' => 25000,
            'tipe_diskon' => 'none',
            'diskon' => 0,
            'stok' => 50,
            'stok_minimal' => 5,
        ]);

        $transaksi = Transaksi::create([
            'channel' => 'shopee',
            'kode_transaksi' => 'SPK-202611-0001',
            'no_pesanan_marketplace' => '261104SHP9999',
            'tanggal' => now()->toDateString(),
            'nama_pelanggan' => 'Budi Santoso',
            'total_harga' => 50000,
            'total_diskon' => 0,
            'grand_total' => 50000,
            'biaya_admin_marketplace' => 4500,
            'penghasilan_bersih' => 45500,
            'status_pesanan' => 'menunggu_produksi',
            'metode_bayar' => 'shopee_pay',
            'catatan' => 'Cetak desain sisi depan dan belakang, packaging kardus cokelat',
            'user_id' => $this->admin->id,
        ]);

        TransaksiDetail::create([
            'transaksi_id' => $transaksi->id,
            'produk_id' => $produk->id,
            'nama_produk' => $produk->nama_produk,
            'harga_satuan' => 25000,
            'qty' => 2,
            'subtotal' => 50000,
            'catatan' => 'Desain Varian Merah',
        ]);

        $response = $this->actingAs($this->admin)->get(route('transaksi-penjualan.cetak-struk', $transaksi));

        $response->assertStatus(200);
        $response->assertSee('PACKING SLIP');
        $response->assertSee('Mug Custom Anime Jibran');
        $response->assertSee('Petugas Packing Gudang');
    }

    public function test_admin_can_update_order_production_status(): void
    {
        $transaksi = Transaksi::create([
            'channel' => 'tiktok',
            'kode_transaksi' => 'TRX-TT-001',
            'no_pesanan_marketplace' => 'TT99887766',
            'tanggal' => now()->toDateString(),
            'nama_pelanggan' => 'Siti Rahma',
            'total_harga' => 35000,
            'total_diskon' => 0,
            'grand_total' => 35000,
            'biaya_admin_marketplace' => 3000,
            'penghasilan_bersih' => 32000,
            'status_pesanan' => 'siap_packing',
            'metode_bayar' => 'tiktok_wallet',
            'user_id' => $this->admin->id,
        ]);

        $response = $this->actingAs($this->admin)->patch(route('transaksi-penjualan.update-status', $transaksi), [
            'status_pesanan' => 'dikirim',
            'kurir' => 'J&T Express',
            'no_resi' => 'JT123456789ID',
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('transaksis', [
            'id' => $transaksi->id,
            'status_pesanan' => 'dikirim',
            'kurir' => 'J&T Express',
            'no_resi' => 'JT123456789ID',
        ]);
    }

    public function test_produk_can_be_differentiated_between_souvenir_and_raw_material(): void
    {
        $souvenir = Produk::create([
            'kategori_id' => $this->kategori->id,
            'satuan_id' => $this->satuan->id,
            'jenis' => 'souvenir_jadi',
            'kode_produk' => 'SVN-001',
            'nama_produk' => 'Gantungan Kunci Akrilik Custom',
            'harga_beli' => 5000,
            'harga_jual' => 15000,
            'stok' => 100,
            'stok_minimal' => 10,
        ]);

        $bahanBaku = Produk::create([
            'kategori_id' => $this->kategori->id,
            'satuan_id' => $this->satuan->id,
            'jenis' => 'bahan_baku',
            'kode_produk' => 'BB-001',
            'nama_produk' => 'Ring Gantungan Kunci Besi 25mm',
            'harga_beli' => 500,
            'harga_jual' => 500,
            'stok' => 500,
            'stok_minimal' => 50,
        ]);

        $this->assertEquals('Souvenir Jadi', $souvenir->jenis_label);
        $this->assertEquals('Bahan Baku', $bahanBaku->jenis_label);
        $this->assertEquals(1, Produk::souvenirJadi()->count());
        $this->assertEquals(1, Produk::bahanBaku()->count());
    }
}
