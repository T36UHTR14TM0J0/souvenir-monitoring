<?php

namespace App\Http\Controllers;

use App\Models\Kategori;
use App\Models\Produk;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ProdukController extends Controller
{
    /**
     * Kode produk default yang tidak boleh dihapus (contoh placeholder).
     */
    private const PROTECTED_KODE_PRODUK = ['PRD00000'];

    /**
     * Display a listing of the produk.
     */
    public function index(Request $request)
    {
        $query = Produk::with('kategori');

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('nama_produk', 'like', '%' . $request->search . '%')
                  ->orWhere('kode_produk', 'like', '%' . $request->search . '%');
            });
        }

        if ($request->filled('kategori_id')) {
            $query->where('kategori_id', $request->kategori_id);
        }

        if ($request->filled('stok_status') && $request->stok_status === 'menipis') {
            $query->stokMenipis();
        }

        $produks   = $query->latest()->paginate(10)->withQueryString();
        $kategoris = Kategori::orderBy('nama_kategori')->get();

        return view('produk.index', compact('produks', 'kategoris'));
    }

    /**
     * Show the form for creating a new produk.
     */
    public function create()
    {
        $kategoris  = Kategori::orderBy('nama_kategori')->get();
        $kodeProduk = Produk::generateKodeProduk();

        return view('produk.create', compact('kategoris', 'kodeProduk'));
    }

    /**
     * Store a newly created produk in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'kategori_id' => [
                'required',
                'integer',
                'exists:kategoris,id',
            ],
            'kode_produk' => [
                'required',
                'string',
                'max:50',
                'unique:produks,kode_produk',
                'regex:/^[A-Z0-9-]+$/',   // ← UPPERCASE, angka, dash
            ],
            'nama_produk' => [
                'required',
                'string',
                'max:150',
            ],
            'harga_beli' => [
                'required',
                'numeric',
                'min:0',
            ],
            'harga_jual' => [
                'required',
                'numeric',
                'min:0',
                'gte:harga_beli',   // ← harga jual >= harga beli
            ],
            'stok' => [
                'required',
                'integer',
                'min:0',
            ],
            'stok_minimal' => [
                'required',
                'integer',
                'min:0',
            ],
            'deskripsi' => [
                'nullable',
                'string',
                'max:500',
            ],
        ], $this->validationMessages(), $this->validationAttributes());

        // Normalisasi input
        $validated['kode_produk']  = strtoupper(trim($validated['kode_produk']));
        $validated['nama_produk']  = $this->normalizeNama($validated['nama_produk']);

        Produk::create($validated);

        return redirect()
            ->route('produk.index')
            ->with('success', "Produk \"{$validated['nama_produk']}\" berhasil ditambahkan.");
    }

    /**
     * Display the specified produk.
     */
    public function show(Produk $produk)
    {
        $produk->load('kategori');
        return view('produk.show', compact('produk'));
    }

    /**
     * Show the form for editing the specified produk.
     */
    public function edit(Produk $produk)
    {
        $kategoris = Kategori::orderBy('nama_kategori')->get();
        return view('produk.edit', compact('produk', 'kategoris'));
    }

    /**
     * Update the specified produk in storage.
     */
    public function update(Request $request, Produk $produk)
    {
        $validated = $request->validate([
            'kategori_id' => [
                'required',
                'integer',
                'exists:kategoris,id',
            ],
            'kode_produk' => [
                'required',
                'string',
                'max:50',
                Rule::unique('produks', 'kode_produk')->ignore($produk->id),
                'regex:/^[A-Z0-9-]+$/',
            ],
            'nama_produk' => [
                'required',
                'string',
                'max:150',
            ],
            'harga_beli' => [
                'required',
                'numeric',
                'min:0',
            ],
            'harga_jual' => [
                'required',
                'numeric',
                'min:0',
                'gte:harga_beli',
            ],
            'stok' => [
                'required',
                'integer',
                'min:0',
            ],
            'stok_minimal' => [
                'required',
                'integer',
                'min:0',
            ],
            'deskripsi' => [
                'nullable',
                'string',
                'max:500',
            ],
        ], $this->validationMessages(), $this->validationAttributes());

        $validated['kode_produk'] = strtoupper(trim($validated['kode_produk']));
        $validated['nama_produk'] = $this->normalizeNama($validated['nama_produk']);

        // Proteksi: kode produk default tidak boleh diubah
        $isProtected = in_array($produk->kode_produk, self::PROTECTED_KODE_PRODUK, true);
        if ($isProtected && $validated['kode_produk'] !== $produk->kode_produk) {
            return back()
                ->withInput()
                ->with('error', "Kode produk default \"{$produk->kode_produk}\" tidak bisa diganti.");
        }

        $produk->update($validated);

        return redirect()
            ->route('produk.index')
            ->with('success', "Produk \"{$produk->nama_produk}\" berhasil diubah.");
    }

    /**
     * Remove the specified produk from storage.
     */
    public function destroy(Produk $produk)
    {
        // Proteksi 1: Produk sudah dipakai di transaksi
        // (Aktifkan jika tabel transaksi sudah ada)
        // $transaksiCount = $produk->transaksiDetails()->count();
        // if ($transaksiCount > 0) {
        //     return back()->with(
        //         'error',
        //         "Produk \"{$produk->nama_produk}\" masih dipakai di {$transaksiCount} transaksi. Tidak bisa dihapus."
        //     );
        // }

        // Proteksi 2: Kode produk default sistem
        if (in_array($produk->kode_produk, self::PROTECTED_KODE_PRODUK, true)) {
            return back()->with('error', "Produk default \"{$produk->kode_produk}\" tidak bisa dihapus.");
        }

        $nama = $produk->nama_produk;
        $produk->delete();

        return redirect()
            ->route('produk.index')
            ->with('success', "Produk \"{$nama}\" berhasil dihapus.");
    }

    /**
     * Normalisasi nama produk → rapikan spasi (biarkan case asli user).
     */
    private function normalizeNama(string $nama): string
    {
        return trim(preg_replace('/\s+/', ' ', $nama));
    }

    /**
     * Custom validation messages (Bahasa Indonesia).
     */
    private function validationMessages(): array
    {
        return [
            // Kategori
            'kategori_id.required' => 'Kategori wajib dipilih.',
            'kategori_id.integer'  => 'Kategori tidak valid.',
            'kategori_id.exists'   => 'Kategori yang dipilih tidak ditemukan.',

            // Kode Produk
            'kode_produk.required' => 'Kode produk wajib diisi.',
            'kode_produk.string'   => 'Kode produk harus berupa teks.',
            'kode_produk.max'      => 'Kode produk maksimal 50 karakter.',
            'kode_produk.unique'   => 'Kode produk sudah digunakan, gunakan kode lain.',
            'kode_produk.regex'    => 'Kode produk hanya boleh berisi huruf kapital, angka, dan tanda hubung (-).',

            // Nama Produk
            'nama_produk.required' => 'Nama produk wajib diisi.',
            'nama_produk.string'   => 'Nama produk harus berupa teks.',
            'nama_produk.max'      => 'Nama produk maksimal 150 karakter.',

            // Harga
            'harga_beli.required' => 'Harga beli wajib diisi.',
            'harga_beli.numeric'  => 'Harga beli harus berupa angka.',
            'harga_beli.min'      => 'Harga beli tidak boleh negatif.',

            'harga_jual.required' => 'Harga jual wajib diisi.',
            'harga_jual.numeric'  => 'Harga jual harus berupa angka.',
            'harga_jual.min'      => 'Harga jual tidak boleh negatif.',
            'harga_jual.gte'      => 'Harga jual tidak boleh lebih kecil dari harga beli.',

            // Stok
            'stok.required'         => 'Stok wajib diisi.',
            'stok.integer'          => 'Stok harus berupa angka bulat.',
            'stok.min'              => 'Stok tidak boleh negatif.',

            'stok_minimal.required' => 'Stok minimal wajib diisi.',
            'stok_minimal.integer'  => 'Stok minimal harus berupa angka bulat.',
            'stok_minimal.min'      => 'Stok minimal tidak boleh negatif.',

            // Deskripsi
            'deskripsi.string' => 'Deskripsi harus berupa teks.',
            'deskripsi.max'    => 'Deskripsi maksimal 500 karakter.',
        ];
    }

    /**
     * Custom attribute names (untuk pesan error yang lebih rapi).
     */
    private function validationAttributes(): array
    {
        return [
            'kategori_id'  => 'Kategori',
            'kode_produk'  => 'Kode Produk',
            'nama_produk'  => 'Nama Produk',
            'harga_beli'   => 'Harga Beli',
            'harga_jual'   => 'Harga Jual',
            'stok'         => 'Stok',
            'stok_minimal' => 'Stok Minimal',
            'deskripsi'    => 'Deskripsi',
        ];
    }
}