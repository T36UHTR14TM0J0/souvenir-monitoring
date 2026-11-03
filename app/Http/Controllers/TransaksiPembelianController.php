<?php

namespace App\Http\Controllers;

use App\Models\Pembelian;
use App\Models\PembelianDetail;
use App\Models\Produk;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TransaksiPembelianController extends Controller
{
    public function index(Request $request)
    {
        $query = Pembelian::with(['user', 'details']);

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('kode_pembelian', 'like', '%' . $request->search . '%')
                  ->orWhere('supplier', 'like', '%' . $request->search . '%');
            });
        }

        if ($request->filled('dari_tanggal')) {
            $query->whereDate('tanggal', '>=', $request->dari_tanggal);
        }

        if ($request->filled('sampai_tanggal')) {
            $query->whereDate('tanggal', '<=', $request->sampai_tanggal);
        }

        $pembelians = $query->latest('tanggal')
            ->latest('id')
            ->paginate(10)
            ->withQueryString();

        $totalBulanIni  = Pembelian::whereMonth('tanggal', now()->month)
            ->whereYear('tanggal', now()->year)
            ->sum('grand_total');

        $totalPembelian = Pembelian::whereMonth('tanggal', now()->month)
            ->whereYear('tanggal', now()->year)
            ->count();

        $totalHariIni   = Pembelian::whereDate('tanggal', today())->sum('grand_total');

        return view('transaksi-pembelian.index', compact(
            'pembelians',
            'totalBulanIni',
            'totalPembelian',
            'totalHariIni'
        ));
    }

    public function create()
    {
        $kodePembelian = Pembelian::generateKodePembelian();

        $produks = Produk::with(['kategori', 'satuan'])
            ->orderBy('nama_produk')
            ->get();

        $produksJson = $produks->map(function ($p) {
            return [
                'id'          => $p->id,
                'nama'        => $p->nama_produk,
                'kode'        => $p->kode_produk,
                'satuan'      => $p->satuan->singkatan ?? 'pcs',
                'harga_beli'  => (float) $p->harga_beli,
                'stok'        => (int) $p->stok,
                'kategori_id' => $p->kategori_id,
            ];
        })->values()->toArray();

        $kategoris = \App\Models\Kategori::withCount('produks')
            ->orderBy('nama_kategori')
            ->get();

        return view('transaksi-pembelian.create', compact(
            'kodePembelian',
            'produks',
            'produksJson',
            'kategoris'
        ));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'tanggal'             => ['required', 'date'],
            'supplier'            => ['nullable', 'string', 'max:150'],
            'catatan'             => ['nullable', 'string', 'max:500'],

            'items'               => ['required', 'array', 'min:1'],
            'items.*.produk_id'   => ['required', 'integer', 'exists:produks,id'],
            'items.*.qty'         => ['required', 'integer', 'min:1'],
            'items.*.harga_beli'  => ['required', 'numeric', 'min:0'],

            'total_diskon'        => ['nullable', 'numeric', 'min:0'],
        ], $this->validationMessages(), $this->validationAttributes());

        $totalHarga = 0;
        foreach ($validated['items'] as $item) {
            $totalHarga += $item['qty'] * $item['harga_beli'];
        }

        $totalDiskon = $validated['total_diskon'] ?? 0;
        $grandTotal  = max(0, $totalHarga - $totalDiskon);

        DB::beginTransaction();
        try {
            $pembelian = Pembelian::create([
                'kode_pembelian' => Pembelian::generateKodePembelian(),
                'tanggal'        => $validated['tanggal'],
                'supplier'       => $validated['supplier'] ?? null,
                'total_harga'    => $totalHarga,
                'total_diskon'   => $totalDiskon,
                'grand_total'    => $grandTotal,
                'catatan'        => $validated['catatan'] ?? null,
                'user_id'        => auth()->id(),
            ]);

            foreach ($validated['items'] as $item) {
                $produk   = Produk::find($item['produk_id']);
                $subtotal = $item['qty'] * $item['harga_beli'];

                PembelianDetail::create([
                    'pembelian_id' => $pembelian->id,
                    'produk_id'    => $produk->id,
                    'nama_produk'  => $produk->nama_produk,
                    'harga_beli'   => $item['harga_beli'],
                    'qty'          => $item['qty'],
                    'subtotal'     => $subtotal,
                ]);

                $produk->increment('stok', $item['qty']);
                $produk->update(['harga_beli' => $item['harga_beli']]);
            }

            DB::commit();

            return redirect()
                ->route('transaksi-pembelian.show', $pembelian)
                ->with('success', "Pembelian \"{$pembelian->kode_pembelian}\" berhasil disimpan.");

        } catch (\Exception $e) {
            DB::rollBack();

            return back()->withInput()->with('error', 'Gagal menyimpan pembelian: ' . $e->getMessage());
        }
    }

    public function show(Pembelian $transaksiPembelian)
    {
        $transaksiPembelian->load(['details.produk.satuan', 'user']);

        return view('transaksi-pembelian.show', [
            'pembelian' => $transaksiPembelian,
        ]);
    }

    public function destroy(Pembelian $transaksiPembelian)
    {
        if (!$transaksiPembelian->tanggal->isToday()) {
            return back()->with('error', 'Pembelian hanya bisa dihapus di hari yang sama.');
        }

        DB::beginTransaction();
        try {
            foreach ($transaksiPembelian->details as $detail) {
                $produk = Produk::find($detail->produk_id);
                if ($produk) {
                    if ($produk->stok < $detail->qty) {
                        DB::rollBack();
                        return back()->with('error', "Stok \"{$produk->nama_produk}\" tidak cukup untuk dikurangi. Stok saat ini: {$produk->stok}, butuh: {$detail->qty}.");
                    }
                    $produk->decrement('stok', $detail->qty);
                }
            }

            $kode = $transaksiPembelian->kode_pembelian;
            $transaksiPembelian->delete();

            DB::commit();

            return redirect()
                ->route('transaksi-pembelian.index')
                ->with('success', "Pembelian \"{$kode}\" berhasil dihapus dan stok dikembalikan.");

        } catch (\Exception $e) {
            DB::rollBack();

            return back()->with('error', 'Gagal menghapus pembelian: ' . $e->getMessage());
        }
    }

    private function validationMessages(): array
    {
        return [
            'tanggal.required'            => 'Tanggal pembelian wajib diisi.',
            'tanggal.date'                => 'Format tanggal tidak valid.',

            'items.required'              => 'Minimal harus ada 1 produk.',
            'items.array'                 => 'Format produk tidak valid.',
            'items.min'                   => 'Minimal harus ada 1 produk.',

            'items.*.produk_id.required'  => 'Produk wajib dipilih.',
            'items.*.produk_id.exists'    => 'Produk tidak ditemukan.',
            'items.*.qty.required'        => 'Jumlah (qty) wajib diisi.',
            'items.*.qty.integer'         => 'Jumlah (qty) harus angka.',
            'items.*.qty.min'             => 'Jumlah (qty) minimal 1.',
            'items.*.harga_beli.required' => 'Harga beli wajib diisi.',
            'items.*.harga_beli.numeric'  => 'Harga beli harus angka.',
            'items.*.harga_beli.min'      => 'Harga beli tidak boleh negatif.',

            'total_diskon.numeric'        => 'Diskon harus angka.',
            'total_diskon.min'            => 'Diskon tidak boleh negatif.',
        ];
    }

    private function validationAttributes(): array
    {
        return [
            'tanggal'            => 'Tanggal',
            'supplier'           => 'Supplier',
            'catatan'            => 'Catatan',
            'items'              => 'Produk',
            'items.*.produk_id'  => 'Produk',
            'items.*.qty'        => 'Jumlah',
            'items.*.harga_beli' => 'Harga Beli',
            'total_diskon'       => 'Diskon',
        ];
    }
}