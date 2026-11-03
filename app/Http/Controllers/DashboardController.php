<?php

namespace App\Http\Controllers;

use App\Models\Kategori;
use App\Models\Pembelian;
use App\Models\Pengeluaran;
use App\Models\Produk;
use App\Models\Transaksi;
use App\Models\TransaksiDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $periode = $request->get('periode', '30');

        // ============================================
        // KPI BULAN INI
        // ============================================
        $bulanIni = now()->month;
        $tahunIni = now()->year;

        $totalPenjualan = (float) Transaksi::whereMonth('tanggal', $bulanIni)
            ->whereYear('tanggal', $tahunIni)
            ->sum('grand_total');

        $totalPembelian = (float) Pembelian::whereMonth('tanggal', $bulanIni)
            ->whereYear('tanggal', $tahunIni)
            ->sum('grand_total');

        $totalPengeluaran = (float) Pengeluaran::whereMonth('tanggal', $bulanIni)
            ->whereYear('tanggal', $tahunIni)
            ->sum('jumlah');

        $labaKotor  = $totalPenjualan - $totalPembelian;
        $labaBersih = $totalPenjualan - $totalPembelian - $totalPengeluaran;

        $jumlahTransaksi = (int) Transaksi::whereMonth('tanggal', $bulanIni)
            ->whereYear('tanggal', $tahunIni)
            ->count();

        // ============================================
        // KPI HARI INI
        // ============================================
        $penjualanHariIni   = (float) Transaksi::whereDate('tanggal', today())->sum('grand_total');
        $transaksiHariIni   = (int)   Transaksi::whereDate('tanggal', today())->count();
        $pembelianHariIni   = (float) Pembelian::whereDate('tanggal', today())->sum('grand_total');
        $pengeluaranHariIni = (float) Pengeluaran::whereDate('tanggal', today())->sum('jumlah');

        // ============================================
        // BULAN LALU
        // ============================================
        $bulanLalu = now()->subMonth();

        $penjualanBulanLalu = (float) Transaksi::whereMonth('tanggal', $bulanLalu->month)
            ->whereYear('tanggal', $bulanLalu->year)
            ->sum('grand_total');

        $pembelianBulanLalu = (float) Pembelian::whereMonth('tanggal', $bulanLalu->month)
            ->whereYear('tanggal', $bulanLalu->year)
            ->sum('grand_total');

        $pengeluaranBulanLalu = (float) Pengeluaran::whereMonth('tanggal', $bulanLalu->month)
            ->whereYear('tanggal', $bulanLalu->year)
            ->sum('jumlah');

        $labaBulanLalu = $penjualanBulanLalu - $pembelianBulanLalu - $pengeluaranBulanLalu;

        $transaksiBulanLalu = (int) Transaksi::whereMonth('tanggal', $bulanLalu->month)
            ->whereYear('tanggal', $bulanLalu->year)
            ->count();

        // Hitung persentase
        $persenPenjualan = $penjualanBulanLalu > 0
            ? (($totalPenjualan - $penjualanBulanLalu) / $penjualanBulanLalu) * 100
            : 0;

        $persenLaba = $labaBulanLalu != 0
            ? (($labaBersih - $labaBulanLalu) / abs($labaBulanLalu)) * 100
            : 0;

        $persenTransaksi = $transaksiBulanLalu > 0
            ? (($jumlahTransaksi - $transaksiBulanLalu) / $transaksiBulanLalu) * 100
            : 0;

        // ============================================
        // GRAFIK TREN
        // ============================================
        if ($periode === 'bulan') {
            $labels = [];
            $dataPenjualan = [];
            $dataPembelian = [];

            for ($i = 11; $i >= 0; $i--) {
                $bulan = now()->subMonths($i);
                $labels[] = $bulan->translatedFormat('M Y');

                $dataPenjualan[] = (float) Transaksi::whereMonth('tanggal', $bulan->month)
                    ->whereYear('tanggal', $bulan->year)
                    ->sum('grand_total');

                $dataPembelian[] = (float) Pembelian::whereMonth('tanggal', $bulan->month)
                    ->whereYear('tanggal', $bulan->year)
                    ->sum('grand_total');
            }
        } else {
            $jumlahHari = (int) $periode;
            $labels = [];
            $dataPenjualan = [];
            $dataPembelian = [];

            for ($i = $jumlahHari - 1; $i >= 0; $i--) {
                $tanggal = now()->subDays($i);
                $labels[] = $tanggal->translatedFormat('d M');

                $dataPenjualan[] = (float) Transaksi::whereDate('tanggal', $tanggal->format('Y-m-d'))
                    ->sum('grand_total');

                $dataPembelian[] = (float) Pembelian::whereDate('tanggal', $tanggal->format('Y-m-d'))
                    ->sum('grand_total');
            }
        }

        // ============================================
        // KATEGORI
        // ============================================
        $kategoriData = Kategori::withCount('produks')->get();
        $kategoriLabels = $kategoriData->pluck('nama_kategori')->toArray();
        $kategoriCounts = $kategoriData->pluck('produks_count')->toArray();

        // ============================================
        // TOP 5 PRODUK TERLARIS
        // ============================================
        $topProduk = TransaksiDetail::select(
                'produk_id',
                'nama_produk',
                DB::raw('SUM(qty) as total_qty'),
                DB::raw('SUM(subtotal) as total_omzet')
            )
            ->whereHas('transaksi', function ($q) use ($bulanIni, $tahunIni) {
                $q->whereMonth('tanggal', $bulanIni)
                  ->whereYear('tanggal', $tahunIni);
            })
            ->groupBy('produk_id', 'nama_produk')
            ->orderByDesc('total_qty')
            ->limit(5)
            ->get();

        // ============================================
        // PENGELUARAN PER KATEGORI
        // ============================================
        $pengeluaranPerKategori = Pengeluaran::select(
                'kategori',
                DB::raw('SUM(jumlah) as total_jumlah'),
                DB::raw('COUNT(*) as total_transaksi')
            )
            ->whereMonth('tanggal', $bulanIni)
            ->whereYear('tanggal', $tahunIni)
            ->groupBy('kategori')
            ->orderByDesc('total_jumlah')
            ->get()
            ->map(function ($item) {
                $item->kategori_label = Pengeluaran::KATEGORI[$item->kategori] ?? ucfirst($item->kategori);
                return $item;
            });

        // ============================================
        // PRODUK STOK MENIPIS
        // ============================================
        $produkStokMenipis = Produk::with(['kategori', 'satuan'])
            ->whereColumn('stok', '<=', 'stok_minimal')
            ->orderBy('stok', 'asc')
            ->limit(10)
            ->get();

        // ============================================
        // TERBARU
        // ============================================
        $transaksiTerbaru = Transaksi::with('details')
            ->latest('tanggal')
            ->latest('id')
            ->limit(5)
            ->get();

        $pembelianTerbaru = Pembelian::with('details')
            ->latest('tanggal')
            ->latest('id')
            ->limit(5)
            ->get();

        $pengeluaranTerbaru = Pengeluaran::latest('tanggal')
            ->latest('id')
            ->limit(5)
            ->get();

        // ============================================
        // STATISTIK
        // ============================================
        $totalProduk   = Produk::count();
        $totalKategori = Kategori::count();

        return view('dashboard.index', compact(
            'periode',
            // KPI
            'totalPenjualan', 'totalPembelian', 'totalPengeluaran',
            'labaKotor', 'labaBersih', 'jumlahTransaksi',
            'penjualanHariIni', 'transaksiHariIni',
            'pembelianHariIni', 'pengeluaranHariIni',
            // Perbandingan
            'penjualanBulanLalu', 'labaBulanLalu', 'transaksiBulanLalu',
            'persenPenjualan', 'persenLaba', 'persenTransaksi',
            // Grafik
            'labels', 'dataPenjualan', 'dataPembelian',
            'kategoriLabels', 'kategoriCounts',
            // List
            'topProduk', 'produkStokMenipis',
            'transaksiTerbaru', 'pembelianTerbaru', 'pengeluaranTerbaru',
            'pengeluaranPerKategori',
            // Statistik
            'totalProduk', 'totalKategori'
        ));
    }
}