<?php

namespace App\Http\Controllers;

use App\Models\Produk;
use App\Models\Satuan;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class SatuanController extends Controller
{
    /**
     * Satuan standar sistem yang tidak boleh dihapus demi integritas data.
     */
    private const PROTECTED_SATUAN = ['Pieces', 'Pcs'];

    /**
     * Display a listing of the satuan.
     */
    public function index(Request $request)
    {
        $query = Satuan::withCount('produks');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('nama_satuan', 'like', "%{$search}%")
                    ->orWhere('singkatan', 'like', "%{$search}%")
                    ->orWhere('deskripsi', 'like', "%{$search}%");
            });
        }

        if ($request->filled('tipe')) {
            if ($request->tipe === 'default') {
                $query->whereIn('nama_satuan', self::PROTECTED_SATUAN);
            } elseif ($request->tipe === 'kustom') {
                $query->whereNotIn('nama_satuan', self::PROTECTED_SATUAN);
            }
        }

        if ($request->filled('status_pakai')) {
            if ($request->status_pakai === 'terpakai') {
                $query->has('produks');
            } elseif ($request->status_pakai === 'kosong') {
                $query->doesntHave('produks');
            }
        }

        $sort = $request->input('sort', 'nama_asc');
        match ($sort) {
            'latest' => $query->latest(),
            'nama_desc' => $query->orderByDesc('nama_satuan'),
            'produk_desc' => $query->orderByDesc('produks_count'),
            'produk_asc' => $query->orderBy('produks_count'),
            default => $query->orderBy('nama_satuan', 'asc'),
        };

        $satuans = $query->paginate(10)->withQueryString();

        $totalSatuan = Satuan::count();
        $satuanTerpakai = Satuan::has('produks')->count();
        $satuanKosong = Satuan::doesntHave('produks')->count();
        $totalProduk = Produk::count();

        return view('satuan.index', compact(
            'satuans',
            'totalSatuan',
            'satuanTerpakai',
            'satuanKosong',
            'totalProduk'
        ));
    }

    /**
     * Show the form for creating a new satuan.
     */
    public function create()
    {
        return view('satuan.create');
    }

    /**
     * Store a newly created satuan in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_satuan' => [
                'required',
                'string',
                'max:50',
                'unique:satuans,nama_satuan',
            ],
            'singkatan' => [
                'required',
                'string',
                'max:20',
                'unique:satuans,singkatan',
            ],
            'deskripsi' => [
                'nullable',
                'string',
                'max:255',
            ],
        ], $this->validationMessages(), $this->validationAttributes());

        $validated['nama_satuan'] = $this->normalizeNama($validated['nama_satuan']);
        $validated['singkatan'] = strtolower(trim($validated['singkatan']));

        Satuan::create($validated);

        return redirect()
            ->route('satuan.index')
            ->with('success', "Satuan \"{$validated['nama_satuan']} ({$validated['singkatan']})\" berhasil ditambahkan.");
    }

    /**
     * Show the form for editing the specified satuan.
     */
    public function edit(Satuan $satuan)
    {
        return view('satuan.edit', compact('satuan'));
    }

    /**
     * Update the specified satuan in storage.
     */
    public function update(Request $request, Satuan $satuan)
    {
        $validated = $request->validate([
            'nama_satuan' => [
                'required',
                'string',
                'max:50',
                Rule::unique('satuans', 'nama_satuan')->ignore($satuan->id),
            ],
            'singkatan' => [
                'required',
                'string',
                'max:20',
                Rule::unique('satuans', 'singkatan')->ignore($satuan->id),
            ],
            'deskripsi' => [
                'nullable',
                'string',
                'max:255',
            ],
        ], $this->validationMessages(), $this->validationAttributes());

        $validated['nama_satuan'] = $this->normalizeNama($validated['nama_satuan']);
        $validated['singkatan'] = strtolower(trim($validated['singkatan']));

        // Proteksi rename satuan default sistem
        $isProtected = in_array($satuan->nama_satuan, self::PROTECTED_SATUAN, true);
        if ($isProtected && $validated['nama_satuan'] !== $satuan->nama_satuan) {
            return back()
                ->withInput()
                ->with('error', "Satuan default \"{$satuan->nama_satuan}\" tidak dapat diubah namanya.");
        }

        $satuan->update($validated);

        return redirect()
            ->route('satuan.index')
            ->with('success', "Satuan \"{$satuan->nama_satuan}\" berhasil diperbarui.");
    }

    /**
     * Remove the specified satuan from storage.
     */
    public function destroy(Satuan $satuan)
    {
        // Proteksi 1: Masih digunakan oleh produk
        $produkCount = $satuan->produks()->count();
        if ($produkCount > 0) {
            return back()->with(
                'error',
                "Satuan \"{$satuan->nama_satuan}\" tidak dapat dihapus karena masih digunakan oleh {$produkCount} produk. Pindahkan satuan produk tersebut terlebih dahulu."
            );
        }

        // Proteksi 2: Satuan default sistem
        if (in_array($satuan->nama_satuan, self::PROTECTED_SATUAN, true)) {
            return back()->with('error', "Satuan default \"{$satuan->nama_satuan}\" tidak dapat dihapus.");
        }

        $nama = $satuan->nama_satuan;
        $satuan->delete();

        return redirect()
            ->route('satuan.index')
            ->with('success', "Satuan \"{$nama}\" berhasil dihapus.");
    }

    /**
     * Normalisasi nama satuan -> rapikan spasi dan Title Case.
     */
    private function normalizeNama(string $nama): string
    {
        return ucwords(strtolower(trim(preg_replace('/\s+/', ' ', $nama))));
    }

    /**
     * Custom validation messages.
     */
    private function validationMessages(): array
    {
        return [
            'nama_satuan.required' => 'Nama satuan wajib diisi.',
            'nama_satuan.string' => 'Nama satuan harus berupa teks.',
            'nama_satuan.max' => 'Nama satuan maksimal 50 karakter.',
            'nama_satuan.unique' => 'Nama satuan ini sudah terdaftar.',

            'singkatan.required' => 'Singkatan/simbol satuan wajib diisi.',
            'singkatan.string' => 'Singkatan satuan harus berupa teks.',
            'singkatan.max' => 'Singkatan satuan maksimal 20 karakter.',
            'singkatan.unique' => 'Singkatan satuan ini sudah digunakan.',

            'deskripsi.string' => 'Deskripsi harus berupa teks.',
            'deskripsi.max' => 'Deskripsi maksimal 255 karakter.',
        ];
    }

    /**
     * Custom attribute names.
     */
    private function validationAttributes(): array
    {
        return [
            'nama_satuan' => 'Nama Satuan',
            'singkatan' => 'Singkatan',
            'deskripsi' => 'Deskripsi',
        ];
    }
}
