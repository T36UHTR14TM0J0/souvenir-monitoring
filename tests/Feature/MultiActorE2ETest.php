<?php

namespace Tests\Feature;

use App\Models\KasMasuk;
use App\Models\Pengeluaran;
use App\Models\Produk;
use App\Models\Transaksi;
use App\Models\TransaksiDetail;
use App\Models\User;
use Database\Seeders\KategoriSeeder;
use Database\Seeders\ProdukSeeder;
use Database\Seeders\RolePermissionSeeder;
use Database\Seeders\SatuanSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MultiActorE2ETest extends TestCase
{
    use RefreshDatabase;

    private User $pemilik;

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

        $this->pemilik = User::where('email', 'pemilik@gmail.com')->first();
        $this->admin = User::where('email', 'admin@gmail.com')->first();
        $this->bendahara = User::where('email', 'bendahara@gmail.com')->first();

        // Sample Transaksi Penjualan
        $souvenir = Produk::where('jenis', 'souvenir_jadi')->first();
        $transaksi = Transaksi::create([
            'channel' => 'shopee',
            'kode_transaksi' => 'TRX-SAMPLE-001',
            'no_pesanan_marketplace' => '261104SHP12345',
            'no_resi' => 'SPXID0987654321',
            'kurir' => 'SPX Standard',
            'tanggal' => now()->toDateString(),
            'nama_pelanggan' => 'Budi Santoso',
            'total_harga' => 50000,
            'total_diskon' => 0,
            'grand_total' => 50000,
            'biaya_admin_marketplace' => 4000,
            'penghasilan_bersih' => 46000,
            'status_pesanan' => 'perlu_dikirim',
            'metode_bayar' => 'saldo_shopee',
            'catatan' => 'Pesanan kustomisasi nama',
            'user_id' => $this->admin->id,
        ]);

        TransaksiDetail::create([
            'transaksi_id' => $transaksi->id,
            'produk_id' => $souvenir->id,
            'nama_produk' => $souvenir->nama_produk,
            'harga_satuan' => 25000,
            'qty' => 2,
            'subtotal' => 50000,
        ]);

        // Sample Pengeluaran Bahan Baku
        Pengeluaran::create([
            'kode_pengeluaran' => 'PGL-BAHAN-001',
            'tanggal' => now()->toDateString(),
            'tipe_alokasi' => 'bisnis',
            'kategori' => 'bahan_baku',
            'keterangan' => 'Belanja Blank Mug dan Dus Sublimasi',
            'jumlah' => 350000,
            'user_id' => $this->bendahara->id,
        ]);

        // Sample Pengeluaran Operasional & Ads
        Pengeluaran::create([
            'kode_pengeluaran' => 'PGL-SAMPLE-001',
            'tanggal' => now()->toDateString(),
            'tipe_alokasi' => 'bisnis',
            'kategori' => 'iklan',
            'keterangan' => 'Iklan Shopee Ads',
            'jumlah' => 250000,
            'user_id' => $this->bendahara->id,
        ]);

        // Sample Kas Masuk
        KasMasuk::create([
            'kode_transaksi' => 'KM-SAMPLE-001',
            'tanggal' => now()->toDateString(),
            'tipe' => 'modal_pemilik',
            'sumber' => 'Debit / Transfer',
            'jumlah' => 5000000,
            'keterangan' => 'Modal awal',
            'user_id' => $this->bendahara->id,
        ]);
    }

    public function test_pemilik_can_access_executive_dashboard_and_all_modules(): void
    {
        $response = $this->actingAs($this->pemilik)->get('/dashboard');
        $response->assertStatus(200);

        $responsePenjualan = $this->actingAs($this->pemilik)->get(route('transaksi-penjualan.index'));
        $responsePenjualan->assertStatus(200);

        $responseProduk = $this->actingAs($this->pemilik)->get(route('produk.index'));
        $responseProduk->assertStatus(200);

        $responsePengeluaran = $this->actingAs($this->pemilik)->get(route('pengeluaran.index'));
        $responsePengeluaran->assertStatus(200);

        $responseKasMasuk = $this->actingAs($this->pemilik)->get(route('kas-masuk.index'));
        $responseKasMasuk->assertStatus(200);

        $responseLaporan = $this->actingAs($this->pemilik)->get(route('laporan.keuangan'));
        $responseLaporan->assertStatus(200);

        $responseUser = $this->actingAs($this->pemilik)->get(route('users.index'));
        $responseUser->assertStatus(200);

        $responseRoles = $this->actingAs($this->pemilik)->get(route('roles.index'));
        $responseRoles->assertStatus(200);
        $responseRoles->assertSee('Pemilik');
        $responseRoles->assertSee('Bendahara');
        $responseRoles->assertSee('Admin');

        $responsePermissions = $this->actingAs($this->pemilik)->get(route('permissions.index'));
        $responsePermissions->assertStatus(200);
    }

    public function test_admin_can_manage_marketplace_orders_and_print_packing_slip(): void
    {
        $response = $this->actingAs($this->admin)->get('/dashboard');
        $response->assertStatus(200);

        $responseOrders = $this->actingAs($this->admin)->get(route('transaksi-penjualan.index'));
        $responseOrders->assertStatus(200);
        $responseOrders->assertSee('Pesanan Marketplace');

        $transaksi = Transaksi::first();
        $this->assertNotNull($transaksi);

        // Cetak Packing Slip
        $responseSlip = $this->actingAs($this->admin)->get(route('transaksi-penjualan.cetak-struk', $transaksi));
        $responseSlip->assertStatus(200);
        $responseSlip->assertSee('PACKING SLIP');

        // Update status order
        $responseUpdate = $this->actingAs($this->admin)->patch(route('transaksi-penjualan.update-status', $transaksi), [
            'status_pesanan' => 'siap_packing',
            'kurir' => 'SPX Standard',
            'no_resi' => 'SPXID99887766',
        ]);
        $responseUpdate->assertRedirect();
        $this->assertDatabaseHas('transaksis', [
            'id' => $transaksi->id,
            'status_pesanan' => 'siap_packing',
            'kurir' => 'SPX Standard',
            'no_resi' => 'SPXID99887766',
        ]);

        // Admin can manage products
        $responseProduk = $this->actingAs($this->admin)->get(route('produk.index'));
        $responseProduk->assertStatus(200);
    }

    public function test_bendahara_can_manage_financials_expenses_and_reports(): void
    {
        $response = $this->actingAs($this->bendahara)->get('/dashboard');
        $response->assertStatus(200);

        // Kas Masuk & Pencairan Marketplace
        $responseKasMasuk = $this->actingAs($this->bendahara)->get(route('kas-masuk.index'));
        $responseKasMasuk->assertStatus(200);

        // Biaya Operasional & Bahan Baku
        $responsePengeluaran = $this->actingAs($this->bendahara)->get(route('pengeluaran.index'));
        $responsePengeluaran->assertStatus(200);

        // Laporan Keuangan
        $responseLaporan = $this->actingAs($this->bendahara)->get(route('laporan.keuangan'));
        $responseLaporan->assertStatus(200);
        $responseLaporan->assertSee('Laporan Keuangan');
    }

    public function test_database_has_seeded_souvenir_products(): void
    {
        $souvenirs = Produk::souvenirJadi()->count();

        $this->assertEquals(14, $souvenirs, '14 Souvenir Jadi harus ter-seed');
    }
}
