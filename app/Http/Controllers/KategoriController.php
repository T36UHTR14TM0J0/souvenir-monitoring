<?php

namespace App\Http\Controllers;

use App\Models\Kategori;
use App\Models\Produk;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class KategoriController extends Controller
{
    /**
     * Kategori default yang tidak boleh dihapus.
     */
    private const PROTECTED_KATEGORI = ['Umum', 'Lain-lain'];

    /**
     * Display a listing of the kategori.
     */
    public function index(Request $request)
    {
        $query = Kategori::withCount('produks');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('nama_kategori', 'like', '%'.$search.'%')
                    ->orWhere('deskripsi', 'like', '%'.$search.'%');
            });
        }

        if ($request->filled('tipe')) {
            if ($request->tipe === 'default') {
                $query->whereIn('nama_kategori', self::PROTECTED_KATEGORI);
            } elseif ($request->tipe === 'kustom') {
                $query->whereNotIn('nama_kategori', self::PROTECTED_KATEGORI);
            }
        }

        if ($request->filled('status_pakai')) {
            if ($request->status_pakai === 'terpakai') {
                $query->has('produks');
            } elseif ($request->status_pakai === 'kosong') {
                $query->doesntHave('produks');
            }
        }

        $sort = $request->input('sort', 'latest');
        match ($sort) {
            'nama_asc' => $query->orderBy('nama_kategori'),
            'nama_desc' => $query->orderByDesc('nama_kategori'),
            'produk_desc' => $query->orderByDesc('produks_count'),
            'produk_asc' => $query->orderBy('produks_count'),
            default => $query->latest(),
        };

        $kategoris = $query->paginate(10)->withQueryString();

        $totalKategori = Kategori::count();
        $kategoriTerpakai = Kategori::has('produks')->count();
        $kategoriKosong = Kategori::doesntHave('produks')->count();
        $totalProduk = Produk::count();

        return view('kategori.index', compact(
            'kategoris',
            'totalKategori',
            'kategoriTerpakai',
            'kategoriKosong',
            'totalProduk'
        ));
    }

    /**
     * Show the form for creating a new kategori.
     */
    public function create()
    {
        return view('kategori.create');
    }

    /**
     * Store a newly created kategori in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_kategori' => [
                'required',
                'string',
                'max:100',
                'unique:kategoris,nama_kategori',
            ],
            'deskripsi' => [
                'nullable',
                'string',
                'max:500',
            ],
        ], $this->validationMessages(), $this->validationAttributes());

        // Normalisasi nama kategori → TrimSpaces + Title Case
        $validated['nama_kategori'] = $this->normalizeNama($validated['nama_kategori']);

        Kategori::create($validated);

        return redirect()
            ->route('kategori.index')
            ->with('success', "Kategori \"{$validated['nama_kategori']}\" berhasil ditambahkan.");
    }

    /**
     * Show the form for editing the specified kategori.
     */
    public function edit(Kategori $kategori)
    {
        return view('kategori.edit', compact('kategori'));
    }

    /**
     * Update the specified kategori in storage.
     */
    public function update(Request $request, Kategori $kategori)
    {
        $validated = $request->validate([
            'nama_kategori' => [
                'required',
                'string',
                'max:100',
                Rule::unique('kategoris', 'nama_kategori')->ignore($kategori->id),
            ],
            'deskripsi' => [
                'nullable',
                'string',
                'max:500',
            ],
        ], $this->validationMessages(), $this->validationAttributes());

        $validated['nama_kategori'] = $this->normalizeNama($validated['nama_kategori']);

        // Proteksi: rename kategori default tidak diizinkan
        $isProtected = in_array($kategori->nama_kategori, self::PROTECTED_KATEGORI, true);
        if ($isProtected && $validated['nama_kategori'] !== $kategori->nama_kategori) {
            return back()
                ->withInput()
                ->with('error', "Kategori default \"{$kategori->nama_kategori}\" tidak bisa diganti namanya.");
        }

        $kategori->update($validated);

        return redirect()
            ->route('kategori.index')
            ->with('success', "Kategori \"{$kategori->nama_kategori}\" berhasil diubah.");
    }

    /**
     * Remove the specified kategori from storage.
     */
    public function destroy(Kategori $kategori)
    {
        // Proteksi 1: Kategori masih dipakai produk
        $produkCount = $kategori->produks()->count();
        if ($produkCount > 0) {
            return back()->with(
                'error',
                "Kategori \"{$kategori->nama_kategori}\" masih digunakan oleh {$produkCount} produk. Pindahkan produk terlebih dahulu."
            );
        }

        // Proteksi 2: Kategori default sistem
        if (in_array($kategori->nama_kategori, self::PROTECTED_KATEGORI, true)) {
            return back()->with('error', "Kategori default \"{$kategori->nama_kategori}\" tidak bisa dihapus.");
        }

        $nama = $kategori->nama_kategori;
        $kategori->delete();

        return redirect()
            ->route('kategori.index')
            ->with('success', "Kategori \"{$nama}\" berhasil dihapus.");
    }

    /**
     * Normalisasi nama kategori → rapikan spasi & Title Case.
     */
    private function normalizeNama(string $nama): string
    {
        return ucwords(strtolower(trim(preg_replace('/\s+/', ' ', $nama))));
    }

    /**
     * Custom validation messages (Bahasa Indonesia).
     */
    private function validationMessages(): array
    {
        return [
            'nama_kategori.required' => 'Nama kategori wajib diisi.',
            'nama_kategori.string' => 'Nama kategori harus berupa teks.',
            'nama_kategori.max' => 'Nama kategori maksimal 100 karakter.',
            'nama_kategori.unique' => 'Nama kategori sudah digunakan, gunakan nama lain.',

            'deskripsi.string' => 'Deskripsi harus berupa teks.',
            'deskripsi.max' => 'Deskripsi maksimal 500 karakter.',
        ];
    }

    /**
     * Custom attribute names (untuk pesan error yang lebih rapi).
     */
    private function validationAttributes(): array
    {
        return [
            'nama_kategori' => 'Nama Kategori',
            'deskripsi' => 'Deskripsi',
        ];
    }
}
