<?php

namespace App\Http\Controllers;

use App\Models\KasMasuk;
use Illuminate\Http\Request;

class KasMasukController extends Controller
{
    public function index(Request $request)
    {
        $query = KasMasuk::with('user');

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('kode_transaksi', 'like', '%' . $request->search . '%')
                  ->orWhere('keterangan', 'like', '%' . $request->search . '%')
                  ->orWhere('sumber', 'like', '%' . $request->search . '%');
            });
        }

        if ($request->filled('tipe')) {
            $query->where('tipe', $request->tipe);
        }

        if ($request->filled('dari_tanggal')) {
            $query->whereDate('tanggal', '>=', $request->dari_tanggal);
        }

        if ($request->filled('sampai_tanggal')) {
            $query->whereDate('tanggal', '<=', $request->sampai_tanggal);
        }

        $kasMasuks = $query->latest('tanggal')
            ->latest('id')
            ->paginate(10)
            ->withQueryString();

        $bulanSekarang = now()->month;
        $tahunSekarang = now()->year;

        $totalBulanIni = KasMasuk::whereMonth('tanggal', $bulanSekarang)
            ->whereYear('tanggal', $tahunSekarang)
            ->sum('jumlah');

        $totalModalDisetorBulanIni = KasMasuk::where('tipe', 'modal_pemilik')
            ->whereMonth('tanggal', $bulanSekarang)
            ->whereYear('tanggal', $tahunSekarang)
            ->sum('jumlah');

        $totalHariIni = KasMasuk::whereDate('tanggal', today())->sum('jumlah');

        $tipes = KasMasuk::TIPE;

        return view('kas-masuk.index', compact(
            'kasMasuks',
            'totalBulanIni',
            'totalModalDisetorBulanIni',
            'totalHariIni',
            'tipes'
        ));
    }

    public function create()
    {
        $kodeTransaksi = KasMasuk::generateKodeTransaksi();
        $tipes         = KasMasuk::TIPE;
        $sumbers       = KasMasuk::SUMBER;

        return view('kas-masuk.create', compact('kodeTransaksi', 'tipes', 'sumbers'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'tanggal'    => ['required', 'date'],
            'tipe'       => ['required', 'string', 'in:' . implode(',', array_keys(KasMasuk::TIPE))],
            'sumber'     => ['required', 'string', 'max:100'],
            'keterangan' => ['required', 'string', 'max:255'],
            'jumlah'     => ['required', 'numeric', 'min:1'],
            'catatan'    => ['nullable', 'string', 'max:500'],
        ]);

        $kasMasuk = KasMasuk::create([
            'kode_transaksi' => KasMasuk::generateKodeTransaksi(),
            'tanggal'        => $validated['tanggal'],
            'tipe'           => $validated['tipe'],
            'sumber'         => $validated['sumber'],
            'keterangan'     => $validated['keterangan'],
            'jumlah'         => $validated['jumlah'],
            'catatan'        => $validated['catatan'] ?? null,
            'user_id'        => auth()->id(),
        ]);

        return redirect()
            ->route('kas-masuk.index')
            ->with('success', "Kas Masuk / Modal \"{$kasMasuk->kode_transaksi}\" berhasil disimpan.");
    }

    public function edit(KasMasuk $kasMasuk)
    {
        $tipes   = KasMasuk::TIPE;
        $sumbers = KasMasuk::SUMBER;

        return view('kas-masuk.edit', compact('kasMasuk', 'tipes', 'sumbers'));
    }

    public function update(Request $request, KasMasuk $kasMasuk)
    {
        $validated = $request->validate([
            'tanggal'    => ['required', 'date'],
            'tipe'       => ['required', 'string', 'in:' . implode(',', array_keys(KasMasuk::TIPE))],
            'sumber'     => ['required', 'string', 'max:100'],
            'keterangan' => ['required', 'string', 'max:255'],
            'jumlah'     => ['required', 'numeric', 'min:1'],
            'catatan'    => ['nullable', 'string', 'max:500'],
        ]);

        $kasMasuk->update($validated);

        return redirect()
            ->route('kas-masuk.index')
            ->with('success', "Kas Masuk / Modal \"{$kasMasuk->kode_transaksi}\" berhasil diupdate.");
    }

    public function destroy(KasMasuk $kasMasuk)
    {
        $kode = $kasMasuk->kode_transaksi;
        $kasMasuk->delete();

        return redirect()
            ->route('kas-masuk.index')
            ->with('success', "Data \"{$kode}\" berhasil dihapus.");
    }
}
