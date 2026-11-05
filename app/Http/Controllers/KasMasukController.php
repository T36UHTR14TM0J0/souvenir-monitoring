<?php

namespace App\Http\Controllers;

use App\Models\KasMasuk;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class KasMasukController extends Controller
{
    public function index(Request $request)
    {
        $query = KasMasuk::with('user');

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('kode_transaksi', 'like', '%'.$request->search.'%')
                    ->orWhere('keterangan', 'like', '%'.$request->search.'%')
                    ->orWhere('sumber', 'like', '%'.$request->search.'%');
            });
        }

        if ($request->filled('tipe')) {
            $query->where('tipe', $request->tipe);
        }

        if ($request->filled('sumber')) {
            $query->where('sumber', $request->sumber);
        }

        if ($request->filled('dari_tanggal')) {
            $query->whereDate('tanggal', '>=', $request->dari_tanggal);
        }

        if ($request->filled('sampai_tanggal')) {
            $query->whereDate('tanggal', '<=', $request->sampai_tanggal);
        }

        if ($request->filled('bulan')) {
            $query->whereMonth('tanggal', $request->bulan);
        }

        if ($request->filled('tahun')) {
            $query->whereYear('tanggal', $request->tahun);
        }

        $sort = $request->input('sort', 'latest');
        match ($sort) {
            'oldest' => $query->oldest('tanggal')->oldest('id'),
            'jumlah_desc' => $query->orderByDesc('jumlah')->latest('tanggal'),
            'jumlah_asc' => $query->orderBy('jumlah')->latest('tanggal'),
            default => $query->latest('tanggal')->latest('id'),
        };

        $kasMasuks = $query->paginate(10)->withQueryString();

        $hasDateFilter = $request->filled('dari_tanggal') || $request->filled('sampai_tanggal') || $request->filled('bulan') || $request->filled('tahun');

        $statsQuery = KasMasuk::query();

        if ($hasDateFilter) {
            if ($request->filled('dari_tanggal')) {
                $statsQuery->whereDate('tanggal', '>=', $request->dari_tanggal);
            }
            if ($request->filled('sampai_tanggal')) {
                $statsQuery->whereDate('tanggal', '<=', $request->sampai_tanggal);
            }
            if ($request->filled('bulan')) {
                $statsQuery->whereMonth('tanggal', $request->bulan);
            }
            if ($request->filled('tahun')) {
                $statsQuery->whereYear('tanggal', $request->tahun);
            }

            if ($request->filled('dari_tanggal') && $request->filled('sampai_tanggal')) {
                $periodeLabel = Carbon::parse($request->dari_tanggal)->translatedFormat('d M Y').' - '.Carbon::parse($request->sampai_tanggal)->translatedFormat('d M Y');
            } elseif ($request->filled('dari_tanggal')) {
                $periodeLabel = 'Mulai '.Carbon::parse($request->dari_tanggal)->translatedFormat('d M Y');
            } elseif ($request->filled('sampai_tanggal')) {
                $periodeLabel = 's/d '.Carbon::parse($request->sampai_tanggal)->translatedFormat('d M Y');
            } elseif ($request->filled('bulan') && $request->filled('tahun')) {
                $periodeLabel = Carbon::createFromDate($request->tahun, $request->bulan, 1)->translatedFormat('F Y');
            } elseif ($request->filled('bulan')) {
                $periodeLabel = Carbon::createFromDate(now()->year, $request->bulan, 1)->translatedFormat('F');
            } else {
                $periodeLabel = 'Tahun '.$request->tahun;
            }
        } else {
            // Anchor cerdas: jika bulan saat ini belum memiliki transaksi (misal seeder selesai Okt 2026),
            // otomatis gunakan bulan transaksi terakhir yang tersedia agar card statistik terisi data aktual
            $hasCurrentMonthData = KasMasuk::whereYear('tanggal', now()->year)
                ->whereMonth('tanggal', now()->month)
                ->exists();

            if ($hasCurrentMonthData) {
                $anchorDate = now();
            } else {
                $latestDate = KasMasuk::max('tanggal');
                $anchorDate = $latestDate ? Carbon::parse($latestDate) : now();
            }

            $periodeLabel = $anchorDate->translatedFormat('F Y');
            $statsQuery->whereMonth('tanggal', $anchorDate->month)
                ->whereYear('tanggal', $anchorDate->year);
        }

        $totalBulanIni = (float) (clone $statsQuery)->sum('jumlah');
        $totalModalDisetorBulanIni = (float) (clone $statsQuery)->where('tipe', 'modal_pemilik')->sum('jumlah');
        $totalPendapatanLainBulanIni = (float) (clone $statsQuery)->where('tipe', 'pendapatan_lain')->sum('jumlah');
        $totalTransaksiBulanIni = (int) (clone $statsQuery)->count();

        $totalHariIni = (float) KasMasuk::whereDate('tanggal', today())->sum('jumlah');
        $totalAkumulasi = (float) KasMasuk::sum('jumlah');
        $totalTransaksiSemua = (int) KasMasuk::count();

        $tipes = KasMasuk::TIPE;
        $sumbers = KasMasuk::SUMBER;

        return view('kas-masuk.index', compact(
            'kasMasuks',
            'periodeLabel',
            'totalBulanIni',
            'totalModalDisetorBulanIni',
            'totalPendapatanLainBulanIni',
            'totalTransaksiBulanIni',
            'totalHariIni',
            'totalAkumulasi',
            'totalTransaksiSemua',
            'tipes',
            'sumbers'
        ));
    }

    public function create()
    {
        $kodeTransaksi = KasMasuk::generateKodeTransaksi();
        $tipes = KasMasuk::TIPE;
        $sumbers = KasMasuk::SUMBER;

        return view('kas-masuk.create', compact('kodeTransaksi', 'tipes', 'sumbers'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'tanggal' => ['required', 'date'],
            'tipe' => ['required', 'string', 'in:'.implode(',', array_keys(KasMasuk::TIPE))],
            'sumber' => ['required', 'string', 'max:100'],
            'keterangan' => ['required', 'string', 'max:255'],
            'jumlah' => ['required', 'numeric', 'min:1'],
            'bukti_transaksi' => ['nullable', 'file', 'mimes:jpeg,jpg,png,webp,pdf', 'max:5120'],
            'catatan' => ['nullable', 'string', 'max:500'],
        ]);

        $buktiPath = null;
        if ($request->hasFile('bukti_transaksi')) {
            $buktiPath = $request->file('bukti_transaksi')->store('bukti-transaksi/kas-masuk', 'public');
        }

        $kasMasuk = KasMasuk::create([
            'kode_transaksi' => KasMasuk::generateKodeTransaksi(),
            'tanggal' => $validated['tanggal'],
            'tipe' => $validated['tipe'],
            'sumber' => $validated['sumber'],
            'keterangan' => $validated['keterangan'],
            'jumlah' => $validated['jumlah'],
            'catatan' => $validated['catatan'] ?? null,
            'bukti_transaksi' => $buktiPath,
            'user_id' => auth()->id(),
        ]);

        return redirect()
            ->route('kas-masuk.index')
            ->with('success', "Kas Masuk / Modal \"{$kasMasuk->kode_transaksi}\" berhasil disimpan.");
    }

    public function edit(KasMasuk $kasMasuk)
    {
        $tipes = KasMasuk::TIPE;
        $sumbers = KasMasuk::SUMBER;

        return view('kas-masuk.edit', compact('kasMasuk', 'tipes', 'sumbers'));
    }

    public function update(Request $request, KasMasuk $kasMasuk)
    {
        $validated = $request->validate([
            'tanggal' => ['required', 'date'],
            'tipe' => ['required', 'string', 'in:'.implode(',', array_keys(KasMasuk::TIPE))],
            'sumber' => ['required', 'string', 'max:100'],
            'keterangan' => ['required', 'string', 'max:255'],
            'jumlah' => ['required', 'numeric', 'min:1'],
            'bukti_transaksi' => ['nullable', 'file', 'mimes:jpeg,jpg,png,webp,pdf', 'max:5120'],
            'hapus_bukti' => ['nullable', 'boolean'],
            'catatan' => ['nullable', 'string', 'max:500'],
        ]);

        if ($request->hasFile('bukti_transaksi')) {
            if ($kasMasuk->bukti_transaksi) {
                Storage::disk('public')->delete($kasMasuk->bukti_transaksi);
            }
            $validated['bukti_transaksi'] = $request->file('bukti_transaksi')->store('bukti-transaksi/kas-masuk', 'public');
        } elseif ($request->boolean('hapus_bukti')) {
            if ($kasMasuk->bukti_transaksi) {
                Storage::disk('public')->delete($kasMasuk->bukti_transaksi);
            }
            $validated['bukti_transaksi'] = null;
        }

        $kasMasuk->update($validated);

        return redirect()
            ->route('kas-masuk.index')
            ->with('success', "Kas Masuk / Modal \"{$kasMasuk->kode_transaksi}\" berhasil diupdate.");
    }

    public function destroy(KasMasuk $kasMasuk)
    {
        $kode = $kasMasuk->kode_transaksi;
        if ($kasMasuk->bukti_transaksi) {
            Storage::disk('public')->delete($kasMasuk->bukti_transaksi);
        }
        $kasMasuk->delete();

        return redirect()
            ->route('kas-masuk.index')
            ->with('success', "Data \"{$kode}\" berhasil dihapus.");
    }
}
