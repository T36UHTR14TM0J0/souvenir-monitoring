<?php

namespace App\Http\Controllers;

use App\Models\KasMasuk;
use App\Models\Kategori;
use App\Models\Pengeluaran;
use App\Models\Produk;
use App\Models\Transaksi;
use App\Models\TransaksiDetail;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $periode = (string) $request->get('periode', 'bulan');
        $forecastHorizon = max(1, min(6, (int) $request->get('horizon', 3)));

        // Tentukan anchor bulan & tahun secara cerdas:
        // Jika user memilih bulan/tahun di filter, gunakan pilihan tersebut.
        // Jika belum memilih dan bulan kalender saat ini belum memiliki transaksi (misal seeder s.d. Okt 2026),
        // otomatis gunakan bulan transaksi terakhir agar dashboard langsung menampilkan data aktual yang kaya.
        $latestTrxDate = Transaksi::max('tanggal');
        $defaultAnchor = $latestTrxDate ? Carbon::parse($latestTrxDate) : now();

        if (! $request->filled('bulan') && ! $request->filled('tahun')) {
            $hasCurrentMonthData = Transaksi::whereYear('tanggal', now()->year)
                ->whereMonth('tanggal', now()->month)
                ->exists();
            $anchorDate = $hasCurrentMonthData ? now() : $defaultAnchor;
        } else {
            $reqTahun = (int) $request->get('tahun', $defaultAnchor->year);
            $reqBulan = (int) $request->get('bulan', $defaultAnchor->month);
            $anchorDate = Carbon::createFromDate($reqTahun, max(1, min(12, $reqBulan)), 1);
        }

        $bulanIni = (int) $anchorDate->month;
        $tahunIni = (int) $anchorDate->year;
        $namaBulanIni = $anchorDate->translatedFormat('F Y');

        // ============================================
        // 1. KPI PERIODE BULAN AKTIF (MENCAKUP SEMUA MODUL)
        // ============================================
        $validPenjualanQuery = Transaksi::whereMonth('tanggal', $bulanIni)
            ->whereYear('tanggal', $tahunIni)
            ->whereNotIn('status_pesanan', ['batal', 'retur']);

        $totalPenjualan = (float) (clone $validPenjualanQuery)->sum('grand_total');
        $totalBiayaAdmin = (float) (clone $validPenjualanQuery)->sum('biaya_admin_marketplace');
        $totalBersih = (float) (clone $validPenjualanQuery)->sum('penghasilan_bersih');
        $jumlahTransaksi = (int) (clone $validPenjualanQuery)->count();
        $jumlahBatalRetur = (int) Transaksi::whereMonth('tanggal', $bulanIni)
            ->whereYear('tanggal', $tahunIni)
            ->whereIn('status_pesanan', ['batal', 'retur'])
            ->count();

        $pendapatanEfektif = $totalBersih > 0 ? $totalBersih : max(0, $totalPenjualan - $totalBiayaAdmin);

        // Modal Belanja Bahan Baku (dari Pengeluaran Modal Bahan)
        $totalPembelian = (float) Pengeluaran::modalBahan()
            ->whereMonth('tanggal', $bulanIni)
            ->whereYear('tanggal', $tahunIni)
            ->sum('jumlah');
        $totalOngkirPembelian = 0;
        $jumlahPembelian = (int) Pengeluaran::modalBahan()
            ->whereMonth('tanggal', $bulanIni)
            ->whereYear('tanggal', $tahunIni)
            ->count();

        // Kas Masuk (Pendapatan Lain & Modal Pemilik)
        $totalPendapatanLain = (float) KasMasuk::whereMonth('tanggal', $bulanIni)
            ->whereYear('tanggal', $tahunIni)
            ->where('tipe', 'pendapatan_lain')
            ->sum('jumlah');

        $totalModalDisetor = (float) KasMasuk::whereMonth('tanggal', $bulanIni)
            ->whereYear('tanggal', $tahunIni)
            ->where('tipe', 'modal_pemilik')
            ->sum('jumlah');

        // Pengeluaran dipisahkan sesuai akuntansi: Bisnis (Operasional & Ads), Pribadi (Prive), Kasbon Tim
        $pengeluaranBisnisQuery = Pengeluaran::whereMonth('tanggal', $bulanIni)
            ->whereYear('tanggal', $tahunIni)
            ->where(function ($q) {
                $q->where('tipe_alokasi', 'bisnis')->orWhereNull('tipe_alokasi');
            });

        $totalPengeluaran = (float) (clone $pengeluaranBisnisQuery)->sum('jumlah');
        $totalBiayaIklan = (float) (clone $pengeluaranBisnisQuery)->where('kategori', 'iklan')->sum('jumlah');

        $totalPriveBulanIni = (float) Pengeluaran::whereMonth('tanggal', $bulanIni)
            ->whereYear('tanggal', $tahunIni)
            ->where('tipe_alokasi', 'pribadi')
            ->sum('jumlah');

        $totalKasbonBulanIni = (float) Pengeluaran::whereMonth('tanggal', $bulanIni)
            ->whereYear('tanggal', $tahunIni)
            ->where('tipe_alokasi', 'kasbon')
            ->sum('jumlah');

        $totalKasbonAktif = (float) Pengeluaran::where('tipe_alokasi', 'kasbon')
            ->where('status_kasbon', 'belum_lunas')
            ->sum('jumlah');

        // Laba Kotor & Laba Bersih Bisnis
        $totalPengeluaranOperasional = (float) (clone $pengeluaranBisnisQuery)
            ->whereNotIn('kategori', ['bahan_baku', 'perlengkapan', 'jasa_produksi'])
            ->sum('jumlah');

        $labaKotor = ($pendapatanEfektif + $totalPendapatanLain) - $totalPembelian;
        $labaBersih = $labaKotor - $totalPengeluaranOperasional;
        $marginBersih = $totalPenjualan > 0 ? round(($labaBersih / $totalPenjualan) * 100, 1) : 0.0;

        // Performa 3 Channel Marketplace Bulan Ini
        $channelStats = [];
        foreach (['shopee' => 'Shopee', 'tiktok' => 'TikTok Shop', 'lazada' => 'Lazada'] as $chKey => $chLabel) {
            $chQuery = (clone $validPenjualanQuery)->where('channel', $chKey);
            $chOmzet = (float) (clone $chQuery)->sum('grand_total');
            $chAdmin = (float) (clone $chQuery)->sum('biaya_admin_marketplace');
            $chBersihRaw = (float) (clone $chQuery)->sum('penghasilan_bersih');
            $chBersih = $chBersihRaw > 0 ? $chBersihRaw : max(0, $chOmzet - $chAdmin);
            $chCount = (int) (clone $chQuery)->count();

            $channelStats[$chKey] = [
                'label' => $chLabel,
                'omzet' => $chOmzet,
                'biaya_admin' => $chAdmin,
                'bersih' => $chBersih,
                'transaksi' => $chCount,
                'share' => $totalPenjualan > 0 ? round(($chOmzet / $totalPenjualan) * 100, 1) : 0.0,
            ];
        }

        $omzetShopee = $channelStats['shopee']['omzet'];
        $omzetLazada = $channelStats['lazada']['omzet'];
        $omzetTiktok = $channelStats['tiktok']['omzet'];

        // Pipeline Status Pesanan Marketplace (Bulan Ini)
        $statusPipeline = [
            'perlu_diproses' => (int) Transaksi::whereMonth('tanggal', $bulanIni)->whereYear('tanggal', $tahunIni)->where('status_pesanan', 'perlu_diproses')->count(),
            'siap_packing' => (int) Transaksi::whereMonth('tanggal', $bulanIni)->whereYear('tanggal', $tahunIni)->where('status_pesanan', 'siap_packing')->count(),
            'dikirim' => (int) Transaksi::whereMonth('tanggal', $bulanIni)->whereYear('tanggal', $tahunIni)->where('status_pesanan', 'dikirim')->count(),
            'selesai' => (int) Transaksi::whereMonth('tanggal', $bulanIni)->whereYear('tanggal', $tahunIni)->where('status_pesanan', 'selesai')->count(),
            'batal_retur' => $jumlahBatalRetur,
        ];

        // ============================================
        // 2. KPI HARI INI & PERBANDINGAN BULAN LALU (MoM)
        // ============================================
        $penjualanHariIni = (float) Transaksi::whereDate('tanggal', today())
            ->whereNotIn('status_pesanan', ['batal', 'retur'])
            ->sum('grand_total');
        $transaksiHariIni = (int) Transaksi::whereDate('tanggal', today())
            ->whereNotIn('status_pesanan', ['batal', 'retur'])
            ->count();
        $pembelianHariIni = (float) Pengeluaran::modalBahan()->whereDate('tanggal', today())->sum('jumlah');
        $pengeluaranHariIni = (float) Pengeluaran::operasionalMurni()->whereDate('tanggal', today())->sum('jumlah');

        $bulanLalu = $anchorDate->copy()->subMonth();
        $validBulanLaluQuery = Transaksi::whereMonth('tanggal', $bulanLalu->month)
            ->whereYear('tanggal', $bulanLalu->year)
            ->whereNotIn('status_pesanan', ['batal', 'retur']);

        $penjualanBulanLalu = (float) (clone $validBulanLaluQuery)->sum('grand_total');
        $biayaAdminBulanLalu = (float) (clone $validBulanLaluQuery)->sum('biaya_admin_marketplace');
        $bersihBulanLaluRaw = (float) (clone $validBulanLaluQuery)->sum('penghasilan_bersih');
        $bersihBulanLalu = $bersihBulanLaluRaw > 0 ? $bersihBulanLaluRaw : max(0, $penjualanBulanLalu - $biayaAdminBulanLalu);

        $pendapatanLainBulanLalu = (float) KasMasuk::whereMonth('tanggal', $bulanLalu->month)
            ->whereYear('tanggal', $bulanLalu->year)
            ->where('tipe', 'pendapatan_lain')
            ->sum('jumlah');

        $pembelianBulanLalu = (float) Pengeluaran::modalBahan()
            ->whereMonth('tanggal', $bulanLalu->month)
            ->whereYear('tanggal', $bulanLalu->year)
            ->sum('jumlah');

        $pengeluaranBulanLalu = (float) Pengeluaran::operasionalMurni()
            ->whereMonth('tanggal', $bulanLalu->month)
            ->whereYear('tanggal', $bulanLalu->year)
            ->sum('jumlah');

        $labaBulanLalu = ($bersihBulanLalu + $pendapatanLainBulanLalu) - $pembelianBulanLalu - $pengeluaranBulanLalu;
        $transaksiBulanLalu = (int) (clone $validBulanLaluQuery)->count();

        $persenPenjualan = $penjualanBulanLalu > 0
            ? (($totalPenjualan - $penjualanBulanLalu) / $penjualanBulanLalu) * 100
            : 0.0;

        $persenLaba = $labaBulanLalu != 0
            ? (($labaBersih - $labaBulanLalu) / abs($labaBulanLalu)) * 100
            : 0.0;

        $persenTransaksi = $transaksiBulanLalu > 0
            ? (($jumlahTransaksi - $transaksiBulanLalu) / $transaksiBulanLalu) * 100
            : 0.0;

        // ============================================
        // 3. DATA HISTORIS 12 BULAN + ENGINE FORECASTING
        // ============================================
        $historyMonths = 12;
        $monthlyHistory = [];
        $labels = [];
        $dataPenjualan = [];
        $dataPenghasilanBersih = [];
        $dataPembelian = [];
        $dataPengeluaran = [];
        $dataLabaBersih = [];
        $dataShopee = [];
        $dataTiktok = [];
        $dataLazada = [];

        for ($i = $historyMonths - 1; $i >= 0; $i--) {
            $mDate = $anchorDate->copy()->subMonths($i);
            $m = (int) $mDate->month;
            $y = (int) $mDate->year;
            $lbl = $mDate->translatedFormat('M Y');

            $qTrx = Transaksi::whereMonth('tanggal', $m)
                ->whereYear('tanggal', $y)
                ->whereNotIn('status_pesanan', ['batal', 'retur']);

            $omzetM = (float) (clone $qTrx)->sum('grand_total');
            $adminM = (float) (clone $qTrx)->sum('biaya_admin_marketplace');
            $bersihRawM = (float) (clone $qTrx)->sum('penghasilan_bersih');
            $bersihM = $bersihRawM > 0 ? $bersihRawM : max(0, $omzetM - $adminM);

            $pendLainM = (float) KasMasuk::whereMonth('tanggal', $m)
                ->whereYear('tanggal', $y)
                ->where('tipe', 'pendapatan_lain')
                ->sum('jumlah');

            $pembelianM = (float) Pengeluaran::modalBahan()
                ->whereMonth('tanggal', $m)
                ->whereYear('tanggal', $y)
                ->sum('jumlah');

            $pengeluaranM = (float) Pengeluaran::operasionalMurni()
                ->whereMonth('tanggal', $m)
                ->whereYear('tanggal', $y)
                ->sum('jumlah');

            $labaM = ($bersihM + $pendLainM) - $pembelianM - $pengeluaranM;

            $shopeeM = (float) (clone $qTrx)->where('channel', 'shopee')->sum('grand_total');
            $tiktokM = (float) (clone $qTrx)->where('channel', 'tiktok')->sum('grand_total');
            $lazadaM = (float) (clone $qTrx)->where('channel', 'lazada')->sum('grand_total');

            $labels[] = $lbl;
            $dataPenjualan[] = $omzetM;
            $dataPenghasilanBersih[] = $bersihM;
            $dataPembelian[] = $pembelianM;
            $dataPengeluaran[] = $pengeluaranM;
            $dataLabaBersih[] = $labaM;
            $dataShopee[] = $shopeeM;
            $dataTiktok[] = $tiktokM;
            $dataLazada[] = $lazadaM;

            $monthlyHistory[] = [
                'label' => $lbl,
                'omzet' => $omzetM,
                'bersih' => $bersihM,
                'pembelian' => $pembelianM,
                'pengeluaran' => $pengeluaranM,
                'laba' => $labaM,
            ];
        }

        // ============================================
        // 4. ENGINE FORECASTING (HOLT'S DOUBLE EXPONENTIAL SMOOTHING + LINEAR TREND)
        // ============================================
        $forecastLabels = [];
        for ($h = 1; $h <= $forecastHorizon; $h++) {
            $fDate = $anchorDate->copy()->addMonths($h);
            $forecastLabels[] = $fDate->translatedFormat('M Y').' (Prediksi)';
        }

        $fcPenjualan = $this->runHoltForecast($dataPenjualan, $forecastHorizon, 0.45, 0.25);
        $fcBersih = $this->runHoltForecast($dataPenghasilanBersih, $forecastHorizon, 0.45, 0.25);
        $fcPembelian = $this->runHoltForecast($dataPembelian, $forecastHorizon, 0.40, 0.20);
        $fcPengeluaran = $this->runHoltForecast($dataPengeluaran, $forecastHorizon, 0.40, 0.20);

        // Hitung prediksi Laba Bersih secara konsisten dari komponen prediksi
        $fcLabaValues = [];
        for ($h = 0; $h < $forecastHorizon; $h++) {
            $fcLabaValues[] = round(($fcBersih['predictions'][$h] ?? 0) - ($fcPembelian['predictions'][$h] ?? 0) - ($fcPengeluaran['predictions'][$h] ?? 0));
        }

        // Forecasting per Channel Marketplace (Bulan Depan)
        $fcShopee = $this->runHoltForecast($dataShopee, $forecastHorizon, 0.45, 0.25);
        $fcTiktok = $this->runHoltForecast($dataTiktok, $forecastHorizon, 0.45, 0.25);
        $fcLazada = $this->runHoltForecast($dataLazada, $forecastHorizon, 0.45, 0.25);

        $channelStats['shopee']['forecast_next'] = $fcShopee['predictions'][0] ?? 0;
        $channelStats['tiktok']['forecast_next'] = $fcTiktok['predictions'][0] ?? 0;
        $channelStats['lazada']['forecast_next'] = $fcLazada['predictions'][0] ?? 0;

        // Gabungkan label & dataset untuk Chart Utama (Historis + Forecasting menyambung di titik terakhir)
        $combinedChartLabels = array_merge($labels, $forecastLabels);
        $histCount = count($labels);
        $nullPaddingHist = array_fill(0, max(0, $histCount - 1), null);

        $chartForecastData = [
            'labels' => $combinedChartLabels,
            'hist_count' => $histCount,
            'forecast_horizon' => $forecastHorizon,
            'actual_omzet' => array_merge($dataPenjualan, array_fill(0, $forecastHorizon, null)),
            'actual_bersih' => array_merge($dataPenghasilanBersih, array_fill(0, $forecastHorizon, null)),
            'actual_pembelian' => array_merge($dataPembelian, array_fill(0, $forecastHorizon, null)),
            'actual_pengeluaran' => array_merge($dataPengeluaran, array_fill(0, $forecastHorizon, null)),
            'actual_laba' => array_merge($dataLabaBersih, array_fill(0, $forecastHorizon, null)),
            // Garis prediksi dimulai dari titik aktual terakhir agar garis grafik tidak terputus
            'pred_omzet' => array_merge($nullPaddingHist, [end($dataPenjualan)], $fcPenjualan['predictions']),
            'pred_omzet_upper' => array_merge($nullPaddingHist, [end($dataPenjualan)], $fcPenjualan['upper_bounds']),
            'pred_omzet_lower' => array_merge($nullPaddingHist, [end($dataPenjualan)], $fcPenjualan['lower_bounds']),
            'pred_bersih' => array_merge($nullPaddingHist, [end($dataPenghasilanBersih)], $fcBersih['predictions']),
            'pred_pembelian' => array_merge($nullPaddingHist, [end($dataPembelian)], $fcPembelian['predictions']),
            'pred_pengeluaran' => array_merge($nullPaddingHist, [end($dataPengeluaran)], $fcPengeluaran['predictions']),
            'pred_laba' => array_merge($nullPaddingHist, [end($dataLabaBersih)], $fcLabaValues),
        ];

        // Tabel Ringkasan Proyeksi Bulan ke Depan
        $forecastTable = [];
        for ($h = 0; $h < $forecastHorizon; $h++) {
            $fMonthLabel = $anchorDate->copy()->addMonths($h + 1)->translatedFormat('F Y');
            $pOmzet = (float) ($fcPenjualan['predictions'][$h] ?? 0);
            $pBersih = (float) ($fcBersih['predictions'][$h] ?? 0);
            $pBeli = (float) ($fcPembelian['predictions'][$h] ?? 0);
            $pBiaya = (float) ($fcPengeluaran['predictions'][$h] ?? 0);
            $pLaba = (float) ($fcLabaValues[$h] ?? 0);
            $pMargin = $pOmzet > 0 ? round(($pLaba / $pOmzet) * 100, 1) : 0.0;

            $forecastTable[] = [
                'periode' => $fMonthLabel,
                'omzet' => $pOmzet,
                'omzet_min' => (float) ($fcPenjualan['lower_bounds'][$h] ?? 0),
                'omzet_max' => (float) ($fcPenjualan['upper_bounds'][$h] ?? 0),
                'bersih' => $pBersih,
                'pembelian' => $pBeli,
                'pengeluaran' => $pBiaya,
                'laba' => $pLaba,
                'margin' => $pMargin,
            ];
        }

        $forecastSummary = [
            'method' => 'Holt’s Double Exponential Smoothing (α=0.45, β=0.25)',
            'accuracy' => $fcPenjualan['accuracy'],
            'mape' => $fcPenjualan['mape'],
            'growth_rate' => $fcPenjualan['growth_rate'],
            'trend_direction' => $fcPenjualan['trend_direction'],
            'next_month_label' => $anchorDate->copy()->addMonth()->translatedFormat('F Y'),
            'next_omzet' => $fcPenjualan['predictions'][0] ?? 0,
            'next_bersih' => $fcBersih['predictions'][0] ?? 0,
            'next_pembelian' => $fcPembelian['predictions'][0] ?? 0,
            'next_pengeluaran' => $fcPengeluaran['predictions'][0] ?? 0,
            'next_laba' => $fcLabaValues[0] ?? 0,
        ];

        // ============================================
        // 5. DEMAND FORECASTING PRODUK & BAHAN BAKU (PREDIKSI KEBUTUHAN STOK)
        // ============================================
        $threeMonthsAgo = $anchorDate->copy()->subMonths(2)->startOfMonth();
        $endOfAnchor = $anchorDate->copy()->endOfMonth();

        // Top 5 Produk Terlaris Bulan Ini + Prediksi Permintaan Bulan Depan
        $topProduk = TransaksiDetail::select(
            'produk_id',
            'nama_produk',
            DB::raw('SUM(qty) as total_qty'),
            DB::raw('SUM(subtotal) as total_omzet')
        )
            ->whereHas('transaksi', function ($q) use ($bulanIni, $tahunIni) {
                $q->whereMonth('tanggal', $bulanIni)
                    ->whereYear('tanggal', $tahunIni)
                    ->whereNotIn('status_pesanan', ['batal', 'retur']);
            })
            ->groupBy('produk_id', 'nama_produk')
            ->orderByDesc('total_qty')
            ->limit(5)
            ->get()
            ->map(function ($item) use ($threeMonthsAgo, $endOfAnchor) {
                $avg3M = (float) TransaksiDetail::where('produk_id', $item->produk_id)
                    ->whereHas('transaksi', function ($q) use ($threeMonthsAgo, $endOfAnchor) {
                        $q->whereBetween('tanggal', [$threeMonthsAgo->toDateString(), $endOfAnchor->toDateString()])
                            ->whereNotIn('status_pesanan', ['batal', 'retur']);
                    })
                    ->sum('qty') / 3;

                $produkModel = Produk::with('satuan')->find($item->produk_id);
                $stokSaatIni = (int) ($produkModel->stok ?? 0);
                // Weighted forecast: 60% bulan berjalan + 40% rata-rata 3 bulan terakhir + faktor tren 5%
                $prediksiQty = (int) ceil((($item->total_qty * 0.6) + ($avg3M * 0.4)) * 1.05);
                $rekomendasiRestock = max(0, $prediksiQty - $stokSaatIni);

                $item->stok_saat_ini = $stokSaatIni;
                $item->satuan = $produkModel->satuan->singkatan ?? 'pcs';
                $item->forecast_qty = max(1, $prediksiQty);
                $item->rekomendasi_restock = $rekomendasiRestock;
                $item->rekomendasi_produksi = $rekomendasiRestock; // backward compatibility

                return $item;
            });

        // ============================================
        // 6. DISTRIBUSI KATEGORI, BIAYA OPERASIONAL & STOK MENIPIS
        // ============================================
        $kategoriData = Kategori::has('produks')->withCount('produks')->get();
        if ($kategoriData->isEmpty()) {
            $kategoriData = Kategori::withCount('produks')->get();
        }
        $kategoriLabels = $kategoriData->pluck('nama_kategori')->toArray();
        $kategoriCounts = $kategoriData->pluck('produks_count')->toArray();

        $pengeluaranPerKategori = Pengeluaran::select(
            'kategori',
            DB::raw('SUM(jumlah) as total_jumlah'),
            DB::raw('COUNT(*) as total_transaksi')
        )
            ->whereMonth('tanggal', $bulanIni)
            ->whereYear('tanggal', $tahunIni)
            ->where(function ($q) {
                $q->where('tipe_alokasi', 'bisnis')->orWhereNull('tipe_alokasi');
            })
            ->groupBy('kategori')
            ->orderByDesc('total_jumlah')
            ->get()
            ->map(function ($item) {
                $item->kategori_label = Pengeluaran::KATEGORI[$item->kategori] ?? ucfirst($item->kategori);

                return $item;
            });

        $produkStokMenipis = Produk::with(['kategori', 'satuan'])
            ->whereColumn('stok', '<=', 'stok_minimal')
            ->orderBy('stok', 'asc')
            ->limit(8)
            ->get();

        // ============================================
        // 7. AKTIVITAS TERBARU LINTAS MODUL
        // ============================================
        $transaksiTerbaru = Transaksi::with('details')
            ->latest('tanggal')
            ->latest('id')
            ->limit(5)
            ->get();

        $pembelianTerbaru = Pengeluaran::modalBahan()
            ->latest('tanggal')
            ->latest('id')
            ->limit(5)
            ->get();

        $pengeluaranTerbaru = Pengeluaran::latest('tanggal')
            ->latest('id')
            ->limit(5)
            ->get();

        // Statistik Master Katalog
        $totalProduk = Produk::count();
        $totalSouvenirJadi = $totalProduk;
        $totalBahanBaku = 0;
        $totalKategori = Kategori::count();

        // Pilihan Filter Tahun & Bulan untuk Modal Filter Dashboard
        $tahunList = collect([now()->year, now()->year - 1, 2026, 2025])->unique()->sortDesc()->values();
        $bulanList = [
            1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
            5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
            9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember',
        ];

        return view('dashboard.index', compact(
            'periode',
            'forecastHorizon',
            'bulanIni',
            'tahunIni',
            'namaBulanIni',
            'tahunList',
            'bulanList',
            // KPI Utama & Semua Modul
            'totalPenjualan', 'totalBiayaAdmin', 'totalBersih', 'pendapatanEfektif',
            'totalPembelian', 'totalOngkirPembelian', 'jumlahPembelian',
            'totalPengeluaran', 'totalBiayaIklan',
            'totalPendapatanLain', 'totalModalDisetor',
            'totalPriveBulanIni', 'totalKasbonBulanIni', 'totalKasbonAktif',
            'omzetShopee', 'omzetLazada', 'omzetTiktok', 'channelStats',
            'labaKotor', 'labaBersih', 'marginBersih',
            'jumlahTransaksi', 'jumlahBatalRetur', 'statusPipeline',
            // Hari Ini & MoM
            'penjualanHariIni', 'transaksiHariIni',
            'pembelianHariIni', 'pengeluaranHariIni',
            'penjualanBulanLalu', 'labaBulanLalu', 'transaksiBulanLalu',
            'persenPenjualan', 'persenLaba', 'persenTransaksi',
            // Grafik Komprehensif & Forecasting
            'labels', 'dataPenjualan', 'dataPenghasilanBersih', 'dataPembelian', 'dataPengeluaran', 'dataLabaBersih',
            'chartForecastData', 'forecastTable', 'forecastSummary',
            'kategoriLabels', 'kategoriCounts', 'kategoriData',
            // Tabel & Ringkasan
            'topProduk', 'produkStokMenipis',
            'transaksiTerbaru', 'pembelianTerbaru', 'pengeluaranTerbaru',
            'pengeluaranPerKategori',
            'totalProduk', 'totalSouvenirJadi', 'totalBahanBaku', 'totalKategori'
        ));
    }

    /**
     * Engine Time-Series Forecasting menggunakan Holt's Linear Exponential Smoothing (Double Exponential Smoothing).
     *
     * @param  array<int, float|int>  $series
     * @return array{
     *     predictions: array<int, float>,
     *     upper_bounds: array<int, float>,
     *     lower_bounds: array<int, float>,
     *     mape: float,
     *     accuracy: float,
     *     growth_rate: float,
     *     trend_direction: string
     * }
     */
    private function runHoltForecast(array $series, int $horizon = 3, float $alpha = 0.45, float $beta = 0.25): array
    {
        $clean = array_values(array_map(fn ($v) => (float) $v, $series));
        $n = count($clean);

        if ($n === 0) {
            return [
                'predictions' => array_fill(0, $horizon, 0.0),
                'upper_bounds' => array_fill(0, $horizon, 0.0),
                'lower_bounds' => array_fill(0, $horizon, 0.0),
                'mape' => 0.0,
                'accuracy' => 100.0,
                'growth_rate' => 0.0,
                'trend_direction' => 'stabil',
            ];
        }

        if ($n === 1) {
            $val = round(max(0.0, $clean[0]));

            return [
                'predictions' => array_fill(0, $horizon, $val),
                'upper_bounds' => array_fill(0, $horizon, round($val * 1.1)),
                'lower_bounds' => array_fill(0, $horizon, round($val * 0.9)),
                'mape' => 0.0,
                'accuracy' => 95.0,
                'growth_rate' => 0.0,
                'trend_direction' => 'stabil',
            ];
        }

        // Inisialisasi Level (L0) dan Trend (T0) menggunakan rata-rata selisih awal
        $level = $clean[0];
        $trend = ($clean[min(3, $n - 1)] - $clean[0]) / max(1, min(3, $n - 1));

        $absPercentErrors = [];
        $squaredErrors = [];

        for ($t = 1; $t < $n; $t++) {
            $actual = $clean[$t];
            $oneStepForecast = $level + $trend;

            $prevLevel = $level;
            $level = ($alpha * $actual) + ((1 - $alpha) * ($prevLevel + $trend));
            $trend = ($beta * ($level - $prevLevel)) + ((1 - $beta) * $trend);

            $error = $actual - $oneStepForecast;
            $squaredErrors[] = $error * $error;

            if ($actual > 0) {
                $absPercentErrors[] = min(1.0, abs($error) / $actual);
            }
        }

        $rmse = count($squaredErrors) > 0 ? sqrt(array_sum($squaredErrors) / count($squaredErrors)) : ($level * 0.08);
        $mape = count($absPercentErrors) > 0 ? round((array_sum($absPercentErrors) / count($absPercentErrors)) * 100, 1) : 4.5;
        $accuracy = round(max(50.0, min(99.5, 100.0 - $mape)), 1);

        // Redam kemiringan tren ekstrem menggunakan damping factor (phi = 0.90) agar proyeksi realistis
        $phi = 0.90;
        $predictions = [];
        $upperBounds = [];
        $lowerBounds = [];

        $dampedSum = 0.0;
        for ($h = 1; $h <= $horizon; $h++) {
            $dampedSum += pow($phi, $h);
            $rawPred = max(0.0, $level + ($dampedSum * $trend));
            $pred = round($rawPred);

            // Interval Kepercayaan 90% (±1.645 * RMSE * sqrt(h)) dengan batas wajar
            $margin = min($pred * 0.25, 1.645 * $rmse * sqrt($h));
            $predictions[] = $pred;
            $upperBounds[] = round($pred + $margin);
            $lowerBounds[] = round(max(0.0, $pred - $margin));
        }

        $lastActual = end($clean) ?: 1.0;
        $firstPred = $predictions[0] ?? $lastActual;
        $growthRate = $lastActual > 0 ? round((($firstPred - $lastActual) / $lastActual) * 100, 1) : 0.0;
        $trendDirection = $growthRate > 1.0 ? 'naik' : ($growthRate < -1.0 ? 'turun' : 'stabil');

        return [
            'predictions' => $predictions,
            'upper_bounds' => $upperBounds,
            'lower_bounds' => $lowerBounds,
            'mape' => $mape,
            'accuracy' => $accuracy,
            'growth_rate' => $growthRate,
            'trend_direction' => $trendDirection,
        ];
    }
}
