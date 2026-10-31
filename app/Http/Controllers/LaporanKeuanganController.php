<?php

namespace App\Http\Controllers;

use App\Models\Pembelian;
use App\Models\Pengeluaran;
use App\Models\Transaksi;
use App\Models\KasMasuk;
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
            'penjualan'     => $this->dataPenjualan($bulan, $tahun),
            'pembelian'     => $this->dataPembelian($bulan, $tahun),
            'pengeluaran'   => $this->dataPengeluaran($bulan, $tahun),
            'pribadi'       => $this->dataPribadi($bulan, $tahun),
            'rekonsiliasi'  => $this->dataRekonsiliasi($bulan, $tahun),
            'kasbon'        => $this->dataKasbon($bulan, $tahun),
            default         => $this->dataLabaRugi($bulan, $tahun),
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
            'penjualan'     => $this->dataPenjualan($bulan, $tahun),
            'pembelian'     => $this->dataPembelian($bulan, $tahun),
            'pengeluaran'   => $this->dataPengeluaran($bulan, $tahun),
            'pribadi'       => $this->dataPribadi($bulan, $tahun),
            'rekonsiliasi'  => $this->dataRekonsiliasi($bulan, $tahun),
            'kasbon'        => $this->dataKasbon($bulan, $tahun),
            default         => $this->dataLabaRugi($bulan, $tahun),
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
            'laba-rugi'     => 'Laba-Rugi',
            'penjualan'     => 'Penjualan',
            'pembelian'     => 'Pembelian',
            'pengeluaran'   => 'Pengeluaran',
            'pribadi'       => 'Keuangan-Pribadi',
            'rekonsiliasi'  => 'Rekonsiliasi-Owner',
            'kasbon'        => 'Kasbon-Karyawan',
        ][$tab] ?? 'Laporan';

        $filename = "Laporan-{$labelTab}-{$tahun}.pdf";

        return $pdf->download($filename);
    }

    // ============================================
    // DATA: LABA/RUGI (MURNI OPERASIONAL BISNIS)
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

        if ($bulan === 'all') {
            $rows = [];
            for ($i = 1; $i <= 12; $i++) {
                $penjualan = (float) Transaksi::whereMonth('tanggal', $i)
                    ->whereYear('tanggal', $tahun)->sum('grand_total');
                $pembelian = (float) Pembelian::whereMonth('tanggal', $i)
                    ->whereYear('tanggal', $tahun)->sum('grand_total');
                // PENGELUARAN BISNIS MURNI (tidak dicampur pengeluaran pribadi & kasbon)
                $pengeluaran = (float) Pengeluaran::bisnis()->whereMonth('tanggal', $i)
                    ->whereYear('tanggal', $tahun)->sum('jumlah');

                $labaKotor  = $penjualan - $pembelian;
                $labaBersih = $labaKotor - $pengeluaran;

                // Tambahan: Info Prive & Kasbon untuk pembanding
                $prive = (float) Pengeluaran::pribadi()->whereMonth('tanggal', $i)
                    ->whereYear('tanggal', $tahun)->sum('jumlah');

                $rows[] = [
                    'label'        => Carbon::create(null, $i, 1)->translatedFormat('F'),
                    'bulan'        => $i,
                    'penjualan'    => $penjualan,
                    'pembelian'    => $pembelian,
                    'laba_kotor'   => $labaKotor,
                    'pengeluaran'  => $pengeluaran,
                    'laba_bersih'  => $labaBersih,
                    'prive'        => $prive,
                ];
            }

            $totalPenjualan   = array_sum(array_column($rows, 'penjualan'));
            $totalPembelian   = array_sum(array_column($rows, 'pembelian'));
            $totalLabaKotor   = array_sum(array_column($rows, 'laba_kotor'));
            $totalPengeluaran = array_sum(array_column($rows, 'pengeluaran'));
            $totalLabaBersih  = array_sum(array_column($rows, 'laba_bersih'));
            $totalPrive       = array_sum(array_column($rows, 'prive'));
        } else {
            $penjualan   = (float) $query(Transaksi::class)->sum('grand_total');
            $pembelian   = (float) $query(Pembelian::class)->sum('grand_total');
            $pengeluaran = (float) $query(Pengeluaran::class)->where('tipe_alokasi', 'bisnis')->sum('jumlah');
            $prive       = (float) $query(Pengeluaran::class)->where('tipe_alokasi', 'pribadi')->sum('jumlah');

            $totalPenjualan   = $penjualan;
            $totalPembelian   = $pembelian;
            $totalLabaKotor   = $penjualan - $pembelian;
            $totalPengeluaran = $pengeluaran;
            $totalLabaBersih  = $totalLabaKotor - $pengeluaran;
            $totalPrive       = $prive;

            $rows = [
                [
                    'label'        => 'Ringkasan Bulan Ini',
                    'penjualan'    => $penjualan,
                    'pembelian'    => $pembelian,
                    'laba_kotor'   => $totalLabaKotor,
                    'pengeluaran'  => $pengeluaran,
                    'laba_bersih'  => $totalLabaBersih,
                    'prive'        => $prive,
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
                'prive'       => $totalPrive,
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

        $totalPenjualan = (float) $transaksis->sum('grand_total');
        $totalDiskon    = (float) $transaksis->sum('total_diskon');
        $totalItem      = (int) $transaksis->sum(fn($t) => $t->details->sum('jumlah'));
        $totalTransaksi = $transaksis->count();

        // Chart per bulan / per hari
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
            'rows'        => $transaksis,
            'total'       => [
                'penjualan' => $totalPenjualan,
                'diskon'    => $totalDiskon,
                'item'      => $totalItem,
                'transaksi' => $totalTransaksi,
            ],
            'chart'       => [
                'labels' => $labels,
                'values' => $values,
            ],
        ];
    }

    // ============================================
    // DATA: PEMBELIAN (HPP / BAHAN BAKU)
    // ============================================
    private function dataPembelian($bulan, $tahun): array
    {
        $query = Pembelian::with('details')->whereYear('tanggal', $tahun);

        if ($bulan !== 'all') {
            $query->whereMonth('tanggal', (int) $bulan);
        }

        $pembelians = $query->orderBy('tanggal', 'desc')->get();

        $totalPembelian = (float) $pembelians->sum('grand_total');
        $totalDiskon    = (float) $pembelians->sum('total_diskon');
        $totalItem      = (int) $pembelians->sum(fn($p) => $p->details->sum('jumlah'));
        $totalTransaksi = $pembelians->count();

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
            'rows'        => $pembelians,
            'total'       => [
                'pembelian' => $totalPembelian,
                'diskon'    => $totalDiskon,
                'item'      => $totalItem,
                'transaksi' => $totalTransaksi,
            ],
            'chart'       => [
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
        $totalBisnis      = (float) $pengeluarans->where('tipe_alokasi', 'bisnis')->sum('jumlah');
        $totalPribadi     = (float) $pengeluarans->where('tipe_alokasi', 'pribadi')->sum('jumlah');
        $totalKasbon      = (float) $pengeluarans->where('tipe_alokasi', 'kasbon')->sum('jumlah');
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
            'rows'        => $pengeluarans,
            'total'       => [
                'pengeluaran' => $totalPengeluaran,
                'bisnis'      => $totalBisnis,
                'pribadi'     => $totalPribadi,
                'kasbon'      => $totalKasbon,
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
    // DATA: KEUANGAN PRIBADI (PERSONAL FINANCE)
    // ============================================
    private function dataPribadi($bulan, $tahun): array
    {
        $query = Pengeluaran::pribadi()->whereYear('tanggal', $tahun);

        if ($bulan !== 'all') {
            $query->whereMonth('tanggal', (int) $bulan);
        }

        $pribadis = $query->orderBy('tanggal', 'desc')->get();

        $totalPribadi     = (float) $pribadis->sum('jumlah');
        $makanPribadi     = (float) $pribadis->where('kategori', 'makan_pribadi')->sum('jumlah');
        $kebutuhanRumah   = (float) $pribadis->where('kategori', 'kebutuhan_rumah')->sum('jumlah');
        $privePenarikan   = (float) $pribadis->where('kategori', 'prive_penarikan')->sum('jumlah');
        $pribadiLainnya   = (float) $pribadis->where('kategori', 'pribadi_lainnya')->sum('jumlah');
        $totalTransaksi   = $pribadis->count();

        $perKategori = $pribadis->groupBy('kategori')->map(function ($items, $kategori) {
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
            'rows'        => $pribadis,
            'total'       => [
                'pengeluaran'      => $totalPribadi,
                'makan_pribadi'    => $makanPribadi,
                'kebutuhan_rumah'  => $kebutuhanRumah,
                'prive_penarikan'  => $privePenarikan,
                'pribadi_lainnya'  => $pribadiLainnya,
                'transaksi'        => $totalTransaksi,
                'per_kategori'     => $perKategori,
            ],
            'chart'       => [
                'labels' => $labels,
                'values' => $values,
            ],
        ];
    }

    // ============================================
    // DATA: REKONSILIASI OWNER (MODAL VS PRIVE)
    // ============================================
    private function dataRekonsiliasi($bulan, $tahun): array
    {
        $queryModal = KasMasuk::where('tipe', 'modal_pemilik')->whereYear('tanggal', $tahun);
        $queryPrive = Pengeluaran::pribadi()->whereYear('tanggal', $tahun);
        $queryKasbon= Pengeluaran::kasbon()->whereYear('tanggal', $tahun);

        if ($bulan !== 'all') {
            $queryModal->whereMonth('tanggal', (int) $bulan);
            $queryPrive->whereMonth('tanggal', (int) $bulan);
            $queryKasbon->whereMonth('tanggal', (int) $bulan);
        }

        $totalModalDisetor = (float) $queryModal->sum('jumlah');
        $totalPriveDitarik = (float) $queryPrive->sum('jumlah');
        $totalKasbonAktif  = (float) $queryKasbon->where('status_kasbon', 'belum_lunas')->sum('jumlah');

        $saldoNetto = $totalModalDisetor - $totalPriveDitarik;

        // Breakdown bulanan
        $rows = [];
        for ($i = 1; $i <= 12; $i++) {
            $modal = (float) KasMasuk::where('tipe', 'modal_pemilik')
                ->whereMonth('tanggal', $i)->whereYear('tanggal', $tahun)->sum('jumlah');
            $prive = (float) Pengeluaran::pribadi()
                ->whereMonth('tanggal', $i)->whereYear('tanggal', $tahun)->sum('jumlah');
            $selisih = $modal - $prive;

            $rows[] = [
                'bulan_angka' => $i,
                'bulan_label' => Carbon::create(null, $i, 1)->translatedFormat('F'),
                'modal'       => $modal,
                'prive'       => $prive,
                'selisih'     => $selisih,
            ];
        }

        $listModal = KasMasuk::whereYear('tanggal', $tahun);
        if ($bulan !== 'all') {
            $listModal->whereMonth('tanggal', (int) $bulan);
        }
        $listModal = $listModal->latest('tanggal')->limit(20)->get();

        return [
            'rows'              => $rows,
            'list_modal'        => $listModal,
            'total'             => [
                'modal_disetor' => $totalModalDisetor,
                'prive_ditarik' => $totalPriveDitarik,
                'saldo_netto'   => $saldoNetto,
                'kasbon_aktif'  => $totalKasbonAktif,
            ],
            'chart'             => [
                'labels' => array_column($rows, 'bulan_label'),
                'modal'  => array_column($rows, 'modal'),
                'prive'  => array_column($rows, 'prive'),
            ],
        ];
    }

    // ============================================
    // DATA: BUKU KASBON KARYAWAN
    // ============================================
    private function dataKasbon($bulan, $tahun): array
    {
        $query = Pengeluaran::kasbon()->whereYear('tanggal', $tahun);

        if ($bulan !== 'all') {
            $query->whereMonth('tanggal', (int) $bulan);
        }

        $kasbons = $query->orderBy('tanggal', 'desc')->get();

        $totalKasbon     = (float) $kasbons->sum('jumlah');
        $totalLunas      = (float) $kasbons->where('status_kasbon', 'lunas')->sum('jumlah');
        $totalBelumLunas = (float) $kasbons->where('status_kasbon', '!=', 'lunas')->sum('jumlah');

        // Rekap per orang
        $perKaryawan = $kasbons->groupBy('karyawan_nama')->map(function ($items, $nama) {
            $namaClean = $nama ?: 'Tanpa Nama';
            $total     = (float) $items->sum('jumlah');
            $lunas     = (float) $items->where('status_kasbon', 'lunas')->sum('jumlah');
            $sisa      = $total - $lunas;

            return [
                'nama'  => $namaClean,
                'total' => $total,
                'lunas' => $lunas,
                'sisa'  => $sisa,
                'count' => $items->count(),
            ];
        })->sortByDesc('sisa')->values();

        return [
            'rows'         => $kasbons,
            'per_karyawan' => $perKaryawan,
            'total'        => [
                'total_kasbon'     => $totalKasbon,
                'total_lunas'      => $totalLunas,
                'total_belum_lunas'=> $totalBelumLunas,
                'transaksi'        => $kasbons->count(),
            ],
            'chart'        => [
                'labels' => $perKaryawan->pluck('nama')->toArray(),
                'values' => $perKaryawan->pluck('sisa')->toArray(),
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
        $tahunKasMasuk    = KasMasuk::select(DB::raw('YEAR(tanggal) as tahun'))->distinct()->pluck('tahun');

        $tahunList = collect()
            ->merge($tahunTransaksi)
            ->merge($tahunPembelian)
            ->merge($tahunPengeluaran)
            ->merge($tahunKasMasuk)
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
     * Export Excel.
     */
    public function exportExcel(Request $request)
    {
        $tab   = $request->get('tab', 'laba-rugi');
        $bulan = $request->get('bulan', 'all');
        $tahun = (int) $request->get('tahun', now()->year);

        $data = match ($tab) {
            'penjualan'    => $this->dataPenjualan($bulan, $tahun),
            'pembelian'    => $this->dataPembelian($bulan, $tahun),
            'pengeluaran'  => $this->dataPengeluaran($bulan, $tahun),
            'pribadi'      => $this->dataPribadi($bulan, $tahun),
            'rekonsiliasi' => $this->dataRekonsiliasi($bulan, $tahun),
            'kasbon'       => $this->dataKasbon($bulan, $tahun),
            default        => $this->dataLabaRugi($bulan, $tahun),
        };

        $rows = $this->transformToExcelRows($tab, $data);

        $bulanList = [
            1  => 'Januari', 2  => 'Februari', 3  => 'Maret',
            4  => 'April',   5  => 'Mei',      6  => 'Juni',
            7  => 'Juli',    8  => 'Agustus',  9  => 'September',
            10 => 'Oktober', 11 => 'November', 12 => 'Desember',
        ];

        $labelPeriode = $bulan === 'all'
            ? 'Semua Bulan ' . $tahun
            : ($bulanList[(int) $bulan] ?? 'Bulan ' . $bulan) . ' ' . $tahun;

        $labelTab = match ($tab) {
            'penjualan'    => 'Penjualan',
            'pembelian'    => 'Pembelian',
            'pengeluaran'  => 'Pengeluaran',
            'pribadi'      => 'Keuangan-Pribadi',
            'rekonsiliasi' => 'Rekonsiliasi-Owner',
            'kasbon'       => 'Kasbon-Karyawan',
            default        => 'Laba-Rugi',
        };

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

            case 'rekonsiliasi':
                foreach ($data['rows'] as $r) {
                    $rows[] = [
                        $r['bulan_label'],
                        (float) $r['modal'],
                        (float) $r['prive'],
                        (float) $r['selisih'],
                    ];
                }
                $rows[] = [
                    'TOTAL',
                    $data['total']['modal_disetor'],
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
                    'Sisa Belum Lunas: ' . $data['total']['total_belum_lunas'],
                ];
                break;
        }

        return $rows;
    }
}
