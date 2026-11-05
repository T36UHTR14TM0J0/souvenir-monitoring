<?php

namespace App\Http\Controllers;

use App\Models\KasMasuk;
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

        $sort = $request->input('sort', 'latest');
        match ($sort) {
            'oldest' => $query->oldest('tanggal')->oldest('id'),
            'jumlah_desc' => $query->orderByDesc('jumlah')->latest('tanggal'),
            'jumlah_asc' => $query->orderBy('jumlah')->latest('tanggal'),
            default => $query->latest('tanggal')->latest('id'),
        };

        $kasMasuks = $query->paginate(10)->withQueryString();

        $bulanSekarang = now()->month;
        $tahunSekarang = now()->year;

        $totalBulanIni = KasMasuk::whereMonth('tanggal', $bulanSekarang)
            ->whereYear('tanggal', $tahunSekarang)
            ->sum('jumlah');

        $totalModalDisetorBulanIni = KasMasuk::where('tipe', 'modal_pemilik')
            ->whereMonth('tanggal', $bulanSekarang)
            ->whereYear('tanggal', $tahunSekarang)
            ->sum('jumlah');

        $totalPendapatanLainBulanIni = KasMasuk::where('tipe', 'pendapatan_lain')
            ->whereMonth('tanggal', $bulanSekarang)
            ->whereYear('tanggal', $tahunSekarang)
            ->sum('jumlah');

        $totalTransaksiBulanIni = KasMasuk::whereMonth('tanggal', $bulanSekarang)
            ->whereYear('tanggal', $tahunSekarang)
            ->count();

        $totalHariIni = KasMasuk::whereDate('tanggal', today())->sum('jumlah');

        $tipes = KasMasuk::TIPE;
        $sumbers = KasMasuk::SUMBER;

        return view('kas-masuk.index', compact(
            'kasMasuks',
            'totalBulanIni',
            'totalModalDisetorBulanIni',
            'totalPendapatanLainBulanIni',
            'totalTransaksiBulanIni',
            'totalHariIni',
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
