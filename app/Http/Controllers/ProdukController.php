<?php

namespace App\Http\Controllers;

use App\Models\Kategori;
use App\Models\Satuan;
use App\Models\Pembelian;
use App\Models\PembelianDetail;
use App\Models\Produk;
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
        $query = Produk::with(['kategori', 'satuan']);

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('nama_produk', 'like', '%' . $request->search . '%')
                  ->orWhere('kode_produk', 'like', '%' . $request->search . '%');
            });
        }

        if ($request->filled('kategori_id')) {
            $query->where('kategori_id', $request->kategori_id);
        }

        if ($request->filled('satuan_id')) {
            $query->where('satuan_id', $request->satuan_id);
        }

        if ($request->filled('stok_status') && $request->stok_status === 'menipis') {
            $query->stokMenipis();
        }

        $produks   = $query->latest()->paginate(10)->withQueryString();
        $kategoris = Kategori::orderBy('nama_kategori')->get();
        $satuans   = Satuan::orderBy('nama_satuan')->get();

        return view('produk.index', compact('produks', 'kategoris', 'satuans'));
    }

    /**
     * Show the form for creating a new produk.
     */
    public function create()
    {
        $kategoris  = Kategori::orderBy('nama_kategori')->get();
        $satuans    = Satuan::orderBy('nama_satuan')->get();
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

        DB::beginTransaction();
        try {
            $produk = Produk::create($validated);

            // Jika stok awal > 0 dan harga_beli > 0, otomatis catat transaksi pembelian awal
            // agar modal pengadaan barang terhitung ke dalam Laporan Keuangan
            if ((int) $validated['stok'] > 0 && (float) $validated['harga_beli'] > 0) {
                $subtotal = (int) $validated['stok'] * (float) $validated['harga_beli'];

                $pembelian = Pembelian::create([
                    'kode_pembelian' => Pembelian::generateKodePembelian(),
                    'tanggal'        => now()->format('Y-m-d'),
                    'supplier'       => 'Stok Awal Produk Baru',
                    'total_harga'    => $subtotal,
                    'total_diskon'   => 0,
                    'grand_total'    => $subtotal,
                    'catatan'        => "Pengadaan stok awal untuk produk baru: {$produk->nama_produk} ({$produk->kode_produk})",
                    'user_id'        => auth()->id() ?? 1,
                ]);

                PembelianDetail::create([
                    'pembelian_id' => $pembelian->id,
                    'produk_id'    => $produk->id,
                    'nama_produk'  => $produk->nama_produk,
                    'harga_beli'   => $produk->harga_beli,
                    'qty'          => $validated['stok'],
                    'subtotal'     => $subtotal,
                ]);
            }

            DB::commit();

            $pesan = (int) $validated['stok'] > 0
                ? "Produk \"{$validated['nama_produk']}\" berhasil ditambahkan dan modal stok awal (Rp " . number_format((int)$validated['stok'] * (float)$validated['harga_beli'], 0, ',', '.') . ") telah dicatat ke Laporan Keuangan."
                : "Produk \"{$validated['nama_produk']}\" berhasil ditambahkan.";

            return redirect()
                ->route('produk.index')
                ->with('success', $pesan);
        } catch (\Throwable $e) {
            DB::rollBack();
            report($e);

            return back()
                ->withInput()
                ->with('error', 'Terjadi kesalahan saat menyimpan produk: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified produk.
     */
    public function show(Produk $produk)
    {
        $produk->load(['kategori', 'satuan']);

        $totalTerjual = $produk->transaksiDetails()->sum('qty');
        $totalDibeli  = $produk->pembelianDetails()->sum('qty');

        $riwayatPenjualan = $produk->transaksiDetails()
            ->with('transaksi.user')
            ->latest()
            ->limit(5)
            ->get();

        $riwayatPembelian = $produk->pembelianDetails()
            ->with('pembelian.user')
            ->latest()
            ->limit(5)
            ->get();

        return view('produk.show', compact('produk', 'totalTerjual', 'totalDibeli', 'riwayatPenjualan', 'riwayatPembelian'));
    }

    /**
     * Show the form for editing the specified produk.
     */
    public function edit(Produk $produk)
    {
        $kategoris = Kategori::orderBy('nama_kategori')->get();
        $satuans   = Satuan::orderBy('nama_satuan')->get();
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

        // Proteksi 2: Produk sudah dipakai di transaksi pembelian
        $pembelianCount = $produk->pembelianDetails()->count();
        if ($pembelianCount > 0) {
            return back()->with(
                'error',
                "Produk \"{$produk->nama_produk}\" tidak bisa dihapus karena sudah tercatat dalam {$pembelianCount} transaksi pembelian supplier."
            );
        }

        // Proteksi 3: Kode produk default sistem
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

            // Satuan
            'satuan_id.required'   => 'Satuan wajib dipilih.',
            'satuan_id.integer'    => 'Satuan tidak valid.',
            'satuan_id.exists'     => 'Satuan yang dipilih tidak ditemukan.',

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
            'satuan_id'    => 'Satuan',
            'kode_produk'  => 'Kode Produk',
            'nama_produk'  => 'Nama Produk',
            'harga_beli'   => 'Harga Beli',
            'harga_jual'   => 'Harga Jual',
            'stok'         => 'Stok',
            'stok_minimal' => 'Stok Minimal',
            'deskripsi'    => 'Deskripsi',
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
        if (!empty($merges)) {
            $request->merge($merges);
        }
    }
}