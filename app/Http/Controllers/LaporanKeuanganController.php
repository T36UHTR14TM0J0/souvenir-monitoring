<?php

namespace App\Http\Controllers;

use App\Exports\LaporanKeuanganExport;
use App\Models\KasMasuk;
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
        'pengeluaran',
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

        $pdf->setPaper('A4', in_array($tab, ['laba-rugi', 'penjualan'], true) ? 'landscape' : 'portrait');

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
            'pengeluaran' => $this->dataPengeluaran($bulan, $tahun),
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

        $pembelian = (float) Pengeluaran::modalBahan()
            ->whereMonth('tanggal', $bulan)
            ->whereYear('tanggal', $tahun)
            ->sum('jumlah');

        $pengeluaran = (float) Pengeluaran::operasionalMurni()
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
            'pengeluaran' => 'Biaya-Operasional-Dan-Bahan',
            'kasbon' => 'Kasbon-Tim',
            default => 'Laba-Rugi',
        };
    }

    private function getTahunList()
    {
        $isSqlite = DB::connection()->getDriverName() === 'sqlite';
        $yearExpr = $isSqlite ? "strftime('%Y', tanggal) as tahun" : 'YEAR(tanggal) as tahun';

        $tahunTransaksi = Transaksi::select(DB::raw($yearExpr))->distinct()->pluck('tahun');
        $tahunPengeluaran = Pengeluaran::select(DB::raw($yearExpr))->distinct()->pluck('tahun');
        $tahunKasMasuk = KasMasuk::select(DB::raw($yearExpr))->distinct()->pluck('tahun');

        $tahunList = collect()
            ->merge($tahunTransaksi)
            ->merge($tahunPengeluaran)
            ->merge($tahunKasMasuk)
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

            case 'pengeluaran':
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
