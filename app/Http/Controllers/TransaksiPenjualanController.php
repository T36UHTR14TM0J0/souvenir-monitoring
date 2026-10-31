<?php

namespace App\Http\Controllers;

use App\Models\Produk;
use App\Models\Transaksi;
use App\Models\TransaksiDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TransaksiPenjualanController extends Controller
{
    public function index(Request $request)
    {
        $query = Transaksi::with(['user', 'details']);

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('kode_transaksi', 'like', '%' . $request->search . '%')
                  ->orWhere('nama_pelanggan', 'like', '%' . $request->search . '%');
            });
        }

        if ($request->filled('dari_tanggal')) {
            $query->whereDate('tanggal', '>=', $request->dari_tanggal);
        }

        if ($request->filled('sampai_tanggal')) {
            $query->whereDate('tanggal', '<=', $request->sampai_tanggal);
        }

        $transaksis = $query->latest('tanggal')
            ->latest('id')
            ->paginate(10)
            ->withQueryString();

        $totalBulanIni  = Transaksi::whereMonth('tanggal', now()->month)
            ->whereYear('tanggal', now()->year)
            ->sum('grand_total');

        $totalTransaksi = Transaksi::whereMonth('tanggal', now()->month)
            ->whereYear('tanggal', now()->year)
            ->count();

        $totalHariIni   = Transaksi::whereDate('tanggal', today())->sum('grand_total');

        return view('transaksi-penjualan.index', compact(
            'transaksis',
            'totalBulanIni',
            'totalTransaksi',
            'totalHariIni'
        ));
    }

    public function create()
    {
        $kodeTransaksi = Transaksi::generateKodeTransaksi();
        $produks       = Produk::with('kategori')
            ->where('stok', '>', 0)
            ->orderBy('nama_produk')
            ->get();

        $produksJson = $produks->map(function ($p) {
            return [
                'id'         => $p->id,
                'nama'       => $p->nama_produk,
                'kode'       => $p->kode_produk,
                'harga_jual' => (float) $p->harga_jual,
                'stok'       => (int) $p->stok,
            ];
        })->values()->toArray();

        return view('transaksi-penjualan.create', compact(
            'kodeTransaksi',
            'produks',
            'produksJson'
        ));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'tanggal'              => ['required', 'date'],
            'nama_pelanggan'       => ['nullable', 'string', 'max:100'],
            'catatan'              => ['nullable', 'string', 'max:500'],

            'items'                => ['required', 'array', 'min:1'],
            'items.*.produk_id'    => ['required', 'integer', 'exists:produks,id'],
            'items.*.qty'          => ['required', 'integer', 'min:1'],
            'items.*.harga_satuan' => ['required', 'numeric', 'min:0'],

            'total_diskon'         => ['nullable', 'numeric', 'min:0'],
        ], $this->validationMessages(), $this->validationAttributes());

        $errors = [];

        foreach ($validated['items'] as $index => $item) {
            $produk = Produk::find($item['produk_id']);

            if (!$produk) {
                $errors[] = "Produk pada baris " . ($index + 1) . " tidak ditemukan.";
                continue;
            }

            if ($produk->stok < $item['qty']) {
                $errors[] = "Stok \"{$produk->nama_produk}\" tidak cukup. Tersedia: {$produk->stok}, diminta: {$item['qty']}.";
            }
        }

        if (!empty($errors)) {
            return back()->withInput()->with('error', implode(' ', $errors));
        }

        $totalHarga = 0;
        foreach ($validated['items'] as $item) {
            $totalHarga += $item['qty'] * $item['harga_satuan'];
        }

        $totalDiskon = $validated['total_diskon'] ?? 0;
        $grandTotal  = max(0, $totalHarga - $totalDiskon);

        DB::beginTransaction();
        try {
            $transaksi = Transaksi::create([
                'kode_transaksi' => Transaksi::generateKodeTransaksi(),
                'tanggal'        => $validated['tanggal'],
                'nama_pelanggan' => $validated['nama_pelanggan'] ?? null,
                'total_harga'    => $totalHarga,
                'total_diskon'   => $totalDiskon,
                'grand_total'    => $grandTotal,
                'metode_bayar'   => 'tunai',
                'catatan'        => $validated['catatan'] ?? null,
                'user_id'        => auth()->id(),
            ]);

            foreach ($validated['items'] as $item) {
                $produk   = Produk::find($item['produk_id']);
                $subtotal = $item['qty'] * $item['harga_satuan'];

                TransaksiDetail::create([
                    'transaksi_id' => $transaksi->id,
                    'produk_id'    => $produk->id,
                    'nama_produk'  => $produk->nama_produk,
                    'harga_satuan' => $item['harga_satuan'],
                    'qty'          => $item['qty'],
                    'subtotal'     => $subtotal,
                ]);

                $produk->decrement('stok', $item['qty']);
            }

            DB::commit();

            return redirect()
                ->route('transaksi-penjualan.show', $transaksi)
                ->with('success', "Transaksi \"{$transaksi->kode_transaksi}\" berhasil disimpan.");

        } catch (\Exception $e) {
            DB::rollBack();

            return back()->withInput()->with('error', 'Gagal menyimpan transaksi: ' . $e->getMessage());
        }
    }

    public function show(Transaksi $transaksiPenjualan)
    {
        $transaksiPenjualan->load(['details.produk', 'user']);

        return view('transaksi-penjualan.show', [
            'transaksi' => $transaksiPenjualan,
        ]);
    }

    public function destroy(Transaksi $transaksiPenjualan)
    {
        if (!$transaksiPenjualan->tanggal->isToday()) {
            return back()->with('error', 'Transaksi hanya bisa dihapus di hari yang sama.');
        }

        DB::beginTransaction();
        try {
            foreach ($transaksiPenjualan->details as $detail) {
                $produk = Produk::find($detail->produk_id);
                if ($produk) {
                    $produk->increment('stok', $detail->qty);
                }
            }

            $kode = $transaksiPenjualan->kode_transaksi;
            $transaksiPenjualan->delete();

            DB::commit();

            return redirect()
                ->route('transaksi-penjualan.index')
                ->with('success', "Transaksi \"{$kode}\" berhasil dihapus dan stok dikembalikan.");

        } catch (\Exception $e) {
            DB::rollBack();

            return back()->with('error', 'Gagal menghapus transaksi: ' . $e->getMessage());
        }
    }

    private function validationMessages(): array
    {
        return [
            'tanggal.required'              => 'Tanggal transaksi wajib diisi.',
            'tanggal.date'                  => 'Format tanggal tidak valid.',

            'items.required'                => 'Minimal harus ada 1 produk.',
            'items.array'                   => 'Format produk tidak valid.',
            'items.min'                     => 'Minimal harus ada 1 produk.',

            'items.*.produk_id.required'    => 'Produk wajib dipilih.',
            'items.*.produk_id.exists'      => 'Produk tidak ditemukan.',
            'items.*.qty.required'          => 'Jumlah (qty) wajib diisi.',
            'items.*.qty.integer'           => 'Jumlah (qty) harus angka.',
            'items.*.qty.min'               => 'Jumlah (qty) minimal 1.',
            'items.*.harga_satuan.required' => 'Harga satuan wajib diisi.',
            'items.*.harga_satuan.numeric'  => 'Harga satuan harus angka.',
            'items.*.harga_satuan.min'      => 'Harga satuan tidak boleh negatif.',

            'total_diskon.numeric'          => 'Diskon harus angka.',
            'total_diskon.min'              => 'Diskon tidak boleh negatif.',
        ];
    }

    private function validationAttributes(): array
    {
        return [
            'tanggal'              => 'Tanggal',
            'nama_pelanggan'       => 'Nama Pelanggan',
            'catatan'              => 'Catatan',
            'items'                => 'Produk',
            'items.*.produk_id'    => 'Produk',
            'items.*.qty'          => 'Jumlah',
            'items.*.harga_satuan' => 'Harga Satuan',
            'total_diskon'         => 'Diskon',
        ];
    }
}