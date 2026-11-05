<?php

namespace App\Http\Controllers;

use App\Models\PemakaianBahan;
use App\Models\PemakaianBahanDetail;
use App\Models\Produk;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class PemakaianBahanController extends Controller
{
    public function index(Request $request)
    {
        $query = PemakaianBahan::with(['user', 'details.produk.satuan']);

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('kode_pemakaian', 'like', '%'.$request->search.'%')
                    ->orWhere('nama_operator', 'like', '%'.$request->search.'%')
                    ->orWhere('keperluan', 'like', '%'.$request->search.'%');
            });
        }

        if ($request->filled('nama_operator')) {
            $query->where('nama_operator', $request->nama_operator);
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
            'qty_desc' => $query->orderByDesc('total_kuantitas'),
            'qty_asc' => $query->orderBy('total_kuantitas'),
            'item_desc' => $query->orderByDesc('total_item'),
            default => $query->latest('tanggal')->latest('id'),
        };

        $pemakaians = $query->paginate(10)
            ->withQueryString();

        // KPI Metrik Pengambilan Bahan
        $totalTransaksi = PemakaianBahan::count();
        $totalKuantitasKeluar = (float) PemakaianBahan::sum('total_kuantitas');

        $totalBulanIni = PemakaianBahan::whereMonth('tanggal', now()->month)
            ->whereYear('tanggal', now()->year)
            ->count();

        $kuantitasBulanIni = (float) PemakaianBahan::whereMonth('tanggal', now()->month)
            ->whereYear('tanggal', now()->year)
            ->sum('total_kuantitas');

        $totalHariIni = PemakaianBahan::whereDate('tanggal', today())->count();

        // Daftar operator unik untuk opsi filter dropdown
        $operatorList = PemakaianBahan::distinct()->pluck('nama_operator')->filter()->values();

        return view('pemakaian-bahan.index', compact(
            'pemakaians',
            'totalTransaksi',
            'totalKuantitasKeluar',
            'totalBulanIni',
            'kuantitasBulanIni',
            'totalHariIni',
            'operatorList'
        ));
    }

    public function create()
    {
        $kodePemakaian = PemakaianBahan::generateKodePemakaian();

        // Ambil material mentah (bahan baku)
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
                'stok' => (float) $p->stok,
                'kategori' => $p->kategori->nama_kategori ?? '-',
            ];
        });

        return view('pemakaian-bahan.create', compact('kodePemakaian', 'produks', 'produksJson'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'tanggal' => ['required', 'date'],
            'nama_operator' => ['required', 'string', 'max:150'],
            'keperluan' => ['required', 'string', 'max:255'],
            'catatan' => ['nullable', 'string'],
            'bukti_pengambilan' => ['nullable', 'file', 'mimes:jpg,jpeg,png,webp,pdf', 'max:5120'],
            'items' => ['required', 'array', 'min:1'],
            'items.*.produk_id' => ['required', 'integer', 'exists:produks,id'],
            'items.*.qty' => ['required', 'numeric', 'min:0.01'],
            'items.*.keterangan' => ['nullable', 'string', 'max:255'],
        ], $this->validationMessages(), $this->validationAttributes());

        // 1. Validasi kecukupan stok fisik bahan baku sebelum membuka transaksi database
        foreach ($request->items as $item) {
            $produk = Produk::with('satuan')->find($item['produk_id']);
            $qtyMinta = (float) $item['qty'];

            if (! $produk) {
                return back()->withInput()->with('error', 'Data bahan baku tidak ditemukan.');
            }

            if ($produk->stok < $qtyMinta) {
                $satuan = $produk->satuan->singkatan ?? 'unit';

                return back()->withInput()->with('error', "Stok \"{$produk->nama_produk}\" tidak mencukupi di gudang (Tersedia: {$produk->stok} {$satuan}, diminta: {$qtyMinta}).");
            }
        }

        DB::beginTransaction();
        try {
            // 2. Upload Bukti Pengambilan (jika ada)
            $buktiPath = null;
            if ($request->hasFile('bukti_pengambilan')) {
                $buktiPath = $request->file('bukti_pengambilan')->store('bukti-pemakaian-bahan', 'public');
            }

            // 3. Buat Master Pemakaian Bahan
            $pemakaian = PemakaianBahan::create([
                'kode_pemakaian' => PemakaianBahan::generateKodePemakaian(),
                'tanggal' => $request->tanggal,
                'nama_operator' => $request->nama_operator,
                'keperluan' => $request->keperluan,
                'total_item' => count($request->items),
                'total_kuantitas' => 0,
                'catatan' => $request->catatan,
                'bukti_pengambilan' => $buktiPath,
                'user_id' => auth()->id() ?? 1,
            ]);

            $totalKuantitas = 0;

            // 4. Buat Rincian & Potong Stok Gudang
            foreach ($request->items as $item) {
                $produk = Produk::with('satuan')->find($item['produk_id']);
                $qty = (float) $item['qty'];

                PemakaianBahanDetail::create([
                    'pemakaian_bahan_id' => $pemakaian->id,
                    'produk_id' => $produk->id,
                    'nama_bahan' => $produk->nama_produk,
                    'qty' => $qty,
                    'satuan' => $produk->satuan->singkatan ?? 'pcs',
                    'keterangan' => $item['keterangan'] ?? null,
                ]);

                // Potong stok fisik gudang
                $produk->decrement('stok', $qty);
                $totalKuantitas += $qty;
            }

            $pemakaian->update([
                'total_kuantitas' => $totalKuantitas,
            ]);

            DB::commit();

            return redirect()
                ->route('pemakaian-bahan.show', $pemakaian)
                ->with('success', "Pengambilan bahan baku \"{$pemakaian->kode_pemakaian}\" oleh {$pemakaian->nama_operator} berhasil dicatat. Stok fisik gudang otomatis terpotong.");
        } catch (\Throwable $e) {
            DB::rollBack();

            if (isset($buktiPath)) {
                Storage::disk('public')->delete($buktiPath);
            }

            return back()->withInput()->with('error', 'Gagal mencatat pemakaian bahan baku: '.$e->getMessage());
        }
    }

    public function show(PemakaianBahan $pemakaianBahan)
    {
        $pemakaianBahan->load(['details.produk.satuan', 'user']);

        return view('pemakaian-bahan.show', compact('pemakaianBahan'));
    }

    public function cetak(PemakaianBahan $pemakaianBahan)
    {
        $pemakaianBahan->load(['details.produk.satuan', 'user']);

        return view('pemakaian-bahan.cetak', compact('pemakaianBahan'));
    }

    public function destroy(PemakaianBahan $pemakaianBahan)
    {
        DB::beginTransaction();
        try {
            // Kembalikan kuantitas stok yang sebelumnya diambil
            foreach ($pemakaianBahan->details as $detail) {
                $produk = Produk::find($detail->produk_id);
                if ($produk) {
                    $produk->increment('stok', $detail->qty);
                }
            }

            $kode = $pemakaianBahan->kode_pemakaian;

            if ($pemakaianBahan->bukti_pengambilan) {
                Storage::disk('public')->delete($pemakaianBahan->bukti_pengambilan);
            }

            $pemakaianBahan->delete();

            DB::commit();

            return redirect()
                ->route('pemakaian-bahan.index')
                ->with('success', "Catatan pemakaian bahan \"{$kode}\" berhasil dibatalkan. Kuantitas stok otomatis dikembalikan ke gudang.");
        } catch (\Throwable $e) {
            DB::rollBack();

            return back()->with('error', 'Gagal membatalkan pemakaian bahan: '.$e->getMessage());
        }
    }

    private function validationMessages(): array
    {
        return [
            'tanggal.required' => 'Tanggal pengambilan wajib diisi.',
            'nama_operator.required' => 'Nama operator / pengambil wajib diisi.',
            'keperluan.required' => 'Keperluan atau peruntukan pesanan wajib diisi.',
            'items.required' => 'Pilih minimal satu bahan baku yang diambil.',
            'items.min' => 'Pilih minimal satu bahan baku yang diambil.',
            'items.*.qty.min' => 'Jumlah pengambilan minimal 0.01.',
            'bukti_pengambilan.max' => 'Ukuran file bukti maksimal 5MB.',
        ];
    }

    private function validationAttributes(): array
    {
        return [
            'tanggal' => 'tanggal pengambilan',
            'nama_operator' => 'nama operator',
            'keperluan' => 'keperluan produksi',
            'items' => 'daftar bahan baku',
            'bukti_pengambilan' => 'bukti serah terima',
        ];
    }
}
