<?php

namespace App\Http\Controllers;

use App\Models\Pengeluaran;
use Illuminate\Http\Request;

class PengeluaranController extends Controller
{
    public function index(Request $request)
    {
        $query = Pengeluaran::with('user');

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('kode_pengeluaran', 'like', '%' . $request->search . '%')
                  ->orWhere('keterangan', 'like', '%' . $request->search . '%');
            });
        }

        if ($request->filled('kategori')) {
            $query->where('kategori', $request->kategori);
        }

        if ($request->filled('dari_tanggal')) {
            $query->whereDate('tanggal', '>=', $request->dari_tanggal);
        }

        if ($request->filled('sampai_tanggal')) {
            $query->whereDate('tanggal', '<=', $request->sampai_tanggal);
        }

        $pengeluarans = $query->latest('tanggal')
            ->latest('id')
            ->paginate(10)
            ->withQueryString();

        $totalBulanIni = Pengeluaran::whereMonth('tanggal', now()->month)
            ->whereYear('tanggal', now()->year)
            ->sum('jumlah');

        $totalTransaksi = Pengeluaran::whereMonth('tanggal', now()->month)
            ->whereYear('tanggal', now()->year)
            ->count();

        $totalHariIni = Pengeluaran::whereDate('tanggal', today())->sum('jumlah');

        $kategoris = Pengeluaran::KATEGORI;

        return view('pengeluaran.index', compact(
            'pengeluarans',
            'totalBulanIni',
            'totalTransaksi',
            'totalHariIni',
            'kategoris'
        ));
    }

    public function create()
    {
        $kodePengeluaran = Pengeluaran::generateKodePengeluaran();
        $kategoris       = Pengeluaran::KATEGORI;

        return view('pengeluaran.create', compact('kodePengeluaran', 'kategoris'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'tanggal'    => ['required', 'date'],
            'kategori'   => ['required', 'string', 'in:' . implode(',', array_keys(Pengeluaran::KATEGORI))],
            'keterangan' => ['required', 'string', 'max:255'],
            'jumlah'     => ['required', 'numeric', 'min:1'],
            'catatan'    => ['nullable', 'string', 'max:500'],
        ], $this->validationMessages(), $this->validationAttributes());

        $pengeluaran = Pengeluaran::create([
            'kode_pengeluaran' => Pengeluaran::generateKodePengeluaran(),
            'tanggal'          => $validated['tanggal'],
            'kategori'         => $validated['kategori'],
            'keterangan'       => $validated['keterangan'],
            'jumlah'           => $validated['jumlah'],
            'catatan'          => $validated['catatan'] ?? null,
            'user_id'          => auth()->id(),
        ]);

        return redirect()
            ->route('pengeluaran.index')
            ->with('success', "Pengeluaran \"{$pengeluaran->kode_pengeluaran}\" berhasil disimpan.");
    }

    public function show(Pengeluaran $pengeluaran)
    {
        $pengeluaran->load('user');

        return view('pengeluaran.show', compact('pengeluaran'));
    }

    public function edit(Pengeluaran $pengeluaran)
    {
        $kategoris = Pengeluaran::KATEGORI;

        return view('pengeluaran.edit', compact('pengeluaran', 'kategoris'));
    }

    public function update(Request $request, Pengeluaran $pengeluaran)
    {
        $validated = $request->validate([
            'tanggal'    => ['required', 'date'],
            'kategori'   => ['required', 'string', 'in:' . implode(',', array_keys(Pengeluaran::KATEGORI))],
            'keterangan' => ['required', 'string', 'max:255'],
            'jumlah'     => ['required', 'numeric', 'min:1'],
            'catatan'    => ['nullable', 'string', 'max:500'],
        ], $this->validationMessages(), $this->validationAttributes());

        $pengeluaran->update($validated);

        return redirect()
            ->route('pengeluaran.index')
            ->with('success', "Pengeluaran \"{$pengeluaran->kode_pengeluaran}\" berhasil diupdate.");
    }

    public function destroy(Pengeluaran $pengeluaran)
    {
        $kode = $pengeluaran->kode_pengeluaran;
        $pengeluaran->delete();

        return redirect()
            ->route('pengeluaran.index')
            ->with('success', "Pengeluaran \"{$kode}\" berhasil dihapus.");
    }

    private function validationMessages(): array
    {
        return [
            'tanggal.required'    => 'Tanggal wajib diisi.',
            'tanggal.date'        => 'Format tanggal tidak valid.',
            'kategori.required'   => 'Kategori wajib dipilih.',
            'kategori.in'         => 'Kategori tidak valid.',
            'keterangan.required' => 'Keterangan wajib diisi.',
            'keterangan.max'      => 'Keterangan maksimal 255 karakter.',
            'jumlah.required'     => 'Jumlah wajib diisi.',
            'jumlah.numeric'      => 'Jumlah harus berupa angka.',
            'jumlah.min'          => 'Jumlah minimal Rp 1.',
            'catatan.max'         => 'Catatan maksimal 500 karakter.',
        ];
    }

    private function validationAttributes(): array
    {
        return [
            'tanggal'    => 'Tanggal',
            'kategori'   => 'Kategori',
            'keterangan' => 'Keterangan',
            'jumlah'     => 'Jumlah',
            'catatan'    => 'Catatan',
        ];
    }
}