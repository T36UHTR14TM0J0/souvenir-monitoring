<?php

namespace App\Http\Controllers;

use App\Models\Kategori;
use App\Models\Produk;
use App\Models\Satuan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
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
        $query = Produk::souvenirJadi()->with(['kategori', 'satuan']);

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('nama_produk', 'like', '%'.$request->search.'%')
                    ->orWhere('kode_produk', 'like', '%'.$request->search.'%');
            });
        }

        if ($request->filled('jenis')) {
            $query->where('jenis', $request->jenis);
        }

        if ($request->filled('kategori_id')) {
            $query->where('kategori_id', $request->kategori_id);
        }

        if ($request->filled('satuan_id')) {
            $query->where('satuan_id', $request->satuan_id);
        }

        if ($request->filled('stok_status')) {
            if ($request->stok_status === 'menipis') {
                $query->whereColumn('stok', '<=', 'stok_minimal')->where('stok', '>', 0);
            } elseif ($request->stok_status === 'habis') {
                $query->where('stok', '<=', 0);
            } elseif ($request->stok_status === 'aman') {
                $query->whereColumn('stok', '>', 'stok_minimal');
            }
        }

        if ($request->filled('promo_status')) {
            if ($request->promo_status === 'promo') {
                $query->whereIn('tipe_diskon', ['persen', 'nominal'])->where('diskon', '>', 0);
            } elseif ($request->promo_status === 'reguler') {
                $query->where(function ($q) {
                    $q->whereNull('tipe_diskon')
                        ->orWhere('tipe_diskon', 'none')
                        ->orWhere('diskon', '<=', 0);
                });
            }
        }

        if ($request->filled('harga_min')) {
            $query->where('harga_jual', '>=', (float) $request->harga_min);
        }

        if ($request->filled('harga_max')) {
            $query->where('harga_jual', '<=', (float) $request->harga_max);
        }

        $sort = $request->input('sort', 'latest');
        match ($sort) {
            'nama_asc' => $query->orderBy('nama_produk', 'asc'),
            'nama_desc' => $query->orderBy('nama_produk', 'desc'),
            'stok_asc' => $query->orderBy('stok', 'asc'),
            'stok_desc' => $query->orderBy('stok', 'desc'),
            'harga_asc' => $query->orderBy('harga_jual', 'asc'),
            'harga_desc' => $query->orderBy('harga_jual', 'desc'),
            default => $query->latest(),
        };

        $produks = $query->paginate(10)->withQueryString();
        $kategoris = Kategori::orderBy('nama_kategori')->get();
        $satuans = Satuan::orderBy('nama_satuan')->get();

        // Ringkasan KPI Katalog Souvenir
        $allSouvenirs = Produk::souvenirJadi()->get();
        $totalItemSouvenir = $allSouvenirs->count();
        $totalStokFisik = (int) $allSouvenirs->sum('stok');
        $totalNilaiAset = (float) $allSouvenirs->sum(fn ($p) => (float) $p->stok * (float) $p->harga_beli);
        $potensiOmzet = (float) $allSouvenirs->sum(fn ($p) => (float) $p->stok * (float) $p->harga_setelah_diskon);
        $totalStokMenipis = $allSouvenirs->where(fn ($p) => $p->stok <= $p->stok_minimal && $p->stok > 0)->count();
        $totalStokHabis = $allSouvenirs->where(fn ($p) => $p->stok <= 0)->count();
        $totalPromo = $allSouvenirs->where(fn ($p) => in_array($p->tipe_diskon, ['persen', 'nominal'], true) && (float) $p->diskon > 0)->count();

        return view('produk.index', compact(
            'produks',
            'kategoris',
            'satuans',
            'totalItemSouvenir',
            'totalStokFisik',
            'totalNilaiAset',
            'potensiOmzet',
            'totalStokMenipis',
            'totalStokHabis',
            'totalPromo'
        ));
    }

    /**
     * Show the form for creating a new produk.
     */
    public function create()
    {
        $kategoris = Kategori::orderBy('nama_kategori')->get();
        $satuans = Satuan::orderBy('nama_satuan')->get();
        $kodeProduk = Produk::generateKodeProduk();

        return view('produk.create', compact('kategoris', 'satuans', 'kodeProduk'));
    }

    /**
     * Store a newly created produk in storage.
     */
    public function store(Request $request)
    {
        $this->sanitizeHarga($request);

        $validated = $request->validate([
            'kategori_id' => [
                'required',
                'integer',
                'exists:kategoris,id',
            ],
            'satuan_id' => [
                'required',
                'integer',
                'exists:satuans,id',
            ],
            'jenis' => [
                'nullable',
                'string',
                'in:souvenir_jadi,bahan_baku',
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
            'tipe_diskon' => [
                'required',
                'in:none,persen,nominal',
            ],
            'diskon' => [
                'nullable',
                'numeric',
                'min:0',
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
        $validated['jenis'] = 'souvenir_jadi';
        $validated['kode_produk'] = strtoupper(trim($validated['kode_produk']));
        $validated['nama_produk'] = $this->normalizeNama($validated['nama_produk']);

        $tipeDiskon = $validated['tipe_diskon'] ?? 'none';
        $diskon = (float) ($validated['diskon'] ?? 0);

        if ($tipeDiskon === 'none') {
            $diskon = 0;
        } elseif ($tipeDiskon === 'persen' && $diskon > 100) {
            return back()->withInput()->with('error', 'Diskon persen tidak boleh lebih dari 100%.');
        } elseif ($tipeDiskon === 'nominal' && $diskon > (float) $validated['harga_jual']) {
            return back()->withInput()->with('error', 'Diskon nominal tidak boleh melebihi harga jual.');
        }

        $validated['tipe_diskon'] = $tipeDiskon;
        $validated['diskon'] = $diskon;

        DB::beginTransaction();
        try {
            $produk = Produk::create($validated);

            DB::commit();

            return redirect()
                ->route('produk.index')
                ->with('success', "Produk \"{$validated['nama_produk']}\" berhasil ditambahkan.");
        } catch (\Throwable $e) {
            DB::rollBack();
            report($e);

            return back()
                ->withInput()
                ->with('error', 'Gagal menambahkan produk: '.$e->getMessage());
        }
    }

    /**
     * Display the specified produk.
     */
    public function show(Produk $produk)
    {
        $produk->load(['kategori', 'satuan']);

        $totalTerjual = $produk->transaksiDetails()->sum('qty');

        $riwayatPenjualan = $produk->transaksiDetails()
            ->with('transaksi.user')
            ->latest()
            ->limit(10)
            ->get();

        return view('produk.show', compact(
            'produk',
            'totalTerjual',
            'riwayatPenjualan'
        ));
    }

    /**
     * Show the form for editing the specified produk.
     */
    public function edit(Produk $produk)
    {
        $kategoris = Kategori::orderBy('nama_kategori')->get();
        $satuans = Satuan::orderBy('nama_satuan')->get();

        return view('produk.edit', compact('produk', 'kategoris', 'satuans'));
    }

    /**
     * Update the specified produk in storage.
     */
    public function update(Request $request, Produk $produk)
    {
        $this->sanitizeHarga($request);

        $validated = $request->validate([
            'kategori_id' => [
                'required',
                'integer',
                'exists:kategoris,id',
            ],
            'satuan_id' => [
                'required',
                'integer',
                'exists:satuans,id',
            ],
            'jenis' => [
                'nullable',
                'string',
                'in:souvenir_jadi,bahan_baku',
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
            'tipe_diskon' => [
                'required',
                'in:none,persen,nominal',
            ],
            'diskon' => [
                'nullable',
                'numeric',
                'min:0',
            ],
            'stok' => [
                'nullable',
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

        $tipeDiskon = $validated['tipe_diskon'] ?? 'none';
        $diskon = (float) ($validated['diskon'] ?? 0);

        if ($tipeDiskon === 'none') {
            $diskon = 0;
        } elseif ($tipeDiskon === 'persen' && $diskon > 100) {
            return back()->withInput()->with('error', 'Diskon persen tidak boleh lebih dari 100%.');
        } elseif ($tipeDiskon === 'nominal' && $diskon > (float) $validated['harga_jual']) {
            return back()->withInput()->with('error', 'Diskon nominal tidak boleh melebihi harga jual.');
        }

        $validated['tipe_diskon'] = $tipeDiskon;
        $validated['diskon'] = $diskon;

        // Proteksi: kode produk default tidak boleh diubah
        $isProtected = in_array($produk->kode_produk, self::PROTECTED_KODE_PRODUK, true);
        if ($isProtected && $validated['kode_produk'] !== $produk->kode_produk) {
            return back()
                ->withInput()
                ->with('error', "Kode produk default \"{$produk->kode_produk}\" tidak bisa diganti.");
        }

        // Kunci stok: Stok tidak boleh diubah langsung via form edit produk
        // agar tidak merusak perhitungan laporan keuangan & mutasi stok riil
        unset($validated['stok']);

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
        // Proteksi 1: Produk sudah dipakai di transaksi penjualan
        $penjualanCount = $produk->transaksiDetails()->count();
        if ($penjualanCount > 0) {
            return back()->with(
                'error',
                "Produk \"{$produk->nama_produk}\" tidak bisa dihapus karena sudah tercatat dalam {$penjualanCount} transaksi penjualan."
            );
        }

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
            'kategori_id.integer' => 'Kategori tidak valid.',
            'kategori_id.exists' => 'Kategori yang dipilih tidak ditemukan.',

            // Satuan
            'satuan_id.required' => 'Satuan wajib dipilih.',
            'satuan_id.integer' => 'Satuan tidak valid.',
            'satuan_id.exists' => 'Satuan yang dipilih tidak ditemukan.',

            // Kode Produk
            'kode_produk.required' => 'Kode produk wajib diisi.',
            'kode_produk.string' => 'Kode produk harus berupa teks.',
            'kode_produk.max' => 'Kode produk maksimal 50 karakter.',
            'kode_produk.unique' => 'Kode produk sudah digunakan, gunakan kode lain.',
            'kode_produk.regex' => 'Kode produk hanya boleh berisi huruf kapital, angka, dan tanda hubung (-).',

            // Nama Produk
            'nama_produk.required' => 'Nama produk wajib diisi.',
            'nama_produk.string' => 'Nama produk harus berupa teks.',
            'nama_produk.max' => 'Nama produk maksimal 150 karakter.',

            // Harga
            'harga_beli.required' => 'Harga beli wajib diisi.',
            'harga_beli.numeric' => 'Harga beli harus berupa angka.',
            'harga_beli.min' => 'Harga beli tidak boleh negatif.',

            'harga_jual.required' => 'Harga jual wajib diisi.',
            'harga_jual.numeric' => 'Harga jual harus berupa angka.',
            'harga_jual.min' => 'Harga jual tidak boleh negatif.',
            'harga_jual.gte' => 'Harga jual tidak boleh lebih kecil dari harga beli.',

            // Diskon
            'tipe_diskon.required' => 'Pilih jenis diskon produk.',
            'tipe_diskon.in' => 'Jenis diskon harus berupa Tidak Ada, Persen, atau Nominal.',
            'diskon.numeric' => 'Nilai diskon harus berupa angka.',
            'diskon.min' => 'Nilai diskon tidak boleh negatif.',

            // Stok
            'stok.required' => 'Stok wajib diisi.',
            'stok.integer' => 'Stok harus berupa angka bulat.',
            'stok.min' => 'Stok tidak boleh negatif.',

            'stok_minimal.required' => 'Stok minimal wajib diisi.',
            'stok_minimal.integer' => 'Stok minimal harus berupa angka bulat.',
            'stok_minimal.min' => 'Stok minimal tidak boleh negatif.',

            // Deskripsi
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
            'kategori_id' => 'Kategori',
            'satuan_id' => 'Satuan',
            'kode_produk' => 'Kode Produk',
            'nama_produk' => 'Nama Produk',
            'harga_beli' => 'Harga Beli',
            'harga_jual' => 'Harga Jual',
            'tipe_diskon' => 'Jenis Diskon',
            'diskon' => 'Nilai Diskon',
            'stok' => 'Stok',
            'stok_minimal' => 'Stok Minimal',
            'deskripsi' => 'Deskripsi',
        ];
    }

    /**
     * Bersihkan format rupiah (titik / non-digit) dari input harga_beli dan harga_jual.
     */
    private function sanitizeHarga(Request $request): void
    {
        $merges = [];
        if ($request->has('harga_beli') && $request->harga_beli !== null) {
            $cleanHb = preg_replace('/\D/', '', (string) $request->harga_beli);
            $merges['harga_beli'] = $cleanHb !== '' ? (int) $cleanHb : null;
        }
        if ($request->has('harga_jual') && $request->harga_jual !== null) {
            $cleanHj = preg_replace('/\D/', '', (string) $request->harga_jual);
            $merges['harga_jual'] = $cleanHj !== '' ? (int) $cleanHj : null;
        }
        if ($request->has('diskon') && $request->diskon !== null) {
            $tipeDiskon = $request->input('tipe_diskon', 'none');
            if ($tipeDiskon === 'nominal') {
                $cleanDisc = preg_replace('/\D/', '', (string) $request->diskon);
                $merges['diskon'] = $cleanDisc !== '' ? (float) $cleanDisc : 0;
            } elseif ($tipeDiskon === 'persen') {
                $cleanDisc = str_replace(',', '.', (string) $request->diskon);
                $merges['diskon'] = is_numeric($cleanDisc) ? (float) $cleanDisc : 0;
            } else {
                $merges['diskon'] = 0;
            }
        } else {
            $merges['diskon'] = 0;
        }
        if (! empty($merges)) {
            $request->merge($merges);
        }
    }
}
