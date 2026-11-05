<?php

namespace Tests\Feature;

use App\Models\Produk;
use App\Models\User;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class KatalogSouvenirRedesignTest extends TestCase
{
    use RefreshDatabase;

    protected User $admin;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(DatabaseSeeder::class);
        $this->admin = User::where('email', 'admin@gmail.com')->first();
    }

    public function test_sidebar_and_page_header_display_katalog_souvenir(): void
    {
        $response = $this->actingAs($this->admin)->get(route('produk.index'));

        $response->assertStatus(200);
        $response->assertSee('Katalog Souvenir');
        $response->assertDontSee('Katalog Souvenir Jadi');
    }

    public function test_index_renders_four_kpi_cards_and_clean_filter_bar(): void
    {
        $response = $this->actingAs($this->admin)->get(route('produk.index'));

        $response->assertStatus(200);

        // 4 KPI Cards
        $response->assertSee('Total Macam Souvenir');
        $response->assertSee('Modal Persediaan (HPP)');
        $response->assertSee('Potensi Nilai Jual');
        $response->assertSee('Perlu Restock');

        // Header and Filter Button (matching penerimaan-produk)
        $response->assertSee('Tambah Produk Souvenir');
        $response->assertSee('Filter Data');
    }

    public function test_table_renders_sticky_freeze_action_column(): void
    {
        $response = $this->actingAs($this->admin)->get(route('produk.index'));

        $response->assertStatus(200);
        $response->assertSee('col-freeze-aksi');
        $response->assertSee('Informasi Souvenir');
        $response->assertSee('Harga Jual &amp; Modal', false);
        $response->assertSee('Stok Etalase');
    }

    public function test_filtering_by_promo_status(): void
    {
        $response = $this->actingAs($this->admin)->get(route('produk.index', ['promo_status' => 'promo']));

        $response->assertStatus(200);
        $response->assertSee('Promo: Sedang Promo');
    }

    public function test_filtering_by_stok_status(): void
    {
        $response = $this->actingAs($this->admin)->get(route('produk.index', ['stok_status' => 'menipis']));

        $response->assertStatus(200);
        $response->assertSee('Stok: Menipis');
    }

    public function test_create_and_edit_pages_use_katalog_souvenir_titles(): void
    {
        $responseCreate = $this->actingAs($this->admin)->get(route('produk.create'));
        $responseCreate->assertStatus(200);
        $responseCreate->assertSee('Tambah Produk Souvenir');

        $souvenir = Produk::souvenirJadi()->first();
        $responseEdit = $this->actingAs($this->admin)->get(route('produk.edit', $souvenir));
        $responseEdit->assertStatus(200);
        $responseEdit->assertSee('Edit Produk Souvenir');
    }
}
