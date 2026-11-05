<?php

namespace App\Http\Controllers;

use App\Exports\LaporanKeuanganExport;
use App\Models\KasMasuk;
use App\Models\PemakaianBahan;
use App\Models\Pembelian;
use App\Models\PenerimaanProduk;
use App\Models\Pengeluaran;
use App\Models\Transaksi;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use Spatie\Permission\Models\Permission;

class LaporanKeuanganController extends Controller
{
    private const VALID_TABS = [
        'laba-rugi',
        'penjualan',
        'pembelian',
        'pengeluaran',
        'produksi',
        'pribadi',
        'rekonsiliasi',
        'kasbon',
    ];

    /**
     * Halaman utama laporan keuangan (multi-tab).
     */
    public function index(Request $request)
    {
        $tab = $this->resolveAuthorizedTab($request);
        $bulan = $request->get('bulan', 'all');   // 1-12 atau 'all'
        $tahun = (int) $request->get('tahun', now()->year);

        $data = $this->resolveTabData($tab, $bulan, $tahun);

        $tahunList = $this->getTahunList();
        $bulanList = $this->getBulanList();

        return view('laporan.keuangan', array_merge($data, compact(
            'tab', 'bulan', 'tahun', 'tahunList', 'bulanList'
        ), ['withChart' => true]));
    }

    /**
     * Export PDF sesuai tab aktif.
     */
    public function exportPdf(Request $request)
    {
        $tab = $this->resolveAuthorizedTab($request);
        $bulan = $request->get('bulan', 'all');
        $tahun = (int) $request->get('tahun', now()->year);

        $data = $this->resolveTabData($tab, $bulan, $tahun);
        $bulanList = $this->getBulanList();

        $pdf = Pdf::loadView('laporan.keuangan-pdf', array_merge($data, compact(
            'tab', 'bulan', 'tahun', 'bulanList'
        )));

        $pdf->setPaper('A4', in_array($tab, ['laba-rugi', 'penjualan', 'pembelian'], true) ? 'landscape' : 'portrait');

        $labelTab = $this->getTabFilenameLabel($tab);
        $filename = "Laporan-{$labelTab}-{$tahun}.pdf";

        return $pdf->download($filename);
    }

    /**
     * Export Excel sesuai tab aktif.
     */
    public function exportExcel(Request $request)
    {
        $tab = $this->resolveAuthorizedTab($request);
        $bulan = $request->get('bulan', 'all');
        $tahun = (int) $request->get('tahun', now()->year);

        $data = $this->resolveTabData($tab, $bulan, $tahun);
        $rows = $this->transformToExcelRows($tab, $data);
        $bulanList = $this->getBulanList();

        $labelPeriode = $bulan === 'all'
            ? 'Semua Bulan '.$tahun
            : ($bulanList[(int) $bulan] ?? 'Bulan '.$bulan).' '.$tahun;

        $labelTab = $this->getTabFilenameLabel($tab);
        $filename = 'Laporan-'.$labelTab.'-'.str_replace(' ', '-', $labelPeriode).'.xlsx';

        return Excel::download(
            new LaporanKeuanganExport($rows, $tab, 'Laporan '.str_replace('-', ' ', $labelTab), $labelPeriode),
            $filename
        );
    }

    /**
     * Pastikan user memiliki permission untuk membuka tab laporan yang diminta.
     */
    private function resolveAuthorizedTab(Request $request): string
    {
        $user = $request->user();
        $requestedTab = $request->get('tab');

        if ($requestedTab !== null && in_array($requestedTab, self::VALID_TABS, true)) {
            $permName = "laporan-{$requestedTab}";
            $permExists = Permission::where('name', $permName)->exists();

            if ($permExists && (! $user || ! $user->can($permName))) {
                abort(403, 'Anda tidak memiliki hak akses untuk membuka menu laporan ini.');
            }

            return $requestedTab;
        }

        // Jika tidak ada parameter tab, arahkan ke tab pertama yang diizinkan untuk user tersebut
        foreach (self::VALID_TABS as $candidateTab) {
            $permName = "laporan-{$candidateTab}";
            $permExists = Permission::where('name', $permName)->exists();

            if (! $permExists || ($user && $user->can($permName))) {
                return $candidateTab;
            }
        }

        abort(403, 'Anda tidak memiliki hak akses ke modul laporan.');
    }

    /**
     * Resolve data array berdasarkan tab.
     */
    private function resolveTabData(string $tab, string|int $bulan, int $tahun): array
    {
        return match ($tab) {
            'penjualan' => $this->dataPenjualan($bulan, $tahun),
            'pembelian' => $this->dataPembelian($bulan, $tahun),
            'pengeluaran' => $this->dataPengeluaran($bulan, $tahun),
            'produksi' => $this->dataProduksi($bulan, $tahun),
            'pribadi' => $this->dataPribadi($bulan, $tahun),
            'rekonsiliasi' => $this->dataRekonsiliasi($bulan, $tahun),
            'kasbon' => $this->dataKasbon($bulan, $tahun),
            default => $this->dataLabaRugi($bulan, $tahun),
        };
    }

    /**
     * Scope helper untuk pesanan marketplace yang valid (bukan batal/retur).
     */
    private function validTransaksiQuery(): Builder
    {
        return Transaksi::query()->where(function (Builder $q) {
            $q->whereNull('status_pesanan')
                ->orWhereNotIn('status_pesanan', ['batal', 'retur']);
        });
    }

    // ============================================
    // DATA: LABA/RUGI (MURNI OPERASIONAL BISNIS)
    // ============================================
    private function dataLabaRugi($bulan, int $tahun): array
    {
        if ($bulan === 'all') {
            $rows = [];
            for ($i = 1; $i <= 12; $i++) {
                $rows[] = $this->buildLabaRugiRow(
                    Carbon::create(null, $i, 1)->translatedFormat('F'),
                    $i,
                    $tahun
                );
            }
        } else {
            $bulanInt = (int) $bulan;
            $bulanNama = $this->getBulanList()[$bulanInt] ?? 'Bulan Ini';
            $rows = [
                $this->buildLabaRugiRow("Ringkasan {$bulanNama} {$tahun}", $bulanInt, $tahun),
            ];
        }

        $totalPenjualan = (float) array_sum(array_column($rows, 'penjualan'));
        $totalBiayaAdmin = (float) array_sum(array_column($rows, 'biaya_admin'));
        $totalPenghasilanBersih = (float) array_sum(array_column($rows, 'penghasilan_bersih'));
        $totalPendapatanLain = (float) array_sum(array_column($rows, 'pendapatan_lain'));
        $totalPembelian = (float) array_sum(array_column($rows, 'pembelian'));
        $totalLabaKotor = (float) array_sum(array_column($rows, 'laba_kotor'));
        $totalPengeluaran = (float) array_sum(array_column($rows, 'pengeluaran'));
        $totalBiayaIklan = (float) array_sum(array_column($rows, 'biaya_iklan'));
        $totalLabaBersih = (float) array_sum(array_column($rows, 'laba_bersih'));
        $totalPrive = (float) array_sum(array_column($rows, 'prive'));
        $totalBatalRetur = (float) array_sum(array_column($rows, 'omzet_batal_retur'));
        $countBatalRetur = (int) array_sum(array_column($rows, 'count_batal_retur'));

        $marginBersih = $totalPenjualan > 0
            ? round(($totalLabaBersih / $totalPenjualan) * 100, 1)
            : 0.0;

        $search = trim((string) request()->get('search', ''));
        $filteredRows = $rows;
        if ($search !== '') {
            $qLower = mb_strtolower($search);
            $filteredRows = array_values(array_filter($rows, fn (array $r) => str_contains(mb_strtolower((string) $r['label']), $qLower)));
        }

        return [
            'rows' => $filteredRows,
            'total' => [
                'penjualan' => $totalPenjualan,
                'biaya_admin' => $totalBiayaAdmin,
                'penghasilan_bersih' => $totalPenghasilanBersih,
                'pendapatan_lain' => $totalPendapatanLain,
                'pembelian' => $totalPembelian,
                'laba_kotor' => $totalLabaKotor,
                'pengeluaran' => $totalPengeluaran,
                'biaya_iklan' => $totalBiayaIklan,
                'laba_bersih' => $totalLabaBersih,
                'margin_bersih' => $marginBersih,
                'prive' => $totalPrive,
                'omzet_batal_retur' => $totalBatalRetur,
                'count_batal_retur' => $countBatalRetur,
            ],
            'chart' => [
                'labels' => array_column($rows, 'label'),
                'penjualan' => array_column($rows, 'penghasilan_bersih'),
                'pembelian' => array_column($rows, 'pembelian'),
                'pengeluaran' => array_column($rows, 'pengeluaran'),
                'laba_bersih' => array_column($rows, 'laba_bersih'),
            ],
        ];
    }

    /**
     * Hitung satu baris periode Laba/Rugi.
     */
    private function buildLabaRugiRow(string $label, int $bulan, int $tahun): array
    {
        $validTrx = $this->validTransaksiQuery()
            ->whereMonth('tanggal', $bulan)
            ->whereYear('tanggal', $tahun);

        $penjualanKotor = (float) (clone $validTrx)->sum('grand_total');
        $biayaAdmin = (float) (clone $validTrx)->sum('biaya_admin_marketplace');
        $penghasilanBersih = $penjualanKotor - $biayaAdmin;

        $batalReturQuery = Transaksi::query()
            ->whereIn('status_pesanan', ['batal', 'retur'])
            ->whereMonth('tanggal', $bulan)
            ->whereYear('tanggal', $tahun);

        $omzetBatalRetur = (float) (clone $batalReturQuery)->sum('grand_total');
        $countBatalRetur = (int) (clone $batalReturQuery)->count();

        $pendapatanLain = (float) KasMasuk::where('tipe', 'pendapatan_lain')
            ->whereMonth('tanggal', $bulan)
            ->whereYear('tanggal', $tahun)
            ->sum('jumlah');

        $pembelian = (float) Pembelian::whereMonth('tanggal', $bulan)
            ->whereYear('tanggal', $tahun)
            ->sum('grand_total');

        $pengeluaran = (float) Pengeluaran::bisnis()
            ->whereMonth('tanggal', $bulan)
            ->whereYear('tanggal', $tahun)
            ->sum('jumlah');

        $biayaIklan = (float) Pengeluaran::bisnis()
            ->where('kategori', 'iklan')
            ->whereMonth('tanggal', $bulan)
            ->whereYear('tanggal', $tahun)
            ->sum('jumlah');

        $prive = (float) Pengeluaran::pribadi()
            ->whereMonth('tanggal', $bulan)
            ->whereYear('tanggal', $tahun)
            ->sum('jumlah');

        $labaKotor = ($penghasilanBersih + $pendapatanLain) - $pembelian;
        $labaBersih = $labaKotor - $pengeluaran;

        return [
            'label' => $label,
            'bulan' => $bulan,
            'penjualan' => $penjualanKotor,
            'biaya_admin' => $biayaAdmin,
            'penghasilan_bersih' => $penghasilanBersih,
            'pendapatan_lain' => $pendapatanLain,
            'pembelian' => $pembelian,
            'laba_kotor' => $labaKotor,
            'pengeluaran' => $pengeluaran,
            'biaya_iklan' => $biayaIklan,
            'laba_bersih' => $labaBersih,
            'prive' => $prive,
            'omzet_batal_retur' => $omzetBatalRetur,
            'count_batal_retur' => $countBatalRetur,
        ];
    }

    // ============================================
    // DATA: PENJUALAN MARKETPLACE
    // ============================================
    private function dataPenjualan($bulan, int $tahun): array
    {
        $query = Transaksi::with('details')->whereYear('tanggal', $tahun);

        if ($bulan !== 'all') {
            $query->whereMonth('tanggal', (int) $bulan);
        }

        $search = trim((string) request()->get('search', ''));

        $transaksis = $query->orderBy('tanggal', 'desc')->orderBy('id', 'desc')->get();

        if ($search !== '') {
            $qLower = mb_strtolower($search);
            $transaksis = $transaksis->filter(fn (Transaksi $t) => str_contains(mb_strtolower((string) $t->kode_transaksi), $qLower)
                || str_contains(mb_strtolower((string) $t->no_pesanan_marketplace), $qLower)
                || str_contains(mb_strtolower((string) $t->nama_pelanggan), $qLower)
                || str_contains(mb_strtolower((string) $t->channel), $qLower)
                || str_contains(mb_strtolower((string) $t->kurir), $qLower)
                || str_contains(mb_strtolower((string) $t->no_resi), $qLower)
            )->values();
        }

        // Pisahkan transaksi valid vs batal/retur untuk akurasi omzet
        $validTransaksis = $transaksis->filter(fn (Transaksi $t) => ! in_array($t->status_pesanan, ['batal', 'retur'], true));
        $batalReturTransaksis = $transaksis->filter(fn (Transaksi $t) => in_array($t->status_pesanan, ['batal', 'retur'], true));

        $totalPenjualan = (float) $validTransaksis->sum('grand_total');
        $totalBiayaAdmin = (float) $validTransaksis->sum('biaya_admin_marketplace');
        $totalBersih = $totalPenjualan - $totalBiayaAdmin;
        $totalDiskon = (float) $validTransaksis->sum('total_diskon');
        $totalItem = (int) $validTransaksis->sum(fn ($t) => $t->details->sum('qty'));
        $totalTransaksi = $validTransaksis->count();

        $totalBatalRetur = (float) $batalReturTransaksis->sum('grand_total');
        $countBatalRetur = $batalReturTransaksis->count();

        // Breakdown per Marketplace Channel (Shopee, Lazada, TikTok Shop)
        $channels = [
            'shopee' => ['label' => 'Shopee', 'badge' => 'warning', 'icon' => 'fa-bag-shopping'],
            'lazada' => ['label' => 'Lazada', 'badge' => 'primary', 'icon' => 'fa-cart-shopping'],
            'tiktok' => ['label' => 'TikTok Shop', 'badge' => 'dark', 'icon' => 'fa-brands fa-tiktok'],
        ];

        $perChannel = collect($channels)->map(function (array $meta, string $key) use ($validTransaksis, $totalPenjualan) {
            $items = $validTransaksis->where('channel', $key);
            $omzetKotor = (float) $items->sum('grand_total');
            $adminFee = (float) $items->sum('biaya_admin_marketplace');
            $bersih = $omzetKotor - $adminFee;

            return [
                'channel' => $key,
                'label' => $meta['label'],
                'badge' => $meta['badge'],
                'icon' => $meta['icon'],
                'transaksi' => $items->count(),
                'item' => (int) $items->sum(fn ($t) => $t->details->sum('qty')),
                'omzet_kotor' => $omzetKotor,
                'biaya_admin' => $adminFee,
                'penghasilan_bersih' => $bersih,
                'persen_omzet' => $totalPenjualan > 0 ? round(($omzetKotor / $totalPenjualan) * 100, 1) : 0,
            ];
        })->values();

        // Chart per bulan / per hari (Pesanan Valid)
        if ($bulan === 'all') {
            $labels = [];
            $values = [];
            for ($i = 1; $i <= 12; $i++) {
                $labels[] = Carbon::create(null, $i, 1)->translatedFormat('M');
                $values[] = (float) $this->validTransaksiQuery()
                    ->whereMonth('tanggal', $i)
                    ->whereYear('tanggal', $tahun)
                    ->sum('grand_total');
            }
        } else {
            $daysInMonth = Carbon::create($tahun, (int) $bulan, 1)->daysInMonth;
            $labels = [];
            $values = [];
            for ($d = 1; $d <= $daysInMonth; $d++) {
                $labels[] = $d;
                $values[] = (float) $this->validTransaksiQuery()
                    ->whereDate('tanggal', Carbon::create($tahun, (int) $bulan, $d)->format('Y-m-d'))
                    ->sum('grand_total');
            }
        }

        return [
            'rows' => $transaksis,
            'per_channel' => $perChannel,
            'total' => [
                'penjualan' => $totalPenjualan,
                'biaya_admin' => $totalBiayaAdmin,
                'penghasilan_bersih' => $totalBersih,
                'diskon' => $totalDiskon,
                'item' => $totalItem,
                'transaksi' => $totalTransaksi,
                'batal_retur_nominal' => $totalBatalRetur,
                'batal_retur_count' => $countBatalRetur,
            ],
            'chart' => [
                'labels' => $labels,
                'values' => $values,
            ],
        ];
    }

    // ============================================
    // DATA: PEMBELIAN (HPP / BAHAN BAKU)
    // ============================================
    private function dataPembelian($bulan, int $tahun): array
    {
        $query = Pembelian::with('details')->whereYear('tanggal', $tahun);

        if ($bulan !== 'all') {
            $query->whereMonth('tanggal', (int) $bulan);
        }

        $search = trim((string) request()->get('search', ''));

        $pembelians = $query->orderBy('tanggal', 'desc')->orderBy('id', 'desc')->get();

        if ($search !== '') {
            $qLower = mb_strtolower($search);
            $pembelians = $pembelians->filter(fn (Pembelian $p) => str_contains(mb_strtolower((string) $p->kode_pembelian), $qLower)
                || str_contains(mb_strtolower((string) $p->supplier), $qLower)
                || str_contains(mb_strtolower((string) $p->platform), $qLower)
                || str_contains(mb_strtolower((string) $p->no_invoice_online), $qLower)
            )->values();
        }

        $totalPembelian = (float) $pembelians->sum('grand_total');
        $totalBelanjaBahan = (float) $pembelians->sum('total_harga');
        $totalOngkir = (float) $pembelians->sum('ongkir');
        $totalDiskon = (float) $pembelians->sum('total_diskon');
        $totalItem = (int) $pembelians->sum(fn ($p) => $p->details->sum('qty'));
        $totalTransaksi = $pembelians->count();

        $countDiterima = $pembelians->where('status_pembelian', 'diterima')->count();
        $countDalamPerjalanan = $pembelians->whereIn('status_pembelian', ['dipesan', 'dikirim'])->count();

        // Breakdown per platform pembelian
        $perPlatform = $pembelians->groupBy(fn ($p) => $p->platform ?: 'offline')->map(function ($items, $platform) {
            return [
                'platform' => Pembelian::PLATFORMS[$platform] ?? ucfirst($platform),
                'count' => $items->count(),
                'ongkir' => (float) $items->sum('ongkir'),
                'total' => (float) $items->sum('grand_total'),
            ];
        })->sortByDesc('total')->values();

        if ($bulan === 'all') {
            $labels = [];
            $values = [];
            for ($i = 1; $i <= 12; $i++) {
                $labels[] = Carbon::create(null, $i, 1)->translatedFormat('M');
                $values[] = (float) Pembelian::whereMonth('tanggal', $i)
                    ->whereYear('tanggal', $tahun)->sum('grand_total');
            }
        } else {
            $daysInMonth = Carbon::create($tahun, (int) $bulan, 1)->daysInMonth;
            $labels = [];
            $values = [];
            for ($d = 1; $d <= $daysInMonth; $d++) {
                $labels[] = $d;
                $values[] = (float) Pembelian::whereDate('tanggal', Carbon::create($tahun, (int) $bulan, $d)->format('Y-m-d'))
                    ->sum('grand_total');
            }
        }

        return [
            'rows' => $pembelians,
            'per_platform' => $perPlatform,
            'total' => [
                'pembelian' => $totalPembelian,
                'belanja_bahan' => $totalBelanjaBahan,
                'ongkir' => $totalOngkir,
                'diskon' => $totalDiskon,
                'item' => $totalItem,
                'transaksi' => $totalTransaksi,
                'diterima' => $countDiterima,
                'dalam_perjalanan' => $countDalamPerjalanan,
            ],
            'chart' => [
                'labels' => $labels,
                'values' => $values,
            ],
        ];
    }

    // ============================================
    // DATA: PENGELUARAN TOKO (MURNI BISNIS & ADS)
    // ============================================
    private function dataPengeluaran($bulan, int $tahun): array
    {
        $query = Pengeluaran::bisnis()->whereYear('tanggal', $tahun);

        if ($bulan !== 'all') {
            $query->whereMonth('tanggal', (int) $bulan);
        }

        $search = trim((string) request()->get('search', ''));

        $pengeluarans = $query->orderBy('tanggal', 'desc')->orderBy('id', 'desc')->get();

        if ($search !== '') {
            $qLower = mb_strtolower($search);
            $pengeluarans = $pengeluarans->filter(fn (Pengeluaran $p) => str_contains(mb_strtolower((string) $p->kode_pengeluaran), $qLower)
                || str_contains(mb_strtolower((string) $p->keterangan), $qLower)
                || str_contains(mb_strtolower((string) $p->kategori_label), $qLower)
            )->values();
        }

        $totalPengeluaran = (float) $pengeluarans->sum('jumlah');
        $totalIklan = (float) $pengeluarans->where('kategori', 'iklan')->sum('jumlah');
        $totalPacking = (float) $pengeluarans->where('kategori', 'packing')->sum('jumlah');
        $totalOperasionalLain = $totalPengeluaran - $totalIklan - $totalPacking;
        $totalTransaksi = $pengeluarans->count();

        // Pengeluaran per kategori bisnis
        $perKategori = $pengeluarans->groupBy('kategori')->map(function ($items, $kategori) {
            return [
                'kategori_key' => $kategori,
                'kategori' => Pengeluaran::KATEGORI[$kategori] ?? ucfirst($kategori),
                'total' => (float) $items->sum('jumlah'),
                'count' => $items->count(),
            ];
        })->sortByDesc('total')->values();

        if ($bulan === 'all') {
            $labels = [];
            $values = [];
            for ($i = 1; $i <= 12; $i++) {
                $labels[] = Carbon::create(null, $i, 1)->translatedFormat('M');
                $values[] = (float) Pengeluaran::bisnis()->whereMonth('tanggal', $i)
                    ->whereYear('tanggal', $tahun)->sum('jumlah');
            }
        } else {
            $daysInMonth = Carbon::create($tahun, (int) $bulan, 1)->daysInMonth;
            $labels = [];
            $values = [];
            for ($d = 1; $d <= $daysInMonth; $d++) {
                $labels[] = $d;
                $values[] = (float) Pengeluaran::bisnis()->whereDate('tanggal', Carbon::create($tahun, (int) $bulan, $d)->format('Y-m-d'))
                    ->sum('jumlah');
            }
        }

        return [
            'rows' => $pengeluarans,
            'total' => [
                'pengeluaran' => $totalPengeluaran,
                'bisnis' => $totalPengeluaran,
                'iklan' => $totalIklan,
                'packing' => $totalPacking,
                'operasional_lain' => $totalOperasionalLain,
                'transaksi' => $totalTransaksi,
                'per_kategori' => $perKategori,
            ],
            'chart' => [
                'labels' => $labels,
                'values' => $values,
            ],
        ];
    }

    // ============================================
    // DATA: PRODUKSI & GUDANG WORKSHOP (QC & BAHAN)
    // ============================================
    private function dataProduksi($bulan, int $tahun): array
    {
        $queryPenerimaan = PenerimaanProduk::with('details.produk')->whereYear('tanggal', $tahun);
        $queryPemakaian = PemakaianBahan::with('details.produk')->whereYear('tanggal', $tahun);

        if ($bulan !== 'all') {
            $queryPenerimaan->whereMonth('tanggal', (int) $bulan);
            $queryPemakaian->whereMonth('tanggal', (int) $bulan);
        }

        $penerimaans = $queryPenerimaan->orderBy('tanggal', 'desc')->orderBy('id', 'desc')->get();
        $pemakaians = $queryPemakaian->orderBy('tanggal', 'desc')->orderBy('id', 'desc')->get();

        $search = trim((string) request()->get('search', ''));
        if ($search !== '') {
            $qLower = mb_strtolower($search);
            $penerimaans = $penerimaans->filter(fn (PenerimaanProduk $p) => str_contains(mb_strtolower((string) $p->kode_penerimaan), $qLower)
                || str_contains(mb_strtolower((string) $p->nama_petugas), $qLower)
                || str_contains(mb_strtolower((string) $p->catatan), $qLower)
            )->values();
            $pemakaians = $pemakaians->filter(fn (PemakaianBahan $pb) => str_contains(mb_strtolower((string) $pb->kode_pemakaian), $qLower)
                || str_contains(mb_strtolower((string) $pb->nama_operator), $qLower)
                || str_contains(mb_strtolower((string) $pb->keperluan), $qLower)
            )->values();
        }

        $totalBagus = (float) $penerimaans->sum('total_qty_bagus');
        $totalReject = (float) $penerimaans->sum('total_qty_reject');
        $totalProduksiFisik = $totalBagus + $totalReject;
        $rasioLolosQc = $totalProduksiFisik > 0
            ? round(($totalBagus / $totalProduksiFisik) * 100, 1)
            : 100.0;

        $totalQtyBahan = (float) $pemakaians->sum('total_kuantitas');
        $estimasiNilaiBahan = (float) $pemakaians->sum(function (PemakaianBahan $pb) {
            return $pb->details->sum(fn ($d) => (float) $d->qty * (float) ($d->produk->harga_beli ?? 0));
        });

        $labels = [];
        $bagusSeries = [];
        $rejectSeries = [];

        if ($bulan === 'all') {
            for ($i = 1; $i <= 12; $i++) {
                $labels[] = Carbon::create(null, $i, 1)->translatedFormat('M');
                $bagusSeries[] = (float) PenerimaanProduk::whereMonth('tanggal', $i)
                    ->whereYear('tanggal', $tahun)->sum('total_qty_bagus');
                $rejectSeries[] = (float) PenerimaanProduk::whereMonth('tanggal', $i)
                    ->whereYear('tanggal', $tahun)->sum('total_qty_reject');
            }
        } else {
            $daysInMonth = Carbon::create($tahun, (int) $bulan, 1)->daysInMonth;
            for ($d = 1; $d <= $daysInMonth; $d++) {
                $dateStr = Carbon::create($tahun, (int) $bulan, $d)->format('Y-m-d');
                $labels[] = $d;
                $bagusSeries[] = (float) PenerimaanProduk::whereDate('tanggal', $dateStr)->sum('total_qty_bagus');
                $rejectSeries[] = (float) PenerimaanProduk::whereDate('tanggal', $dateStr)->sum('total_qty_reject');
            }
        }

        return [
            'rows' => $penerimaans,
            'rows_pemakaian' => $pemakaians,
            'total' => [
                'batch_produksi' => $penerimaans->count(),
                'total_bagus' => $totalBagus,
                'total_reject' => $totalReject,
                'rasio_qc' => $rasioLolosQc,
                'trx_pemakaian' => $pemakaians->count(),
                'qty_bahan' => $totalQtyBahan,
                'estimasi_nilai_bahan' => $estimasiNilaiBahan,
            ],
            'chart' => [
                'labels' => $labels,
                'bagus' => $bagusSeries,
                'reject' => $rejectSeries,
            ],
        ];
    }

    // ============================================
    // DATA: KEUANGAN PRIBADI (PERSONAL FINANCE)
    // ============================================
    private function dataPribadi($bulan, int $tahun): array
    {
        $query = Pengeluaran::pribadi()->whereYear('tanggal', $tahun);

        if ($bulan !== 'all') {
            $query->whereMonth('tanggal', (int) $bulan);
        }

        $search = trim((string) request()->get('search', ''));

        $pribadis = $query->orderBy('tanggal', 'desc')->orderBy('id', 'desc')->get();

        if ($search !== '') {
            $qLower = mb_strtolower($search);
            $pribadis = $pribadis->filter(fn (Pengeluaran $p) => str_contains(mb_strtolower((string) $p->kode_pengeluaran), $qLower)
                || str_contains(mb_strtolower((string) $p->keterangan), $qLower)
                || str_contains(mb_strtolower((string) $p->kategori_label), $qLower)
            )->values();
        }

        $totalPribadi = (float) $pribadis->sum('jumlah');
        $makanPribadi = (float) $pribadis->where('kategori', 'makan_pribadi')->sum('jumlah');
        $kebutuhanRumah = (float) $pribadis->where('kategori', 'kebutuhan_rumah')->sum('jumlah');
        $privePenarikan = (float) $pribadis->where('kategori', 'prive_penarikan')->sum('jumlah');
        $pribadiLainnya = (float) $pribadis->where('kategori', 'pribadi_lainnya')->sum('jumlah');
        $totalTransaksi = $pribadis->count();

        $perKategori = $pribadis->groupBy('kategori')->map(function ($items, $kategori) {
            return [
                'kategori' => Pengeluaran::KATEGORI[$kategori] ?? ucfirst($kategori),
                'total' => (float) $items->sum('jumlah'),
                'count' => $items->count(),
            ];
        })->sortByDesc('total')->values();

        if ($bulan === 'all') {
            $labels = [];
            $values = [];
            for ($i = 1; $i <= 12; $i++) {
                $labels[] = Carbon::create(null, $i, 1)->translatedFormat('M');
                $values[] = (float) Pengeluaran::pribadi()->whereMonth('tanggal', $i)
                    ->whereYear('tanggal', $tahun)->sum('jumlah');
            }
        } else {
            $daysInMonth = Carbon::create($tahun, (int) $bulan, 1)->daysInMonth;
            $labels = [];
            $values = [];
            for ($d = 1; $d <= $daysInMonth; $d++) {
                $labels[] = $d;
                $values[] = (float) Pengeluaran::pribadi()->whereDate('tanggal', Carbon::create($tahun, (int) $bulan, $d)->format('Y-m-d'))
                    ->sum('jumlah');
            }
        }

        return [
            'rows' => $pribadis,
            'total' => [
                'pengeluaran' => $totalPribadi,
                'makan_pribadi' => $makanPribadi,
                'kebutuhan_rumah' => $kebutuhanRumah,
                'prive_penarikan' => $privePenarikan,
                'pribadi_lainnya' => $pribadiLainnya,
                'transaksi' => $totalTransaksi,
                'per_kategori' => $perKategori,
            ],
            'chart' => [
                'labels' => $labels,
                'values' => $values,
            ],
        ];
    }

    // ============================================
    // DATA: REKONSILIASI OWNER (MODAL, PENCAIRAN & PRIVE)
    // ============================================
    private function dataRekonsiliasi($bulan, int $tahun): array
    {
        $queryModal = KasMasuk::where('tipe', 'modal_pemilik')->whereYear('tanggal', $tahun);
        $queryPendapatanLain = KasMasuk::where('tipe', 'pendapatan_lain')->whereYear('tanggal', $tahun);
        $queryPrive = Pengeluaran::pribadi()->whereYear('tanggal', $tahun);
        $queryKasbon = Pengeluaran::kasbon()->whereYear('tanggal', $tahun);

        if ($bulan !== 'all') {
            $queryModal->whereMonth('tanggal', (int) $bulan);
            $queryPendapatanLain->whereMonth('tanggal', (int) $bulan);
            $queryPrive->whereMonth('tanggal', (int) $bulan);
            $queryKasbon->whereMonth('tanggal', (int) $bulan);
        }

        $totalModalDisetor = (float) $queryModal->sum('jumlah');
        $totalPendapatanLain = (float) $queryPendapatanLain->sum('jumlah');
        $totalKasMasuk = $totalModalDisetor + $totalPendapatanLain;
        $totalPriveDitarik = (float) $queryPrive->sum('jumlah');
        $totalKasbonAktif = (float) $queryKasbon->where('status_kasbon', 'belum_lunas')->sum('jumlah');

        $saldoNetto = $totalModalDisetor - $totalPriveDitarik;

        // Breakdown bulanan
        $rows = [];
        for ($i = 1; $i <= 12; $i++) {
            $modal = (float) KasMasuk::where('tipe', 'modal_pemilik')
                ->whereMonth('tanggal', $i)->whereYear('tanggal', $tahun)->sum('jumlah');
            $pendapatanLain = (float) KasMasuk::where('tipe', 'pendapatan_lain')
                ->whereMonth('tanggal', $i)->whereYear('tanggal', $tahun)->sum('jumlah');
            $prive = (float) Pengeluaran::pribadi()
                ->whereMonth('tanggal', $i)->whereYear('tanggal', $tahun)->sum('jumlah');
            $selisih = ($modal + $pendapatanLain) - $prive;

            $rows[] = [
                'bulan_angka' => $i,
                'bulan_label' => Carbon::create(null, $i, 1)->translatedFormat('F'),
                'modal' => $modal,
                'pendapatan_lain' => $pendapatanLain,
                'prive' => $prive,
                'selisih' => $selisih,
            ];
        }

        $listModalQuery = KasMasuk::whereYear('tanggal', $tahun);
        if ($bulan !== 'all') {
            $listModalQuery->whereMonth('tanggal', (int) $bulan);
        }
        $listModal = $listModalQuery->latest('tanggal')->latest('id')->limit(30)->get();

        $search = trim((string) request()->get('search', ''));
        $filteredRows = $rows;
        if ($search !== '') {
            $qLower = mb_strtolower($search);
            $filteredRows = array_values(array_filter($rows, fn (array $r) => str_contains(mb_strtolower((string) $r['bulan_label']), $qLower)));
            $listModal = $listModal->filter(fn (KasMasuk $km) => str_contains(mb_strtolower((string) $km->kode_transaksi), $qLower)
                || str_contains(mb_strtolower((string) $km->sumber), $qLower)
                || str_contains(mb_strtolower((string) $km->keterangan), $qLower)
            )->values();
        }

        return [
            'rows' => $filteredRows,
            'list_modal' => $listModal,
            'total' => [
                'modal_disetor' => $totalModalDisetor,
                'pendapatan_lain' => $totalPendapatanLain,
                'total_kas_masuk' => $totalKasMasuk,
                'prive_ditarik' => $totalPriveDitarik,
                'saldo_netto' => $saldoNetto,
                'kasbon_aktif' => $totalKasbonAktif,
            ],
            'chart' => [
                'labels' => array_column($rows, 'bulan_label'),
                'modal' => array_column($rows, 'modal'),
                'pendapatan_lain' => array_column($rows, 'pendapatan_lain'),
                'prive' => array_column($rows, 'prive'),
            ],
        ];
    }

    // ============================================
    // DATA: BUKU KASBON KARYAWAN
    // ============================================
    private function dataKasbon($bulan, int $tahun): array
    {
        $query = Pengeluaran::kasbon()->whereYear('tanggal', $tahun);

        if ($bulan !== 'all') {
            $query->whereMonth('tanggal', (int) $bulan);
        }

        $search = trim((string) request()->get('search', ''));

        $kasbons = $query->orderBy('tanggal', 'desc')->orderBy('id', 'desc')->get();

        if ($search !== '') {
            $qLower = mb_strtolower($search);
            $kasbons = $kasbons->filter(fn (Pengeluaran $p) => str_contains(mb_strtolower((string) $p->kode_pengeluaran), $qLower)
                || str_contains(mb_strtolower((string) $p->karyawan_nama), $qLower)
                || str_contains(mb_strtolower((string) $p->keterangan), $qLower)
            )->values();
        }

        $totalKasbon = (float) $kasbons->sum('jumlah');
        $totalLunas = (float) $kasbons->where('status_kasbon', 'lunas')->sum('jumlah');
        $totalBelumLunas = (float) $kasbons->where('status_kasbon', '!=', 'lunas')->sum('jumlah');

        $perKaryawan = $kasbons->groupBy('karyawan_nama')->map(function ($items, $nama) {
            $namaClean = $nama ?: 'Tanpa Nama';
            $total = (float) $items->sum('jumlah');
            $lunas = (float) $items->where('status_kasbon', 'lunas')->sum('jumlah');
            $sisa = $total - $lunas;

            return [
                'nama' => $namaClean,
                'total' => $total,
                'lunas' => $lunas,
                'sisa' => $sisa,
                'count' => $items->count(),
            ];
        })->sortByDesc('sisa')->values();

        return [
            'rows' => $kasbons,
            'per_karyawan' => $perKaryawan,
            'total' => [
                'total_kasbon' => $totalKasbon,
                'total_lunas' => $totalLunas,
                'total_belum_lunas' => $totalBelumLunas,
                'transaksi' => $kasbons->count(),
            ],
            'chart' => [
                'labels' => $perKaryawan->pluck('nama')->toArray(),
                'values' => $perKaryawan->pluck('sisa')->toArray(),
            ],
        ];
    }

    // ============================================
    // HELPER: Daftar Tahun & Bulan
    // ============================================
    private function getBulanList(): array
    {
        return [
            1 => 'Januari', 2 => 'Februari', 3 => 'Maret',
            4 => 'April',   5 => 'Mei',      6 => 'Juni',
            7 => 'Juli',    8 => 'Agustus',  9 => 'September',
            10 => 'Oktober', 11 => 'November', 12 => 'Desember',
        ];
    }

    private function getTabFilenameLabel(string $tab): string
    {
        return match ($tab) {
            'penjualan' => 'Penjualan-Marketplace',
            'pembelian' => 'Pembelian-Bahan-Baku',
            'pengeluaran' => 'Biaya-Operasional-Ads',
            'produksi' => 'Produksi-Gudang-QC',
            'pribadi' => 'Keuangan-Pribadi',
            'rekonsiliasi' => 'Rekonsiliasi-Kas-Owner',
            'kasbon' => 'Kasbon-Tim',
            default => 'Laba-Rugi',
        };
    }

    private function getTahunList()
    {
        $isSqlite = DB::connection()->getDriverName() === 'sqlite';
        $yearExpr = $isSqlite ? "strftime('%Y', tanggal) as tahun" : 'YEAR(tanggal) as tahun';

        $tahunTransaksi = Transaksi::select(DB::raw($yearExpr))->distinct()->pluck('tahun');
        $tahunPembelian = Pembelian::select(DB::raw($yearExpr))->distinct()->pluck('tahun');
        $tahunPengeluaran = Pengeluaran::select(DB::raw($yearExpr))->distinct()->pluck('tahun');
        $tahunKasMasuk = KasMasuk::select(DB::raw($yearExpr))->distinct()->pluck('tahun');
        $tahunProduksi = PenerimaanProduk::select(DB::raw($yearExpr))->distinct()->pluck('tahun');

        $tahunList = collect()
            ->merge($tahunTransaksi)
            ->merge($tahunPembelian)
            ->merge($tahunPengeluaran)
            ->merge($tahunKasMasuk)
            ->merge($tahunProduksi)
            ->map(fn ($y) => (int) $y)
            ->filter()
            ->unique()
            ->sortDesc()
            ->values();

        if ($tahunList->isEmpty()) {
            $tahunList = collect([now()->year]);
        }

        return $tahunList;
    }

    /**
     * Transform data per tab ke bentuk array Excel.
     */
    private function transformToExcelRows(string $tab, array $data): array
    {
        $rows = [];

        switch ($tab) {
            case 'laba-rugi':
                foreach ($data['rows'] as $row) {
                    $rows[] = [
                        $row['label'],
                        $row['penjualan'],
                        $row['biaya_admin'],
                        $row['penghasilan_bersih'],
                        $row['pembelian'],
                        $row['laba_kotor'],
                        $row['pengeluaran'],
                        $row['laba_bersih'],
                    ];
                }
                $rows[] = [
                    'TOTAL',
                    $data['total']['penjualan'],
                    $data['total']['biaya_admin'],
                    $data['total']['penghasilan_bersih'],
                    $data['total']['pembelian'],
                    $data['total']['laba_kotor'],
                    $data['total']['pengeluaran'],
                    $data['total']['laba_bersih'],
                ];
                break;

            case 'penjualan':
                foreach ($data['rows'] as $trx) {
                    $omzet = (float) $trx->grand_total;
                    $admin = (float) $trx->biaya_admin_marketplace;
                    $rows[] = [
                        $trx->kode_transaksi,
                        $trx->tanggal->format('d/m/Y'),
                        strtoupper($trx->channel ?? 'OFFLINE'),
                        $trx->nama_pelanggan ?? '-',
                        $trx->status_pesanan_label,
                        (int) $trx->details->sum('qty'),
                        $omzet,
                        $admin,
                        $omzet - $admin,
                    ];
                }
                $rows[] = [
                    'TOTAL (PESANAN VALID)',
                    '',
                    '',
                    '',
                    '',
                    $data['total']['item'],
                    $data['total']['penjualan'],
                    $data['total']['biaya_admin'],
                    $data['total']['penghasilan_bersih'],
                ];
                break;

            case 'pembelian':
                foreach ($data['rows'] as $pbl) {
                    $rows[] = [
                        $pbl->kode_pembelian,
                        $pbl->tanggal->format('d/m/Y'),
                        $pbl->supplier ?? '-',
                        $pbl->platform_label,
                        $pbl->status_label,
                        (int) $pbl->details->sum('qty'),
                        (float) $pbl->ongkir,
                        (float) $pbl->grand_total,
                    ];
                }
                $rows[] = [
                    'TOTAL',
                    '',
                    '',
                    '',
                    '',
                    $data['total']['item'],
                    $data['total']['ongkir'],
                    $data['total']['pembelian'],
                ];
                break;

            case 'pengeluaran':
            case 'pribadi':
                foreach ($data['rows'] as $pgl) {
                    $rows[] = [
                        $pgl->kode_pengeluaran,
                        $pgl->tanggal->format('d/m/Y'),
                        $pgl->kategori_label,
                        $pgl->keterangan,
                        (float) $pgl->jumlah,
                    ];
                }
                $rows[] = [
                    'TOTAL',
                    '',
                    '',
                    '',
                    $data['total']['pengeluaran'],
                ];
                break;

            case 'produksi':
                foreach ($data['rows'] as $prd) {
                    $rows[] = [
                        $prd->kode_penerimaan,
                        $prd->tanggal->format('d/m/Y'),
                        $prd->nama_petugas ?? '-',
                        ucfirst(str_replace('_', ' ', $prd->tipe_produksi ?? 'produksi')),
                        (float) $prd->total_qty_bagus,
                        (float) $prd->total_qty_reject,
                    ];
                }
                $rows[] = [
                    'TOTAL',
                    '',
                    '',
                    'Rasio Lolos QC: '.$data['total']['rasio_qc'].'%',
                    $data['total']['total_bagus'],
                    $data['total']['total_reject'],
                ];
                break;

            case 'rekonsiliasi':
                foreach ($data['rows'] as $r) {
                    $rows[] = [
                        $r['bulan_label'],
                        (float) $r['modal'],
                        (float) $r['pendapatan_lain'],
                        (float) $r['prive'],
                        (float) $r['selisih'],
                    ];
                }
                $rows[] = [
                    'TOTAL',
                    $data['total']['modal_disetor'],
                    $data['total']['pendapatan_lain'],
                    $data['total']['prive_ditarik'],
                    $data['total']['saldo_netto'],
                ];
                break;

            case 'kasbon':
                foreach ($data['rows'] as $kb) {
                    $rows[] = [
                        $kb->kode_pengeluaran,
                        $kb->tanggal->format('d/m/Y'),
                        $kb->karyawan_nama ?? '-',
                        $kb->keterangan,
                        (float) $kb->jumlah,
                        ucfirst(str_replace('_', ' ', $kb->status_kasbon ?? 'belum_lunas')),
                    ];
                }
                $rows[] = [
                    'TOTAL',
                    '',
                    '',
                    '',
                    $data['total']['total_kasbon'],
                    'Sisa Belum Lunas: '.$data['total']['total_belum_lunas'],
                ];
                break;
        }

        return $rows;
    }
}
