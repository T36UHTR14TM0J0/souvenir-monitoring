<?php

namespace Tests\Feature;

use App\Models\KasMasuk;
use App\Models\Pengeluaran;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Permission;
use Tests\TestCase;

class KeuanganKpiCardsTest extends TestCase
{
    use RefreshDatabase;

    protected User $user;

    protected function setUp(): void
    {
        parent::setUp();

        Permission::firstOrCreate(['name' => 'kas-masuk-list']);
        Permission::firstOrCreate(['name' => 'kas-masuk-create']);
        Permission::firstOrCreate(['name' => 'pengeluaran-list']);
        Permission::firstOrCreate(['name' => 'pengeluaran-create']);

        $this->user = User::factory()->create(['is_active' => true]);
        $this->user->givePermissionTo(['kas-masuk-list', 'pengeluaran-list']);
    }

    public function test_kas_masuk_kpi_cards_anchor_to_latest_data_when_current_month_has_no_records(): void
    {
        // Seed past data (e.g. October 2026) while current month (e.g. November 2026) has 0 records
        KasMasuk::create([
            'kode_transaksi' => 'KM-20261015-0001',
            'tanggal' => '2026-10-15',
            'tipe' => 'modal_pemilik',
            'sumber' => 'Debit / Transfer',
            'jumlah' => 5000000,
            'keterangan' => 'Setoran modal debit workshop',
            'user_id' => $this->user->id,
        ]);

        KasMasuk::create([
            'kode_transaksi' => 'KM-20261020-0002',
            'tanggal' => '2026-10-20',
            'tipe' => 'pendapatan_lain',
            'sumber' => 'Tunai / Cash',
            'jumlah' => 1500000,
            'keterangan' => 'Penjualan afval akrilik',
            'user_id' => $this->user->id,
        ]);

        $response = $this->actingAs($this->user)->get(route('kas-masuk.index'));

        $response->assertOk();
        $response->assertViewHas('periodeLabel', 'Oktober 2026');
        $response->assertViewHas('totalBulanIni', 6500000.0);
        $response->assertViewHas('totalModalDisetorBulanIni', 5000000.0);
        $response->assertViewHas('totalPendapatanLainBulanIni', 1500000.0);
        $response->assertViewHas('totalAkumulasi', 6500000.0);
        $response->assertSee('Oktober 2026');
        $response->assertSee('5.000.000');
        $response->assertSee('1.500.000');
        $response->assertSee('6.500.000');
    }

    public function test_kas_masuk_kpi_cards_adapt_to_custom_filtered_date_range(): void
    {
        KasMasuk::create([
            'kode_transaksi' => 'KM-20250601-0001',
            'tanggal' => '2025-06-01',
            'tipe' => 'modal_pemilik',
            'sumber' => 'Debit / Transfer',
            'jumlah' => 10000000,
            'keterangan' => 'Modal awal juni 2025',
            'user_id' => $this->user->id,
        ]);

        KasMasuk::create([
            'kode_transaksi' => 'KM-20261015-0002',
            'tanggal' => '2026-10-15',
            'tipe' => 'modal_pemilik',
            'sumber' => 'Debit / Transfer',
            'jumlah' => 2000000,
            'keterangan' => 'Modal okt 2026',
            'user_id' => $this->user->id,
        ]);

        $response = $this->actingAs($this->user)->get(route('kas-masuk.index', [
            'dari_tanggal' => '2025-06-01',
            'sampai_tanggal' => '2025-06-30',
        ]));

        $response->assertOk();
        $response->assertViewHas('totalBulanIni', 10000000.0);
        $response->assertViewHas('totalModalDisetorBulanIni', 10000000.0);
        $response->assertSee('10.000.000');
    }

    public function test_pengeluaran_kpi_cards_anchor_to_latest_data_and_display_all_categories(): void
    {
        // Seed past data for October 2026
        Pengeluaran::create([
            'kode_pengeluaran' => 'PGL-20261010-0001',
            'tanggal' => '2026-10-10',
            'tipe_alokasi' => 'bisnis',
            'kategori' => 'bahan_baku',
            'jumlah' => 4500000,
            'keterangan' => 'Beli akrilik lembaran 3mm',
            'user_id' => $this->user->id,
        ]);

        Pengeluaran::create([
            'kode_pengeluaran' => 'PGL-20261012-0002',
            'tanggal' => '2026-10-12',
            'tipe_alokasi' => 'bisnis',
            'kategori' => 'iklan',
            'jumlah' => 2000000,
            'keterangan' => 'Topup Shopee Ads',
            'user_id' => $this->user->id,
        ]);

        Pengeluaran::create([
            'kode_pengeluaran' => 'PGL-20261015-0003',
            'tanggal' => '2026-10-15',
            'tipe_alokasi' => 'pribadi',
            'kategori' => 'prive_penarikan',
            'jumlah' => 3000000,
            'keterangan' => 'Tarik dana pribadi owner',
            'user_id' => $this->user->id,
        ]);

        Pengeluaran::create([
            'kode_pengeluaran' => 'PGL-20261018-0004',
            'tanggal' => '2026-10-18',
            'tipe_alokasi' => 'kasbon',
            'kategori' => 'kasbon_karyawan',
            'status_kasbon' => 'belum_lunas',
            'karyawan_nama' => 'Budi Operator',
            'jumlah' => 500000,
            'keterangan' => 'Kasbon Budi',
            'user_id' => $this->user->id,
        ]);

        $response = $this->actingAs($this->user)->get(route('pengeluaran.index'));

        $response->assertOk();
        $response->assertViewHas('periodeLabel', 'Oktober 2026');
        $response->assertViewHas('totalModalBahanBulanIni', 4500000.0);
        $response->assertViewHas('totalOperasionalBulanIni', 2000000.0);
        $response->assertViewHas('totalPribadiBulanIni', 3000000.0);
        $response->assertViewHas('totalKasbonBulanIni', 500000.0);
        $response->assertViewHas('totalBulanIni', 10000000.0);

        $response->assertSeeText('Pengeluaran Toko & Bahan');
        $response->assertSee('Modal Bahan (Oktober 2026)');
        $response->assertSee('4.500.000');
        $response->assertSee('2.000.000');
        $response->assertSee('3.500.000'); // Kasbon + Prive = 500rb + 3jt
        $response->assertSee('10.000.000');
    }
}
