<?php

namespace App\Http\Controllers;

use App\Models\PemakaianBahan;
use App\Models\PenerimaanProduk;
use App\Models\PenerimaanProdukDetail;
use App\Models\Produk;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class PenerimaanProdukController extends Controller
{
    public function index(Request $request)
    {
        $query = PenerimaanProduk::with(['user', 'details.produk.satuan']);

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('kode_penerimaan', 'like', '%'.$request->search.'%')
                    ->orWhere('nama_petugas', 'like', '%'.$request->search.'%')
                    ->orWhere('no_referensi_pemakaian', 'like', '%'.$request->search.'%')
                    ->orWhere('catatan', 'like', '%'.$request->search.'%');
            });
        }

        if ($request->filled('tipe_produksi')) {
            $query->where('tipe_produksi', $request->tipe_produksi);
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
            'qty_desc' => $query->orderBy('total_qty_bagus', 'desc')->latest('id'),
            'qty_asc' => $query->orderBy('total_qty_bagus', 'asc')->latest('id'),
            'reject_desc' => $query->orderBy('total_qty_reject', 'desc')->latest('id'),
            default => $query->latest('tanggal')->latest('id'),
        };

        $penerimaans = $query->paginate(10)->withQueryString();

        // KPI Metrik Hasil Produksi
        $totalBatch = PenerimaanProduk::count();
        $totalProdukMasuk = (float) PenerimaanProduk::sum('total_qty_bagus');
        $totalProdukReject = (float) PenerimaanProduk::sum('total_qty_reject');

        $totalBulanIni = (float) PenerimaanProduk::whereMonth('tanggal', now()->month)
            ->whereYear('tanggal', now()->year)
            ->sum('total_qty_bagus');

        $batchBulanIni = PenerimaanProduk::whereMonth('tanggal', now()->month)
            ->whereYear('tanggal', now()->year)
            ->count();

        return view('penerimaan-produk.index', compact(
            'penerimaans',
            'totalBatch',
            'totalProdukMasuk',
            'totalProdukReject',
            'totalBulanIni',
            'batchBulanIni'
        ));
    }

    public function create()
    {
        $kodePenerimaan = PenerimaanProduk::generateKodePenerimaan();

        // Ambil produk jadi (souvenir jadi)
        $produks = Produk::where('jenis', 'souvenir_jadi')
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
                'stok' => (int) $p->stok,
                'kategori' => $p->kategori->nama_kategori ?? '-',
            ];
        });

        // Ambil riwayat pemakaian bahan baku terakhir untuk opsi tautan referensi
        $pemakaianList = PemakaianBahan::latest('tanggal')->latest('id')->limit(20)->get();

        return view('penerimaan-produk.create', compact('kodePenerimaan', 'produks', 'produksJson', 'pemakaianList'));
    }

    public function store(Request $request)
    {
        if (! $request->filled('nama_petugas') && $request->filled('nama_penanggung_jawab')) {
            $request->merge(['nama_petugas' => $request->nama_penanggung_jawab]);
        }

        $request->validate([
            'tanggal' => ['required', 'date'],
            'nama_petugas' => ['required', 'string', 'max:150'],
            'tipe_produksi' => ['required', 'string', 'max:50'],
            'no_referensi_pemakaian' => ['nullable', 'string', 'max:100'],
            'catatan' => ['nullable', 'string'],
            'bukti_dokumen' => ['nullable', 'file', 'mimes:jpg,jpeg,png,webp,pdf', 'max:5120'],
            'items' => ['required', 'array', 'min:1'],
            'items.*.produk_id' => ['required', 'integer', 'exists:produks,id'],
            'items.*.qty_bagus' => ['required', 'numeric', 'min:0.01'],
            'items.*.qty_reject' => ['nullable', 'numeric', 'min:0'],
            'items.*.keterangan' => ['nullable', 'string', 'max:255'],
        ], $this->validationMessages(), $this->validationAttributes());

        DB::beginTransaction();
        try {
            // 1. Upload Bukti jika ada
            $buktiPath = null;
            if ($request->hasFile('bukti_dokumen')) {
                $buktiPath = $request->file('bukti_dokumen')->store('bukti-hasil-produksi', 'public');
            }

            // 2. Buat Master Penerimaan Hasil Produksi
            $penerimaan = PenerimaanProduk::create([
                'kode_penerimaan' => PenerimaanProduk::generateKodePenerimaan(),
                'tanggal' => $request->tanggal,
                'nama_petugas' => $request->nama_petugas,
                'tipe_produksi' => $request->tipe_produksi,
                'no_referensi_pemakaian' => $request->no_referensi_pemakaian,
                'total_item' => count($request->items),
                'total_qty_bagus' => 0,
                'total_qty_reject' => 0,
                'catatan' => $request->catatan,
                'bukti_dokumen' => $buktiPath,
                'user_id' => auth()->id() ?? 1,
            ]);

            $totalQtyBagus = 0;
            $totalQtyReject = 0;

            // 3. Simpan Rincian & Tambah Stok Etalase Produk Jadi
            foreach ($request->items as $item) {
                $produk = Produk::with('satuan')->find($item['produk_id']);
                $qtyBagus = (float) $item['qty_bagus'];
                $qtyReject = isset($item['qty_reject']) ? (float) $item['qty_reject'] : 0;

                PenerimaanProdukDetail::create([
                    'penerimaan_produk_id' => $penerimaan->id,
                    'produk_id' => $produk->id,
                    'nama_produk' => $produk->nama_produk,
                    'qty_bagus' => $qtyBagus,
                    'qty_reject' => $qtyReject,
                    'satuan' => $produk->satuan->singkatan ?? 'pcs',
                    'keterangan' => $item['keterangan'] ?? null,
                ]);

                // Stok produk jadi di etalase bertambah otomatis!
                $produk->increment('stok', $qtyBagus);

                $totalQtyBagus += $qtyBagus;
                $totalQtyReject += $qtyReject;
            }

            $penerimaan->update([
                'total_qty_bagus' => $totalQtyBagus,
                'total_qty_reject' => $totalQtyReject,
            ]);

            DB::commit();

            return redirect()
                ->route('penerimaan-produk.show', $penerimaan)
                ->with('success', "Penerimaan hasil produksi \"{$penerimaan->kode_penerimaan}\" berhasil dicatat. Stok etalase bertambah +{$totalQtyBagus} unit.");
        } catch (\Throwable $e) {
            DB::rollBack();

            if (isset($buktiPath)) {
                Storage::disk('public')->delete($buktiPath);
            }

            return back()->withInput()->with('error', 'Gagal mencatat hasil produksi: '.$e->getMessage());
        }
    }

    public function show(PenerimaanProduk $penerimaanProduk)
    {
        $penerimaanProduk->load(['details.produk.satuan', 'user']);

        return view('penerimaan-produk.show', compact('penerimaanProduk'));
    }

    public function cetak(PenerimaanProduk $penerimaanProduk)
    {
        $penerimaanProduk->load(['details.produk.satuan', 'user']);

        return view('penerimaan-produk.cetak', compact('penerimaanProduk'));
    }

    public function destroy(PenerimaanProduk $penerimaanProduk)
    {
        DB::beginTransaction();
        try {
            // Kurangi kembali stok yang sebelumnya bertambah
            foreach ($penerimaanProduk->details as $detail) {
                $produk = Produk::find($detail->produk_id);
                if ($produk) {
                    $produk->decrement('stok', $detail->qty_bagus);
                }
            }

            $kode = $penerimaanProduk->kode_penerimaan;

            if ($penerimaanProduk->bukti_dokumen) {
                Storage::disk('public')->delete($penerimaanProduk->bukti_dokumen);
            }

            $penerimaanProduk->delete();

            DB::commit();

            return redirect()
                ->route('penerimaan-produk.index')
                ->with('success', "Catatan hasil produksi \"{$kode}\" berhasil dibatalkan. Kuantitas stok etalase telah dikurangi kembali.");
        } catch (\Throwable $e) {
            DB::rollBack();

            return back()->with('error', 'Gagal membatalkan hasil produksi: '.$e->getMessage());
        }
    }

    private function validationMessages(): array
    {
        return [
            'tanggal.required' => 'Tanggal selesai produksi wajib diisi.',
            'nama_petugas.required' => 'Nama operator / QC penanggung jawab wajib diisi.',
            'tipe_produksi.required' => 'Pilih peruntukan hasil produksi.',
            'items.required' => 'Pilih minimal satu produk hasil produksi.',
            'items.min' => 'Pilih minimal satu produk hasil produksi.',
            'items.*.qty_bagus.min' => 'Jumlah produk jadi minimal 0.01.',
            'bukti_dokumen.max' => 'Ukuran file bukti maksimal 5MB.',
        ];
    }

    private function validationAttributes(): array
    {
        return [
            'tanggal' => 'tanggal selesai',
            'nama_petugas' => 'penanggung jawab produksi',
            'tipe_produksi' => 'tipe produksi',
            'items' => 'daftar produk hasil produksi',
            'bukti_dokumen' => 'bukti serah terima',
        ];
    }
}
