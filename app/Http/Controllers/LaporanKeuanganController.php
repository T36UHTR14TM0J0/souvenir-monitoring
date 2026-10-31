<?php

namespace App\Http\Controllers;

use App\Models\Pembelian;
use App\Models\Pengeluaran;
use App\Models\Transaksi;
use App\Exports\LaporanKeuanganExport;
use Maatwebsite\Excel\Facades\Excel;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LaporanKeuanganController extends Controller
{
    /**
     * Halaman utama laporan keuangan (multi-tab).
     */
    public function index(Request $request)
    {
        $tab    = $request->get('tab', 'laba-rugi');
        $bulan  = $request->get('bulan', 'all');   // 1-12 atau 'all'
        $tahun  = (int) $request->get('tahun', now()->year);

        // Data untuk tab aktif
        $data = match ($tab) {
            'penjualan'   => $this->dataPenjualan($bulan, $tahun),
            'pembelian'   => $this->dataPembelian($bulan, $tahun),
            'pengeluaran' => $this->dataPengeluaran($bulan, $tahun),
            default       => $this->dataLabaRugi($bulan, $tahun),
        };

        // Daftar tahun yang tersedia (untuk dropdown)
        $tahunList = $this->getTahunList();

        $bulanList = [
            1  => 'Januari', 2  => 'Februari', 3  => 'Maret',
            4  => 'April',   5  => 'Mei',      6  => 'Juni',
            7  => 'Juli',    8  => 'Agustus',  9  => 'September',
            10 => 'Oktober', 11 => 'November', 12 => 'Desember',
        ];

        return view('laporan.keuangan', array_merge($data, compact(
            'tab', 'bulan', 'tahun', 'tahunList', 'bulanList'
        ), ['withChart' => true]));
    }

    /**
     * Export PDF sesuai tab aktif.
     */
    public function exportPdf(Request $request)
    {
        $tab    = $request->get('tab', 'laba-rugi');
        $bulan  = $request->get('bulan', 'all');
        $tahun  = (int) $request->get('tahun', now()->year);

        $data = match ($tab) {
            'penjualan'   => $this->dataPenjualan($bulan, $tahun),
            'pembelian'   => $this->dataPembelian($bulan, $tahun),
            'pengeluaran' => $this->dataPengeluaran($bulan, $tahun),
            default       => $this->dataLabaRugi($bulan, $tahun),
        };

        $bulanList = [
            1 => 'Januari', 2 => 'Februari', 3 => 'Maret',
            4 => 'April', 5 => 'Mei', 6 => 'Juni',
            7 => 'Juli', 8 => 'Agustus', 9 => 'September',
            10 => 'Oktober', 11 => 'November', 12 => 'Desember',
        ];

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('laporan.keuangan-pdf', array_merge($data, compact(
            'tab', 'bulan', 'tahun', 'bulanList'
        )));

        $pdf->setPaper('A4', 'portrait');

        $labelTab = [
            'laba-rugi'   => 'Laba-Rugi',
            'penjualan'   => 'Penjualan',
            'pembelian'   => 'Pembelian',
            'pengeluaran' => 'Pengeluaran',
        ][$tab] ?? 'Laporan';

        $filename = "Laporan-{$labelTab}-{$tahun}.pdf";

        return $pdf->download($filename);
    }
    // ============================================
    // DATA: LABA/RUGI
    // ============================================
    private function dataLabaRugi($bulan, $tahun): array
    {
        $query = function ($model) use ($bulan, $tahun) {
            $q = $model::query();
            if ($bulan !== 'all') {
                $q->whereMonth('tanggal', (int) $bulan);
            }
            return $q->whereYear('tanggal', $tahun);
        };

        // Kalau bulan='all' → tampilkan per bulan
        if ($bulan === 'all') {
            $rows = [];
            for ($i = 1; $i <= 12; $i++) {
                $penjualan = (float) Transaksi::whereMonth('tanggal', $i)
                    ->whereYear('tanggal', $tahun)->sum('grand_total');
                $pembelian = (float) Pembelian::whereMonth('tanggal', $i)
                    ->whereYear('tanggal', $tahun)->sum('grand_total');
                $pengeluaran = (float) Pengeluaran::whereMonth('tanggal', $i)
                    ->whereYear('tanggal', $tahun)->sum('jumlah');

                $labaKotor  = $penjualan - $pembelian;
                $labaBersih = $labaKotor - $pengeluaran;

                $rows[] = [
                    'label'        => Carbon::create(null, $i, 1)->translatedFormat('F'),
                    'bulan'        => $i,
                    'penjualan'    => $penjualan,
                    'pembelian'    => $pembelian,
                    'laba_kotor'   => $labaKotor,
                    'pengeluaran'  => $pengeluaran,
                    'laba_bersih'  => $labaBersih,
                ];
            }

            $totalPenjualan   = array_sum(array_column($rows, 'penjualan'));
            $totalPembelian   = array_sum(array_column($rows, 'pembelian'));
            $totalLabaKotor   = array_sum(array_column($rows, 'laba_kotor'));
            $totalPengeluaran = array_sum(array_column($rows, 'pengeluaran'));
            $totalLabaBersih  = array_sum(array_column($rows, 'laba_bersih'));
        } else {
            // Bulan spesifik
            $penjualan = (float) $query(Transaksi::class)->sum('grand_total');
            $pembelian = (float) $query(Pembelian::class)->sum('grand_total');
            $pengeluaran = (float) $query(Pengeluaran::class)->sum('jumlah');

            $totalPenjualan   = $penjualan;
            $totalPembelian   = $pembelian;
            $totalLabaKotor   = $penjualan - $pembelian;
            $totalPengeluaran = $pengeluaran;
            $totalLabaBersih  = $totalLabaKotor - $pengeluaran;

            // Untuk tabel: tampilkan detail per transaksi (kalau bulan spesifik)
            $rows = [
                [
                    'label'        => 'Ringkasan Bulan',
                    'penjualan'    => $penjualan,
                    'pembelian'    => $pembelian,
                    'laba_kotor'   => $totalLabaKotor,
                    'pengeluaran'  => $pengeluaran,
                    'laba_bersih'  => $totalLabaBersih,
                ],
            ];
        }

        return [
            'rows'  => $rows,
            'total' => [
                'penjualan'   => $totalPenjualan,
                'pembelian'   => $totalPembelian,
                'laba_kotor'  => $totalLabaKotor,
                'pengeluaran' => $totalPengeluaran,
                'laba_bersih' => $totalLabaBersih,
            ],
            'chart' => [
                'labels'      => array_column($rows, 'label'),
                'penjualan'   => array_column($rows, 'penjualan'),
                'pembelian'   => array_column($rows, 'pembelian'),
                'pengeluaran' => array_column($rows, 'pengeluaran'),
                'laba_bersih' => array_column($rows, 'laba_bersih'),
            ],
        ];
    }

    // ============================================
    // DATA: PENJUALAN
    // ============================================
    private function dataPenjualan($bulan, $tahun): array
    {
        $query = Transaksi::with('details')->whereYear('tanggal', $tahun);

        if ($bulan !== 'all') {
            $query->whereMonth('tanggal', (int) $bulan);
        }

        $transaksis = $query->orderBy('tanggal', 'desc')->get();

        $totalPenjualan   = (float) $transaksis->sum('grand_total');
        $totalTransaksi   = $transaksis->count();
        $totalDiskon      = (float) $transaksis->sum('total_diskon');
        $totalItemTerjual = $transaksis->sum(fn($t) => $t->details->sum('qty'));

        // Grafik per hari atau per bulan
        if ($bulan === 'all') {
            $labels = [];
            $values = [];
            for ($i = 1; $i <= 12; $i++) {
                $labels[] = Carbon::create(null, $i, 1)->translatedFormat('M');
                $values[] = (float) Transaksi::whereMonth('tanggal', $i)
                    ->whereYear('tanggal', $tahun)->sum('grand_total');
            }
        } else {
            $daysInMonth = Carbon::create($tahun, (int) $bulan, 1)->daysInMonth;
            $labels = [];
            $values = [];
            for ($d = 1; $d <= $daysInMonth; $d++) {
                $labels[] = $d;
                $values[] = (float) Transaksi::whereDate('tanggal', Carbon::create($tahun, (int) $bulan, $d)->format('Y-m-d'))
                    ->sum('grand_total');
            }
        }

        return [
            'rows'  => $transaksis,
            'total' => [
                'penjualan'   => $totalPenjualan,
                'transaksi'   => $totalTransaksi,
                'diskon'      => $totalDiskon,
                'item'        => $totalItemTerjual,
            ],
            'chart' => [
                'labels' => $labels,
                'values' => $values,
            ],
        ];
    }

    // ============================================
    // DATA: PEMBELIAN
    // ============================================
    private function dataPembelian($bulan, $tahun): array
    {
        $query = Pembelian::with('details')->whereYear('tanggal', $tahun);

        if ($bulan !== 'all') {
            $query->whereMonth('tanggal', (int) $bulan);
        }

        $pembelians = $query->orderBy('tanggal', 'desc')->get();

        $totalPembelian   = (float) $pembelians->sum('grand_total');
        $totalTransaksi   = $pembelians->count();
        $totalDiskon      = (float) $pembelians->sum('total_diskon');
        $totalItemMasuk   = $pembelians->sum(fn($p) => $p->details->sum('qty'));

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
            'rows'  => $pembelians,
            'total' => [
                'pembelian' => $totalPembelian,
                'transaksi' => $totalTransaksi,
                'diskon'    => $totalDiskon,
                'item'      => $totalItemMasuk,
            ],
            'chart' => [
                'labels' => $labels,
                'values' => $values,
            ],
        ];
    }

    // ============================================
    // DATA: PENGELUARAN
    // ============================================
    private function dataPengeluaran($bulan, $tahun): array
    {
        $query = Pengeluaran::whereYear('tanggal', $tahun);

        if ($bulan !== 'all') {
            $query->whereMonth('tanggal', (int) $bulan);
        }

        $pengeluarans = $query->orderBy('tanggal', 'desc')->get();

        $totalPengeluaran = (float) $pengeluarans->sum('jumlah');
        $totalTransaksi   = $pengeluarans->count();

        // Pengeluaran per kategori
        $perKategori = $pengeluarans->groupBy('kategori')->map(function ($items, $kategori) {
            return [
                'kategori' => Pengeluaran::KATEGORI[$kategori] ?? ucfirst($kategori),
                'total'    => $items->sum('jumlah'),
                'count'    => $items->count(),
            ];
        })->sortByDesc('total')->values();

        if ($bulan === 'all') {
            $labels = [];
            $values = [];
            for ($i = 1; $i <= 12; $i++) {
                $labels[] = Carbon::create(null, $i, 1)->translatedFormat('M');
                $values[] = (float) Pengeluaran::whereMonth('tanggal', $i)
                    ->whereYear('tanggal', $tahun)->sum('jumlah');
            }
        } else {
            $daysInMonth = Carbon::create($tahun, (int) $bulan, 1)->daysInMonth;
            $labels = [];
            $values = [];
            for ($d = 1; $d <= $daysInMonth; $d++) {
                $labels[] = $d;
                $values[] = (float) Pengeluaran::whereDate('tanggal', Carbon::create($tahun, (int) $bulan, $d)->format('Y-m-d'))
                    ->sum('jumlah');
            }
        }

        return [
            'rows'        => $pengeluarans,
            'total'       => [
                'pengeluaran' => $totalPengeluaran,
                'transaksi'   => $totalTransaksi,
                'per_kategori'=> $perKategori,
            ],
            'chart'       => [
                'labels' => $labels,
                'values' => $values,
            ],
        ];
    }

    // ============================================
    // HELPER: Daftar Tahun
    // ============================================
    private function getTahunList()
    {
        $tahunTransaksi   = Transaksi::select(DB::raw('YEAR(tanggal) as tahun'))->distinct()->pluck('tahun');
        $tahunPembelian   = Pembelian::select(DB::raw('YEAR(tanggal) as tahun'))->distinct()->pluck('tahun');
        $tahunPengeluaran = Pengeluaran::select(DB::raw('YEAR(tanggal) as tahun'))->distinct()->pluck('tahun');

        $tahunList = collect()
            ->merge($tahunTransaksi)
            ->merge($tahunPembelian)
            ->merge($tahunPengeluaran)
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
     * Export Excel sesuai tab aktif.
     */
    public function exportExcel(Request $request)
    {
        $tab    = $request->get('tab', 'laba-rugi');
        $bulan  = $request->get('bulan', 'all');
        $tahun  = (int) $request->get('tahun', now()->year);

        $data = match ($tab) {
            'penjualan'   => $this->dataPenjualan($bulan, $tahun),
            'pembelian'   => $this->dataPembelian($bulan, $tahun),
            'pengeluaran' => $this->dataPengeluaran($bulan, $tahun),
            default       => $this->dataLabaRugi($bulan, $tahun),
        };

        $bulanList = [
            1 => 'Januari', 2 => 'Februari', 3 => 'Maret',
            4 => 'April', 5 => 'Mei', 6 => 'Juni',
            7 => 'Juli', 8 => 'Agustus', 9 => 'September',
            10 => 'Oktober', 11 => 'November', 12 => 'Desember',
        ];

        $labelTab = [
            'laba-rugi'   => 'Laba-Rugi',
            'penjualan'   => 'Penjualan',
            'pembelian'   => 'Pembelian',
            'pengeluaran' => 'Pengeluaran',
        ][$tab] ?? 'Laporan';

        $labelPeriode = ($bulan === 'all')
            ? 'Semua Bulan ' . $tahun
            : ($bulanList[(int) $bulan] ?? '') . ' ' . $tahun;

        // Transform data ke array siap Excel
        $rows = $this->transformToExcelRows($tab, $data);

        $filename = 'Laporan-' . $labelTab . '-' . str_replace(' ', '-', $labelPeriode) . '.xlsx';

        return Excel::download(
            new LaporanKeuanganExport($rows, $tab, 'Laporan ' . str_replace('-', '/', $labelTab), $labelPeriode),
            $filename
        );
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
                        $row['pembelian'],
                        $row['laba_kotor'],
                        $row['pengeluaran'],
                        $row['laba_bersih'],
                    ];
                }
                // Baris total
                $rows[] = [
                    'TOTAL',
                    $data['total']['penjualan'],
                    $data['total']['pembelian'],
                    $data['total']['laba_kotor'],
                    $data['total']['pengeluaran'],
                    $data['total']['laba_bersih'],
                ];
                break;

            case 'penjualan':
                foreach ($data['rows'] as $trx) {
                    $rows[] = [
                        $trx->kode_transaksi,
                        $trx->tanggal->format('d/m/Y'),
                        $trx->nama_pelanggan ?? '-',
                        $trx->details->count(),
                        (float) $trx->total_diskon,
                        (float) $trx->grand_total,
                    ];
                }
                $rows[] = [
                    'TOTAL',
                    '',
                    '',
                    $data['total']['item'],
                    $data['total']['diskon'],
                    $data['total']['penjualan'],
                ];
                break;

            case 'pembelian':
                foreach ($data['rows'] as $pbl) {
                    $rows[] = [
                        $pbl->kode_pembelian,
                        $pbl->tanggal->format('d/m/Y'),
                        $pbl->supplier ?? '-',
                        $pbl->details->count(),
                        (float) $pbl->total_diskon,
                        (float) $pbl->grand_total,
                    ];
                }
                $rows[] = [
                    'TOTAL',
                    '',
                    '',
                    $data['total']['item'],
                    $data['total']['diskon'],
                    $data['total']['pembelian'],
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
        }

        return $rows;
    }
}