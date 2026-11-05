<?php

namespace App\Http\Controllers;

use App\Models\Kategori;
use App\Models\Produk;
use App\Models\Satuan;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class BahanBakuController extends Controller
{
    public function index(Request $request)
    {
        $query = Produk::bahanBaku()->with(['kategori', 'satuan']);

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('nama_produk', 'like', '%'.$request->search.'%')
                    ->orWhere('kode_produk', 'like', '%'.$request->search.'%');
            });
        }

        if ($request->filled('kategori_id')) {
            $query->where('kategori_id', $request->kategori_id);
        }

        if ($request->filled('satuan_id')) {
            $query->where('satuan_id', $request->satuan_id);
        }

        if ($request->filled('stok_status')) {
            if ($request->stok_status === 'menipis') {
                $query->stokMenipis();
            } elseif ($request->stok_status === 'habis') {
                $query->where('stok', '<=', 0);
            } elseif ($request->stok_status === 'aman') {
                $query->whereColumn('stok', '>', 'stok_minimal');
            }
        }

        $sort = $request->input('sort', 'latest');
        match ($sort) {
            'nama_asc' => $query->orderBy('nama_produk', 'asc'),
            'nama_desc' => $query->orderBy('nama_produk', 'desc'),
            'stok_asc' => $query->orderBy('stok', 'asc'),
            'stok_desc' => $query->orderBy('stok', 'desc'),
            'harga_asc' => $query->orderBy('harga_beli', 'asc'),
            'harga_desc' => $query->orderBy('harga_beli', 'desc'),
            default => $query->latest('id'),
        };

        $bahanBakus = $query->paginate(10)->withQueryString();
        $kategoris = Kategori::orderBy('nama_kategori')->get();
        $satuans = Satuan::orderBy('nama_satuan')->get();

        $allBahans = Produk::bahanBaku()->get();
        $totalItemBahan = $allBahans->count();
        $totalStokFisik = (int) $allBahans->sum('stok');
        $totalNilaiAset = (float) $allBahans->sum(fn ($b) => (float) $b->stok * (float) $b->harga_beli);
        $totalStokMenipis = $allBahans->where(fn ($b) => $b->stok <= $b->stok_minimal && $b->stok > 0)->count();
        $totalStokHabis = $allBahans->where(fn ($b) => $b->stok <= 0)->count();

        return view('bahan-baku.index', compact(
            'bahanBakus',
            'kategoris',
            'satuans',
            'totalItemBahan',
            'totalStokFisik',
            'totalNilaiAset',
            'totalStokMenipis',
            'totalStokHabis'
        ));
    }

    public function create()
    {
        $kategoris = Kategori::orderBy('nama_kategori')->get();
        $satuans = Satuan::orderBy('nama_satuan')->get();

        // Auto generate kode bahan: BB00001
        $last = Produk::where('kode_produk', 'like', 'BB%')->orderBy('id', 'desc')->first();
        if ($last && preg_match('/BB(\d+)/', $last->kode_produk, $matches)) {
            $next = ((int) $matches[1]) + 1;
        } else {
            $next = 1;
        }
        $kodeBahan = 'BB'.str_pad($next, 5, '0', STR_PAD_LEFT);

        return view('bahan-baku.create', compact('kategoris', 'satuans', 'kodeBahan'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'kategori_id' => ['required', 'integer', 'exists:kategoris,id'],
            'satuan_id' => ['required', 'integer', 'exists:satuans,id'],
            'kode_produk' => [
                'required',
                'string',
                'max:50',
                'unique:produks,kode_produk',
                'regex:/^[A-Z0-9-]+$/',
            ],
            'nama_produk' => ['required', 'string', 'max:150'],
            'stok_minimal' => ['required', 'integer', 'min:0'],
            'deskripsi' => ['nullable', 'string', 'max:500'],
        ], [
            'kategori_id.required' => 'Kategori bahan baku wajib dipilih.',
            'satuan_id.required' => 'Satuan takaran wajib dipilih.',
            'kode_produk.required' => 'Kode bahan baku wajib diisi.',
            'kode_produk.unique' => 'Kode bahan baku sudah digunakan.',
            'nama_produk.required' => 'Nama bahan baku wajib diisi.',
            'stok_minimal.required' => 'Batas stok minimum alert wajib diisi.',
        ]);

        $validated['jenis'] = 'bahan_baku';
        $validated['kode_produk'] = strtoupper(trim($validated['kode_produk']));
        $validated['nama_produk'] = trim($validated['nama_produk']);
        $validated['stok'] = 0; // Stok bertambah saat belanja supplier kargo dikonfirmasi
        $validated['harga_beli'] = 0; // Harga beli otomatis tercatat saat transaksi pembelian
        $validated['harga_jual'] = 0;
        $validated['tipe_diskon'] = 'none';
        $validated['diskon'] = 0;

        $bahanBaku = Produk::create($validated);

        return redirect()
            ->route('bahan-baku.index')
            ->with('success', "Bahan baku \"{$bahanBaku->nama_produk}\" berhasil ditambahkan. Stok akan bertambah saat paket pengadaan barang diterima di workshop.");
    }

    public function show(Produk $bahanBaku)
    {
        $bahanBaku->load(['kategori', 'satuan', 'pembelianDetails.pembelian.user', 'pemakaianDetails.pemakaian.user', 'produkHasil.satuan']);

        return view('bahan-baku.show', compact('bahanBaku'));
    }

    public function edit(Produk $bahanBaku)
    {
        $kategoris = Kategori::orderBy('nama_kategori')->get();
        $satuans = Satuan::orderBy('nama_satuan')->get();

        return view('bahan-baku.edit', compact('bahanBaku', 'kategoris', 'satuans'));
    }

    public function update(Request $request, Produk $bahanBaku)
    {
        $validated = $request->validate([
            'kategori_id' => ['required', 'integer', 'exists:kategoris,id'],
            'satuan_id' => ['required', 'integer', 'exists:satuans,id'],
            'kode_produk' => [
                'required',
                'string',
                'max:50',
                Rule::unique('produks', 'kode_produk')->ignore($bahanBaku->id),
                'regex:/^[A-Z0-9-]+$/',
            ],
            'nama_produk' => ['required', 'string', 'max:150'],
            'stok_minimal' => ['required', 'integer', 'min:0'],
            'deskripsi' => ['nullable', 'string', 'max:500'],
        ], [
            'kategori_id.required' => 'Kategori bahan baku wajib dipilih.',
            'satuan_id.required' => 'Satuan takaran wajib dipilih.',
            'kode_produk.required' => 'Kode bahan baku wajib diisi.',
            'kode_produk.unique' => 'Kode bahan baku sudah digunakan.',
            'nama_produk.required' => 'Nama bahan baku wajib diisi.',
            'stok_minimal.required' => 'Batas stok minimum alert wajib diisi.',
        ]);

        $validated['kode_produk'] = strtoupper(trim($validated['kode_produk']));
        $validated['nama_produk'] = trim($validated['nama_produk']);

        // Stok dan harga beli tidak diubah di sini, tetap terjaga sesuai riwayat transaksi fisik
        $bahanBaku->update($validated);

        return redirect()
            ->route('bahan-baku.index')
            ->with('success', "Data bahan baku \"{$bahanBaku->nama_produk}\" berhasil diperbarui.");
    }

    public function destroy(Produk $bahanBaku)
    {
        if ($bahanBaku->pembelianDetails()->exists()) {
            return back()->with('error', "Bahan baku \"{$bahanBaku->nama_produk}\" tidak dapat dihapus karena sudah memiliki riwayat pembelian.");
        }

        if ($bahanBaku->produkHasil()->exists()) {
            return back()->with('error', "Bahan baku \"{$bahanBaku->nama_produk}\" tidak dapat dihapus karena masih ditautkan pada resep (BOM) souvenir jadi.");
        }

        $nama = $bahanBaku->nama_produk;
        $bahanBaku->delete();

        return redirect()
            ->route('bahan-baku.index')
            ->with('success', "Bahan baku \"{$nama}\" berhasil dihapus.");
    }
}
