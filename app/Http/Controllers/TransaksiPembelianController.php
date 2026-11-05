<?php

namespace App\Http\Controllers;

use App\Models\Kategori;
use App\Models\Pembelian;
use App\Models\PembelianDetail;
use App\Models\Produk;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class TransaksiPembelianController extends Controller
{
    public function index(Request $request)
    {
        $query = Pembelian::with(['user', 'details']);

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('kode_pembelian', 'like', '%'.$request->search.'%')
                    ->orWhere('supplier', 'like', '%'.$request->search.'%')
                    ->orWhere('no_invoice_online', 'like', '%'.$request->search.'%')
                    ->orWhere('no_resi_kargo', 'like', '%'.$request->search.'%');
            });
        }

        if ($request->filled('platform')) {
            $query->where('platform', $request->platform);
        }

        if ($request->filled('status_pembelian')) {
            $query->where('status_pembelian', $request->status_pembelian);
        }

        if ($request->filled('dari_tanggal')) {
            $query->whereDate('tanggal', '>=', $request->dari_tanggal);
        }

        if ($request->filled('sampai_tanggal')) {
            $query->whereDate('tanggal', '<=', $request->sampai_tanggal);
        }

        $sort = $request->input('sort', 'latest');
        match ($sort) {
            'oldest' => $query->oldest('tanggal')->oldest('id'),
            'nominal_desc' => $query->orderByDesc('grand_total'),
            'nominal_asc' => $query->orderBy('grand_total'),
            default => $query->latest('tanggal')->latest('id'),
        };

        $pembelians = $query->paginate(10)
            ->withQueryString();

        $totalTransaksi = Pembelian::count();
        $totalNominal = (float) Pembelian::sum('grand_total');
        $totalDipesan = Pembelian::where('status_pembelian', 'dipesan')->count();
        $totalDikirim = Pembelian::where('status_pembelian', 'dikirim')->count();
        $totalDiterima = Pembelian::where('status_pembelian', 'diterima')->count();

        $totalBulanIni = Pembelian::whereMonth('tanggal', now()->month)
            ->whereYear('tanggal', now()->year)
            ->sum('grand_total');

        $totalPembelian = Pembelian::whereMonth('tanggal', now()->month)
            ->whereYear('tanggal', now()->year)
            ->count();

        $totalHariIni = Pembelian::whereDate('tanggal', today())->sum('grand_total');

        return view('transaksi-pembelian.index', compact(
            'pembelians',
            'totalTransaksi',
            'totalNominal',
            'totalDipesan',
            'totalDikirim',
            'totalDiterima',
            'totalBulanIni',
            'totalPembelian',
            'totalHariIni'
        ));
    }

    public function create()
    {
        $kodePembelian = Pembelian::generateKodePembelian();

        // Hanya muat Bahan Baku & Material Packaging
        $produks = Produk::bahanBaku()
            ->with(['kategori', 'satuan'])
            ->orderBy('nama_produk')
            ->get();

        if ($produks->isEmpty()) {
            $produks = Produk::with(['kategori', 'satuan'])->orderBy('nama_produk')->get();
        }

        $produksJson = $produks->map(function ($p) {
            return [
                'id' => $p->id,
                'nama' => $p->nama_produk,
                'kode' => $p->kode_produk,
                'satuan' => $p->satuan->singkatan ?? 'pcs',
                'harga_beli' => (float) $p->harga_beli,
                'stok' => (int) $p->stok,
                'kategori_id' => $p->kategori_id,
            ];
        })->values()->toArray();

        $kategoris = Kategori::withCount('produks')
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
            'tanggal' => ['required', 'date'],
            'supplier' => ['required', 'string', 'max:150'],
            'platform' => ['required', 'string', 'in:shopee,tokopedia,tiktok,lazada,website,offline'],
            'no_invoice_online' => ['nullable', 'string', 'max:100'],
            'ongkir' => ['nullable', 'numeric', 'min:0'],
            'status_pembelian' => ['required', 'string', 'in:dipesan,dikirim,diterima'],
            'kurir_kargo' => ['nullable', 'string', 'max:50'],
            'no_resi_kargo' => ['nullable', 'string', 'max:100'],
            'bukti_transaksi' => ['nullable', 'file', 'mimes:jpeg,jpg,png,webp,pdf', 'max:5120'],
            'catatan' => ['nullable', 'string', 'max:500'],

            'items' => ['required', 'array', 'min:1'],
            'items.*.produk_id' => ['required', 'integer', 'exists:produks,id'],
            'items.*.qty' => ['required', 'integer', 'min:1'],
            'items.*.harga_beli' => ['required', 'numeric', 'min:0'],

            'total_diskon' => ['nullable', 'numeric', 'min:0'],
        ], $this->validationMessages(), $this->validationAttributes());

        $totalHarga = 0;
        foreach ($validated['items'] as $item) {
            $totalHarga += $item['qty'] * $item['harga_beli'];
        }

        $totalDiskon = (float) ($validated['total_diskon'] ?? 0);
        $ongkir = (float) ($validated['ongkir'] ?? 0);
        $grandTotal = max(0, ($totalHarga + $ongkir) - $totalDiskon);

        $buktiPath = null;
        if ($request->hasFile('bukti_transaksi')) {
            $buktiPath = $request->file('bukti_transaksi')->store('bukti-transaksi/pembelian', 'public');
        }

        DB::beginTransaction();
        try {
            $pembelian = Pembelian::create([
                'kode_pembelian' => Pembelian::generateKodePembelian(),
                'tanggal' => $validated['tanggal'],
                'supplier' => $validated['supplier'],
                'platform' => $validated['platform'],
                'no_invoice_online' => $validated['no_invoice_online'] ?? null,
                'total_harga' => $totalHarga,
                'total_diskon' => $totalDiskon,
                'ongkir' => $ongkir,
                'grand_total' => $grandTotal,
                'status_pembelian' => $validated['status_pembelian'],
                'kurir_kargo' => $validated['kurir_kargo'] ?? null,
                'no_resi_kargo' => $validated['no_resi_kargo'] ?? null,
                'catatan' => $validated['catatan'] ?? null,
                'bukti_transaksi' => $buktiPath,
                'user_id' => auth()->id(),
            ]);

            foreach ($validated['items'] as $item) {
                $produk = Produk::find($item['produk_id']);
                $subtotal = $item['qty'] * $item['harga_beli'];

                PembelianDetail::create([
                    'pembelian_id' => $pembelian->id,
                    'produk_id' => $produk->id,
                    'nama_produk' => $produk->nama_produk,
                    'harga_beli' => $item['harga_beli'],
                    'qty' => $item['qty'],
                    'subtotal' => $subtotal,
                ]);

                // Jika status langsung "diterima", stok bahan baku langsung bertambah
                if ($validated['status_pembelian'] === 'diterima') {
                    $produk->increment('stok', $item['qty']);
                }

                $produk->update(['harga_beli' => $item['harga_beli']]);
            }

            DB::commit();

            return redirect()
                ->route('transaksi-pembelian.show', $pembelian)
                ->with('success', "Pembelian bahan baku \"{$pembelian->kode_pembelian}\" berhasil disimpan.");

        } catch (\Exception $e) {
            DB::rollBack();

            if ($buktiPath) {
                Storage::disk('public')->delete($buktiPath);
            }

            return back()->withInput()->with('error', 'Gagal menyimpan pembelian: '.$e->getMessage());
        }
    }

    public function konfirmasiDiterima(Pembelian $transaksiPembelian)
    {
        if ($transaksiPembelian->status_pembelian === 'diterima') {
            return back()->with('info', 'Paket pembelian bahan baku ini sudah berstatus diterima sebelumnya.');
        }

        DB::beginTransaction();
        try {
            foreach ($transaksiPembelian->details as $item) {
                $produk = Produk::find($item->produk_id);
                if ($produk) {
                    $produk->increment('stok', $item->qty);
                }
            }

            $transaksiPembelian->update(['status_pembelian' => 'diterima']);

            DB::commit();

            return back()->with('success', "Paket bahan baku {$transaksiPembelian->kode_pembelian} berhasil dikonfirmasi diterima di workshop. Stok gudang otomatis bertambah.");
        } catch (\Throwable $e) {
            DB::rollBack();

            return back()->with('error', 'Gagal mengonfirmasi penerimaan: '.$e->getMessage());
        }
    }

    public function updateStatus(Request $request, Pembelian $transaksiPembelian)
    {
        $validated = $request->validate([
            'status_pembelian' => ['required', 'string', 'in:dipesan,dikirim,diterima'],
            'kurir_kargo' => ['nullable', 'string', 'max:50'],
            'no_resi_kargo' => ['nullable', 'string', 'max:100'],
        ]);

        $statusLama = $transaksiPembelian->status_pembelian;
        $statusBaru = $validated['status_pembelian'];

        DB::beginTransaction();
        try {
            // Jika status baru DITERIMA dan sebelumnya belum diterima: stok masuk gudang
            if ($statusBaru === 'diterima' && $statusLama !== 'diterima') {
                foreach ($transaksiPembelian->details as $item) {
                    $produk = Produk::find($item->produk_id);
                    if ($produk) {
                        $produk->increment('stok', $item->qty);
                    }
                }
            }

            // Jika status sebelumnya DITERIMA tapi diubah mundur ke dipesan/dikirim: kurangi kembali stok
            if ($statusLama === 'diterima' && $statusBaru !== 'diterima') {
                foreach ($transaksiPembelian->details as $item) {
                    $produk = Produk::find($item->produk_id);
                    if ($produk) {
                        $produk->decrement('stok', $item->qty);
                    }
                }
            }

            $updateData = ['status_pembelian' => $statusBaru];
            if ($request->filled('kurir_kargo')) {
                $updateData['kurir_kargo'] = $validated['kurir_kargo'];
            }
            if ($request->filled('no_resi_kargo')) {
                $updateData['no_resi_kargo'] = $validated['no_resi_kargo'];
            }

            $transaksiPembelian->update($updateData);

            DB::commit();

            $pesan = match ($statusBaru) {
                'dipesan' => "Status pembelian {$transaksiPembelian->kode_pembelian} diubah menjadi Dipesan ke supplier online.",
                'dikirim' => "Status pembelian {$transaksiPembelian->kode_pembelian} diubah menjadi Dikirim (dalam pengiriman kargo).",
                'diterima' => "Status pembelian {$transaksiPembelian->kode_pembelian} berhasil dikonfirmasi Diterima di workshop. Stok gudang bertambah.",
            };

            return back()->with('success', $pesan);
        } catch (\Throwable $e) {
            DB::rollBack();

            return back()->with('error', 'Gagal memperbarui status pengadaan: '.$e->getMessage());
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
        if (! $transaksiPembelian->tanggal->isToday()) {
            return back()->with('error', 'Pembelian hanya bisa dihapus di hari yang sama.');
        }

        DB::beginTransaction();
        try {
            // Hanya kurangi stok jika barang sebelumnya sudah diterima di gudang
            if ($transaksiPembelian->status_pembelian === 'diterima') {
                foreach ($transaksiPembelian->details as $item) {
                    $produk = Produk::find($item->produk_id);
                    if ($produk) {
                        $produk->decrement('stok', $item->qty);
                    }
                }
            }

            $kode = $transaksiPembelian->kode_pembelian;
            if ($transaksiPembelian->bukti_transaksi) {
                Storage::disk('public')->delete($transaksiPembelian->bukti_transaksi);
            }
            $transaksiPembelian->delete();

            DB::commit();

            return redirect()
                ->route('transaksi-pembelian.index')
                ->with('success', "Pembelian \"{$kode}\" berhasil dihapus.");

        } catch (\Exception $e) {
            DB::rollBack();

            return back()->with('error', 'Gagal menghapus pembelian: '.$e->getMessage());
        }
    }

    private function validationMessages(): array
    {
        return [
            'tanggal.required' => 'Tanggal pembelian wajib diisi.',
            'supplier.required' => 'Nama toko online / supplier wajib diisi.',
            'platform.required' => 'Pilih platform pembelian online.',
            'items.required' => 'Pilih minimal satu bahan baku.',
            'items.min' => 'Pilih minimal satu bahan baku.',
            'items.*.qty.min' => 'Jumlah beli minimal 1.',
            'bukti_transaksi.max' => 'Ukuran file bukti maksimal 5MB.',
        ];
    }

    private function validationAttributes(): array
    {
        return [
            'tanggal' => 'tanggal pembelian',
            'supplier' => 'nama supplier / toko online',
            'platform' => 'platform belanja',
            'ongkir' => 'ongkos kirim kargo',
            'items' => 'daftar bahan baku',
            'bukti_transaksi' => 'bukti transaksi / invoice',
        ];
    }
}
