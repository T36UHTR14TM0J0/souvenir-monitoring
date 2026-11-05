<?php

namespace App\Http\Controllers;

use App\Models\Produk;
use App\Models\Transaksi;
use App\Models\TransaksiDetail;
use App\Services\ShopeeImportService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class TransaksiPenjualanController extends Controller
{
    public function index(Request $request)
    {
        $query = Transaksi::with(['user', 'details.produk.satuan']);

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('kode_transaksi', 'like', '%'.$request->search.'%')
                    ->orWhere('nama_pelanggan', 'like', '%'.$request->search.'%')
                    ->orWhere('no_pesanan_marketplace', 'like', '%'.$request->search.'%')
                    ->orWhere('no_resi', 'like', '%'.$request->search.'%')
                    ->orWhere('kurir', 'like', '%'.$request->search.'%');
            });
        }

        if ($request->filled('channel')) {
            $query->where('channel', $request->channel);
        }

        if ($request->filled('status_pesanan')) {
            $query->where('status_pesanan', $request->status_pesanan);
        }

        if ($request->filled('dari_tanggal')) {
            $query->whereDate('tanggal', '>=', $request->dari_tanggal);
        }

        if ($request->filled('sampai_tanggal')) {
            $query->whereDate('tanggal', '<=', $request->sampai_tanggal);
        }

        $transaksis = $query->latest('tanggal')
            ->latest('id')
            ->paginate(15)
            ->withQueryString();

        $bulanIniQuery = Transaksi::whereMonth('tanggal', now()->month)
            ->whereYear('tanggal', now()->year);

        $hasBulanIni = (clone $bulanIniQuery)->exists();
        $summaryQuery = $hasBulanIni
            ? $bulanIniQuery
            : Transaksi::where('tanggal', '>=', now()->subDays(30));

        $periodeLabel = $hasBulanIni ? 'Bulan Ini' : '30 Hari Terakhir';

        $totalBulanIni = (clone $summaryQuery)->sum('grand_total');
        $totalAdminMarketplaceBulanIni = (clone $summaryQuery)->sum('biaya_admin_marketplace');
        $totalBersihBulanIni = (clone $summaryQuery)->sum('penghasilan_bersih');
        $totalTransaksi = (clone $summaryQuery)->count();

        // Breakdown per marketplace
        $omzetShopee = (clone $summaryQuery)->where('channel', 'shopee')->sum('grand_total');
        $omzetLazada = (clone $summaryQuery)->where('channel', 'lazada')->sum('grand_total');
        $omzetTiktok = (clone $summaryQuery)->where('channel', 'tiktok')->sum('grand_total');

        $totalHariIni = Transaksi::whereDate('tanggal', today())->sum('grand_total');
        $totalBersihHariIni = Transaksi::whereDate('tanggal', today())->sum('penghasilan_bersih');

        return view('transaksi-penjualan.index', compact(
            'transaksis',
            'periodeLabel',
            'totalBulanIni',
            'totalAdminMarketplaceBulanIni',
            'totalBersihBulanIni',
            'totalTransaksi',
            'omzetShopee',
            'omzetLazada',
            'omzetTiktok',
            'totalHariIni',
            'totalBersihHariIni'
        ));
    }

    public function create()
    {
        $kodeTransaksi = Transaksi::generateKodeTransaksi();
        $produks = Produk::souvenirJadi()
            ->with(['kategori', 'satuan'])
            ->orderBy('nama_produk')
            ->get();

        $produksJson = $produks->map(function ($p) {
            return [
                'id' => $p->id,
                'nama' => $p->nama_produk,
                'kode' => $p->kode_produk,
                'kategori' => $p->kategori->nama_kategori ?? '-',
                'satuan' => $p->satuan->singkatan ?? ($p->satuan->nama_satuan ?? 'pcs'),
                'harga_jual' => (float) $p->harga_setelah_diskon,
                'harga_normal' => (float) $p->harga_jual,
                'tipe_diskon' => $p->tipe_diskon,
                'diskon' => (float) $p->diskon,
                'has_diskon' => (bool) $p->has_diskon,
                'label_diskon' => $p->label_diskon,
                'nilai_potongan' => (float) $p->nilai_potongan,
                'stok' => (int) $p->stok,
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
            'channel' => ['required', 'string', 'in:shopee,lazada,tiktok'],
            'no_pesanan_marketplace' => ['nullable', 'string', 'max:100'],
            'no_resi' => ['nullable', 'string', 'max:100'],
            'kurir' => ['nullable', 'string', 'max:50'],
            'tanggal' => ['required', 'date'],
            'nama_pelanggan' => ['nullable', 'string', 'max:100'],
            'biaya_admin_marketplace' => ['nullable', 'numeric', 'min:0'],
            'status_pesanan' => ['nullable', 'string', 'in:'.implode(',', array_keys(Transaksi::STATUS_PESANAN))],
            'catatan' => ['nullable', 'string', 'max:500'],

            'items' => ['required', 'array', 'min:1'],
            'items.*.produk_id' => ['required', 'integer', 'exists:produks,id'],
            'items.*.qty' => ['required', 'integer', 'min:1'],
            'items.*.harga_satuan' => ['required', 'numeric', 'min:0'],
        ], $this->validationMessages(), $this->validationAttributes());

        $errors = [];

        foreach ($validated['items'] as $index => $item) {
            $produk = Produk::find($item['produk_id']);

            if (! $produk) {
                $errors[] = 'Produk pada baris '.($index + 1).' tidak ditemukan.';

                continue;
            }

            if ($produk->stok < $item['qty']) {
                $errors[] = "Stok \"{$produk->nama_produk}\" tidak cukup. Tersedia: {$produk->stok}, diminta: {$item['qty']}.";
            }
        }

        if (! empty($errors)) {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => implode(' ', $errors),
                ], 422);
            }

            return back()->withInput()->with('error', implode(' ', $errors));
        }

        $totalHarga = 0;
        $totalDiskon = 0;
        foreach ($validated['items'] as $item) {
            $subtotal = $item['qty'] * $item['harga_satuan'];
            $totalHarga += $subtotal;

            $produk = Produk::find($item['produk_id']);
            if ($produk && $produk->has_diskon) {
                $hemat = max(0, $produk->harga_jual - $item['harga_satuan']) * $item['qty'];
                $totalDiskon += $hemat;
            }
        }

        $grandTotal = $totalHarga;
        $channel = $validated['channel'];
        $biayaAdmin = (float) ($validated['biaya_admin_marketplace'] ?? 0);
        $penghasilanBersih = max(0, $grandTotal - $biayaAdmin);
        $statusPesanan = $validated['status_pesanan'] ?? 'selesai';

        DB::beginTransaction();
        try {
            $transaksi = Transaksi::create([
                'channel' => $channel,
                'kode_transaksi' => Transaksi::generateKodeTransaksi(),
                'no_pesanan_marketplace' => $validated['no_pesanan_marketplace'] ?? null,
                'no_resi' => $validated['no_resi'] ?? null,
                'kurir' => $validated['kurir'] ?? null,
                'tanggal' => $validated['tanggal'],
                'nama_pelanggan' => $validated['nama_pelanggan'] ?? null,
                'total_harga' => $totalHarga,
                'total_diskon' => $totalDiskon,
                'grand_total' => $grandTotal,
                'biaya_admin_marketplace' => $biayaAdmin,
                'penghasilan_bersih' => $penghasilanBersih,
                'status_pesanan' => $statusPesanan,
                'metode_bayar' => 'saldo_penjual',
                'catatan' => $validated['catatan'] ?? null,
                'user_id' => auth()->id(),
            ]);

            foreach ($validated['items'] as $item) {
                $produk = Produk::find($item['produk_id']);
                $subtotal = $item['qty'] * $item['harga_satuan'];

                TransaksiDetail::create([
                    'transaksi_id' => $transaksi->id,
                    'produk_id' => $produk->id,
                    'nama_produk' => $produk->nama_produk,
                    'harga_satuan' => $item['harga_satuan'],
                    'qty' => $item['qty'],
                    'subtotal' => $subtotal,
                ]);

                if (! in_array($statusPesanan, ['batal', 'retur'], true)) {
                    $produk->decrement('stok', $item['qty']);
                }
            }

            // Otomatis potong bahan baku jika dibuat dengan status produksi
            $faseProduksi = ['siap_packing', 'perlu_dikirim', 'dikirim', 'selesai'];
            if (in_array($statusPesanan, $faseProduksi, true)) {
                $transaksi->potongBahanBakuOtomatis();
            }

            DB::commit();

            if ($request->ajax() || $request->wantsJson()) {
                $transaksi->load(['details.produk.satuan', 'user']);

                return response()->json([
                    'success' => true,
                    'message' => "Pesanan \"{$transaksi->kode_transaksi}\" berhasil disimpan.",
                    'transaksi' => [
                        'id' => $transaksi->id,
                        'kode_transaksi' => $transaksi->kode_transaksi,
                        'no_pesanan_marketplace' => $transaksi->no_pesanan_marketplace,
                        'channel' => $transaksi->channel_label,
                        'tanggal' => $transaksi->tanggal->translatedFormat('d M Y'),
                        'nama_pelanggan' => $transaksi->nama_pelanggan ?: 'Pembeli Online',
                        'grand_total' => (float) $transaksi->grand_total,
                        'biaya_admin' => (float) $transaksi->biaya_admin_marketplace,
                        'penghasilan_bersih' => (float) $transaksi->penghasilan_bersih,
                        'packing_slip_url' => route('transaksi-penjualan.cetak-struk', $transaksi),
                        'show_url' => route('transaksi-penjualan.show', $transaksi),
                    ],
                ]);
            }

            return redirect()
                ->route('transaksi-penjualan.show', $transaksi)
                ->with('success', "Pesanan {$transaksi->channel_label} \"{$transaksi->kode_transaksi}\" berhasil dicatat.");

        } catch (\Exception $e) {
            DB::rollBack();

            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Gagal menyimpan pesanan: '.$e->getMessage(),
                ], 422);
            }

            return back()->withInput()->with('error', 'Gagal menyimpan pesanan: '.$e->getMessage());
        }
    }

    /**
     * Import laporan pesanan Shopee, Lazada, atau TikTok Shop.
     */
    public function importShopee(Request $request, ShopeeImportService $service)
    {
        $request->validate([
            'file_marketplace' => ['nullable', 'file', 'mimes:xlsx,xls,csv', 'max:10240'],
            'file_shopee' => ['nullable', 'file', 'mimes:xlsx,xls,csv', 'max:10240'],
            'channel' => ['nullable', 'string', 'in:shopee,lazada,tiktok'],
        ], [
            'file_marketplace.mimes' => 'Format file harus berupa Excel (.xlsx, .xls) atau .csv.',
            'file_shopee.mimes' => 'Format file harus berupa Excel (.xlsx, .xls) atau .csv.',
        ]);

        $file = $request->file('file_marketplace') ?? $request->file('file_shopee');

        if (! $file) {
            return back()->with('error', 'Pilih file laporan pesanan (.xlsx, .xls, .csv) terlebih dahulu.');
        }

        try {
            $channel = $request->get('channel');
            $result = $service->import($file, auth()->id(), $channel);

            $channelName = Transaksi::CHANNELS[$result['channel']] ?? ucfirst($result['channel']);
            $msg = "Import {$channelName} berhasil! {$result['imported']} pesanan berhasil dicatat (Omzet: Rp ".number_format($result['total_omzet'], 0, ',', '.').', Biaya Marketplace: Rp '.number_format($result['total_admin'], 0, ',', '.').', Bersih: Rp '.number_format($result['total_bersih'], 0, ',', '.').')';

            if ($result['skipped'] > 0) {
                $msg .= ", {$result['skipped']} pesanan dilewati karena sudah ada.";
            }

            return redirect()
                ->route('transaksi-penjualan.index')
                ->with('success', $msg);

        } catch (\Throwable $e) {
            return back()->with('error', 'Gagal memproses file: '.$e->getMessage());
        }
    }

    public function show(Transaksi $transaksiPenjualan)
    {
        $transaksiPenjualan->load(['details.produk.satuan', 'user']);

        return view('transaksi-penjualan.show', [
            'transaksi' => $transaksiPenjualan,
        ]);
    }

    /**
     * Update status pesanan / alur pengiriman (Admin & Pemilik).
     */
    public function updateStatus(Request $request, Transaksi $transaksiPenjualan)
    {
        $request->validate([
            'status_pesanan' => ['required', 'string', 'in:'.implode(',', array_keys(Transaksi::STATUS_PESANAN))],
            'no_resi' => ['nullable', 'string', 'max:100'],
            'kurir' => ['nullable', 'string', 'max:50'],
        ]);

        $updateData = ['status_pesanan' => $request->status_pesanan];

        if ($request->filled('no_resi')) {
            $updateData['no_resi'] = trim($request->no_resi);
        }
        if ($request->filled('kurir')) {
            $updateData['kurir'] = trim($request->kurir);
        }

        $transaksiPenjualan->update($updateData);

        // Otomatis potong bahan baku jika masuk tahapan siap packing / kirim
        $faseProduksi = ['siap_packing', 'perlu_dikirim', 'dikirim', 'selesai'];
        if (in_array($request->status_pesanan, $faseProduksi, true)) {
            $transaksiPenjualan->potongBahanBakuOtomatis();
        }

        // Kembalikan bahan baku jika pesanan dibatalkan / diretur
        if (in_array($request->status_pesanan, ['batal', 'retur'], true)) {
            $transaksiPenjualan->kembalikanBahanBaku();
        }

        $statusLabel = Transaksi::STATUS_PESANAN[$request->status_pesanan] ?? $request->status_pesanan;

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => "Status pesanan {$transaksiPenjualan->kode_transaksi} diperbarui menjadi: {$statusLabel}.",
                'status' => $request->status_pesanan,
                'status_label' => $statusLabel,
                'status_badge' => $transaksiPenjualan->status_pesanan_badge_config,
            ]);
        }

        return back()->with('success', "Status pesanan {$transaksiPenjualan->kode_transaksi} diperbarui menjadi: {$statusLabel}.");
    }

    /**
     * Cetak Packing Slip & Surat Jalan Pengiriman (menggantikan struk kasir minimarket).
     */
    public function cetakStruk(Transaksi $transaksiPenjualan)
    {
        $transaksiPenjualan->load(['details.produk.satuan', 'user']);

        return view('transaksi-penjualan.packing-slip', [
            'transaksi' => $transaksiPenjualan,
        ]);
    }

    public function destroy(Transaksi $transaksiPenjualan)
    {
        if (! $transaksiPenjualan->tanggal->isToday()) {
            return back()->with('error', 'Transaksi hanya bisa dihapus di hari yang sama.');
        }

        DB::beginTransaction();
        try {
            if (! in_array($transaksiPenjualan->status_pesanan, ['batal', 'retur'], true)) {
                foreach ($transaksiPenjualan->details as $detail) {
                    $produk = Produk::find($detail->produk_id);
                    if ($produk) {
                        $produk->increment('stok', $detail->qty);
                    }
                }
            }

            if ($transaksiPenjualan->bahan_baku_dipotong) {
                $transaksiPenjualan->kembalikanBahanBaku();
            }

            $kode = $transaksiPenjualan->kode_transaksi;
            if ($transaksiPenjualan->bukti_transaksi) {
                Storage::disk('public')->delete($transaksiPenjualan->bukti_transaksi);
            }
            $transaksiPenjualan->delete();

            DB::commit();

            return redirect()
                ->route('transaksi-penjualan.index')
                ->with('success', "Pesanan \"{$kode}\" berhasil dihapus dan stok dikembalikan.");

        } catch (\Exception $e) {
            DB::rollBack();

            return back()->with('error', 'Gagal menghapus transaksi: '.$e->getMessage());
        }
    }

    private function validationMessages(): array
    {
        return [
            'tanggal.required' => 'Tanggal transaksi wajib diisi.',
            'tanggal.date' => 'Format tanggal tidak valid.',
            'channel.required' => 'Pilih saluran marketplace (Shopee, Lazada, atau TikTok).',

            'items.required' => 'Minimal harus ada 1 produk souvenir yang dipesan.',
            'items.array' => 'Format produk tidak valid.',
            'items.min' => 'Minimal harus ada 1 produk souvenir.',

            'items.*.produk_id.required' => 'Produk wajib dipilih.',
            'items.*.produk_id.exists' => 'Produk tidak ditemukan.',
            'items.*.qty.required' => 'Jumlah (qty) wajib diisi.',
            'items.*.qty.integer' => 'Jumlah (qty) harus angka bulat.',
            'items.*.qty.min' => 'Jumlah (qty) minimal 1.',
            'items.*.harga_satuan.required' => 'Harga satuan wajib diisi.',
            'items.*.harga_satuan.numeric' => 'Harga satuan harus angka.',
            'items.*.harga_satuan.min' => 'Harga satuan tidak boleh negatif.',
        ];
    }

    private function validationAttributes(): array
    {
        return [
            'channel' => 'Marketplace',
            'no_pesanan_marketplace' => 'No. Pesanan Marketplace',
            'biaya_admin_marketplace' => 'Biaya Potongan Marketplace',
            'tanggal' => 'Tanggal',
            'nama_pelanggan' => 'Pembeli',
            'catatan' => 'Catatan',
            'items' => 'Produk',
            'items.*.produk_id' => 'Produk',
            'items.*.qty' => 'Jumlah',
            'items.*.harga_satuan' => 'Harga Satuan',
        ];
    }
}
