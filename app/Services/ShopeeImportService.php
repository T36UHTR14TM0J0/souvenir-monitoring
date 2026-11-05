<?php

namespace App\Services;

use App\Models\Kategori;
use App\Models\Produk;
use App\Models\Satuan;
use App\Models\Transaksi;
use App\Models\TransaksiDetail;
use Carbon\Carbon;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Shared\Date;

class ShopeeImportService
{
    /**
     * Import file Excel / CSV laporan pesanan dari Shopee, Lazada, atau TikTok Shop.
     */
    public function import(UploadedFile $file, int $userId, ?string $channel = null): array
    {
        $filePath = $file->getRealPath();
        $spreadsheet = IOFactory::load($filePath);
        $worksheet = $spreadsheet->getActiveSheet();
        $rows = $worksheet->toArray(null, true, true, true);

        if (empty($rows)) {
            throw new \Exception('File Excel kosong atau tidak dapat dibaca.');
        }

        // Cari baris header (biasanya baris 1 s/d 5)
        $headerRowIndex = null;
        $columnMapping = [];

        foreach ($rows as $index => $row) {
            $mapped = $this->detectHeaders($row);
            if (! empty($mapped['no_pesanan'])) {
                $headerRowIndex = $index;
                $columnMapping = $mapped;
                break;
            }
        }

        if (! $headerRowIndex) {
            throw new \Exception('Kolom No. Pesanan / Order ID tidak ditemukan dalam file. Pastikan Anda mengunggah file laporan pesanan dari Seller Centre (Shopee, Lazada, atau TikTok Shop).');
        }

        // Deteksi channel jika tidak dipilih
        $detectedChannel = $channel ?: $this->detectChannel($columnMapping);

        // Kumpulkan data pesanan dan kelompokkan berdasarkan no_pesanan
        $ordersGrouped = [];
        $dataRows = array_slice($rows, $headerRowIndex, null, true);

        foreach ($dataRows as $rowIndex => $row) {
            $noPesanan = trim((string) ($row[$columnMapping['no_pesanan']] ?? ''));
            if ($noPesanan === '') {
                continue;
            }

            // Ambil item
            $sku = ! empty($columnMapping['sku']) ? trim((string) ($row[$columnMapping['sku']] ?? '')) : '';
            $namaProduk = ! empty($columnMapping['nama_produk']) ? trim((string) ($row[$columnMapping['nama_produk']] ?? '')) : 'Produk Souvenir';
            $variasi = ! empty($columnMapping['variasi']) ? trim((string) ($row[$columnMapping['variasi']] ?? '')) : '';
            $qty = ! empty($columnMapping['qty']) ? (int) $this->parseNumber($row[$columnMapping['qty']] ?? 1) : 1;
            if ($qty < 1) {
                $qty = 1;
            }

            $hargaSatuan = ! empty($columnMapping['harga_satuan']) ? $this->parseNumber($row[$columnMapping['harga_satuan']] ?? 0) : 0;
            $subtotal = ! empty($columnMapping['subtotal']) ? $this->parseNumber($row[$columnMapping['subtotal']] ?? 0) : ($hargaSatuan * $qty);
            if ($hargaSatuan <= 0 && $qty > 0 && $subtotal > 0) {
                $hargaSatuan = round($subtotal / $qty, 2);
            }

            $tanggalRaw = ! empty($columnMapping['tanggal']) ? ($row[$columnMapping['tanggal']] ?? null) : null;
            $tanggal = $this->parseDate($tanggalRaw);

            $statusRaw = ! empty($columnMapping['status']) ? trim((string) ($row[$columnMapping['status']] ?? '')) : 'Selesai';
            $statusPesanan = $this->normalizeStatus($statusRaw);

            $pelanggan = ! empty($columnMapping['pembeli']) ? trim((string) ($row[$columnMapping['pembeli']] ?? '')) : 'Pembeli Online';
            $resi = ! empty($columnMapping['resi']) ? trim((string) ($row[$columnMapping['resi']] ?? '')) : null;
            $kurir = ! empty($columnMapping['kurir']) ? trim((string) ($row[$columnMapping['kurir']] ?? '')) : null;

            $totalPembayaran = ! empty($columnMapping['total_pembayaran']) ? $this->parseNumber($row[$columnMapping['total_pembayaran']] ?? 0) : 0;
            $biayaAdmin = ! empty($columnMapping['biaya_admin']) ? abs($this->parseNumber($row[$columnMapping['biaya_admin']] ?? 0)) : 0;
            $penghasilanBersih = ! empty($columnMapping['penghasilan_bersih']) ? $this->parseNumber($row[$columnMapping['penghasilan_bersih']] ?? 0) : 0;

            if (! isset($ordersGrouped[$noPesanan])) {
                $ordersGrouped[$noPesanan] = [
                    'channel' => $detectedChannel,
                    'no_pesanan' => $noPesanan,
                    'tanggal' => $tanggal,
                    'pelanggan' => $pelanggan ?: 'Pembeli Online',
                    'status_pesanan' => $statusPesanan,
                    'resi' => $resi,
                    'kurir' => $kurir,
                    'total_pembayaran' => $totalPembayaran,
                    'biaya_admin' => $biayaAdmin,
                    'penghasilan_bersih' => $penghasilanBersih,
                    'items' => [],
                ];
            } else {
                if (! $ordersGrouped[$noPesanan]['resi'] && $resi) {
                    $ordersGrouped[$noPesanan]['resi'] = $resi;
                }
                if (! $ordersGrouped[$noPesanan]['kurir'] && $kurir) {
                    $ordersGrouped[$noPesanan]['kurir'] = $kurir;
                }
            }

            $fullName = $namaProduk;
            if ($variasi !== '' && ! str_contains($fullName, $variasi)) {
                $fullName .= ' ('.$variasi.')';
            }

            $ordersGrouped[$noPesanan]['items'][] = [
                'sku' => $sku,
                'nama' => $fullName,
                'qty' => $qty,
                'harga_satuan' => $hargaSatuan,
                'subtotal' => $subtotal > 0 ? $subtotal : ($hargaSatuan * $qty),
            ];
        }

        // Simpan ke database dengan Transaction
        $stats = [
            'channel' => $detectedChannel,
            'total_found' => count($ordersGrouped),
            'imported' => 0,
            'skipped' => 0,
            'total_omzet' => 0,
            'total_admin' => 0,
            'total_bersih' => 0,
            'messages' => [],
        ];

        DB::beginTransaction();
        try {
            $defaultKategori = Kategori::firstOrCreate(['nama_kategori' => 'Marketplace'], [
                'deskripsi' => 'Kategori produk otomatis import pesanan online',
            ]);
            $defaultSatuan = Satuan::firstOrCreate(['singkatan' => 'pcs'], [
                'nama_satuan' => 'Pcs / Buah',
            ]);

            foreach ($ordersGrouped as $noPesanan => $order) {
                // Cek apakah nomor pesanan ini sudah pernah di-import
                $exists = Transaksi::where('no_pesanan_marketplace', $noPesanan)->first();
                if ($exists) {
                    $stats['skipped']++;

                    continue;
                }

                $itemsSubtotal = array_sum(array_column($order['items'], 'subtotal'));
                $grandTotal = $order['total_pembayaran'] > 0 ? $order['total_pembayaran'] : $itemsSubtotal;

                $biayaAdmin = $order['biaya_admin'];
                $penghasilanBersih = $order['penghasilan_bersih'];

                if ($penghasilanBersih <= 0 && $grandTotal > 0) {
                    $penghasilanBersih = max(0, $grandTotal - $biayaAdmin);
                } elseif ($biayaAdmin <= 0 && $grandTotal > 0 && $penghasilanBersih > 0) {
                    $biayaAdmin = max(0, $grandTotal - $penghasilanBersih);
                }

                $transaksi = Transaksi::create([
                    'channel' => $order['channel'],
                    'kode_transaksi' => Transaksi::generateKodeTransaksi(),
                    'no_pesanan_marketplace' => $noPesanan,
                    'no_resi' => $order['resi'],
                    'kurir' => $order['kurir'],
                    'tanggal' => $order['tanggal'],
                    'nama_pelanggan' => $order['pelanggan'],
                    'total_harga' => $itemsSubtotal > 0 ? $itemsSubtotal : $grandTotal,
                    'total_diskon' => max(0, ($itemsSubtotal > $grandTotal ? $itemsSubtotal - $grandTotal : 0)),
                    'grand_total' => $grandTotal,
                    'biaya_admin_marketplace' => $biayaAdmin,
                    'penghasilan_bersih' => $penghasilanBersih > 0 ? $penghasilanBersih : $grandTotal,
                    'status_pesanan' => $order['status_pesanan'],
                    'metode_bayar' => 'saldo_penjual',
                    'catatan' => 'Import '.ucfirst($order['channel']).' #'.$noPesanan.($order['kurir'] ? ' ('.$order['kurir'].')' : ''),
                    'user_id' => $userId,
                ]);

                foreach ($order['items'] as $item) {
                    $produk = $this->resolveProduk($item['sku'], $item['nama'], $item['harga_satuan'], $defaultKategori->id, $defaultSatuan->id);

                    TransaksiDetail::create([
                        'transaksi_id' => $transaksi->id,
                        'produk_id' => $produk->id,
                        'nama_produk' => Str::limit($item['nama'], 150),
                        'harga_satuan' => $item['harga_satuan'] > 0 ? $item['harga_satuan'] : ($produk->harga_jual ?? 0),
                        'qty' => $item['qty'],
                        'subtotal' => $item['subtotal'] > 0 ? $item['subtotal'] : ($item['qty'] * ($produk->harga_jual ?? 0)),
                    ]);

                    // Kurangi stok kecuali pesanan batal atau retur
                    if (! in_array($order['status_pesanan'], ['batal', 'retur'], true)) {
                        $produk->decrement('stok', $item['qty']);
                    }
                }

                $stats['imported']++;
                $stats['total_omzet'] += $grandTotal;
                $stats['total_admin'] += $biayaAdmin;
                $stats['total_bersih'] += ($penghasilanBersih > 0 ? $penghasilanBersih : $grandTotal);
            }

            DB::commit();
        } catch (\Throwable $e) {
            DB::rollBack();
            throw $e;
        }

        return $stats;
    }

    /**
     * Cari produk berdasarkan SKU atau nama, atau buat baru bila belum ada.
     */
    private function resolveProduk(string $sku, string $nama, float $harga, int $defaultKategoriId, int $defaultSatuanId): Produk
    {
        if ($sku !== '') {
            $p = Produk::where('kode_produk', $sku)->first();
            if ($p) {
                return $p;
            }
        }

        $p = Produk::where('nama_produk', $nama)->first();
        if ($p) {
            return $p;
        }

        $newSku = $sku !== '' ? $sku : Produk::generateKodeProduk();

        return Produk::create([
            'kategori_id' => $defaultKategoriId,
            'satuan_id' => $defaultSatuanId,
            'kode_produk' => $newSku,
            'nama_produk' => Str::limit($nama, 150),
            'harga_beli' => round($harga * 0.7, 2),
            'harga_jual' => $harga > 0 ? $harga : 0,
            'stok' => 100,
            'stok_minimal' => 10,
            'deskripsi' => 'Dibuat otomatis dari import pesanan marketplace',
        ]);
    }

    /**
     * Deteksi kolom-kolom Shopee, Lazada, atau TikTok Shop.
     */
    private function detectHeaders(array $row): array
    {
        $mapping = [];

        foreach ($row as $colKey => $val) {
            if ($val === null) {
                continue;
            }

            $clean = strtolower(trim((string) $val));
            $clean = str_replace(["\r", "\n", "\t", '"', "'"], '', $clean);

            // No Pesanan / Order ID
            if (empty($mapping['no_pesanan']) && (
                str_contains($clean, 'no. pesanan') ||
                str_contains($clean, 'no pesanan') ||
                str_contains($clean, 'order id') ||
                str_contains($clean, 'order number') ||
                str_contains($clean, 'order_id') ||
                str_contains($clean, 'order no') ||
                $clean === 'nomor pesanan'
            )) {
                $mapping['no_pesanan'] = $colKey;
            }

            // Status Pesanan
            if (empty($mapping['status']) && (
                str_contains($clean, 'status pesanan') ||
                str_contains($clean, 'order status') ||
                $clean === 'status'
            )) {
                $mapping['status'] = $colKey;
            }

            // Tanggal
            if (empty($mapping['tanggal']) && (
                str_contains($clean, 'waktu pesanan dibuat') ||
                str_contains($clean, 'waktu pembayaran dikonfirmasi') ||
                str_contains($clean, 'tanggal pelepasan dana') ||
                str_contains($clean, 'order creation date') ||
                str_contains($clean, 'created time') ||
                str_contains($clean, 'create_time') ||
                str_contains($clean, 'created at') ||
                str_contains($clean, 'waktu pesanan') ||
                str_contains($clean, 'tanggal pesanan') ||
                $clean === 'tanggal'
            )) {
                $mapping['tanggal'] = $colKey;
            }

            // SKU
            if (empty($mapping['sku']) && (
                str_contains($clean, 'referensi sku') ||
                str_contains($clean, 'seller sku') ||
                str_contains($clean, 'shop sku') ||
                str_contains($clean, 'sku penjual') ||
                str_contains($clean, 'sku induk') ||
                str_contains($clean, 'sku reference') ||
                $clean === 'sku' ||
                str_contains($clean, 'nomor referensi')
            )) {
                $mapping['sku'] = $colKey;
            }

            // Nama Produk
            if (empty($mapping['nama_produk']) && (
                str_contains($clean, 'nama produk') ||
                str_contains($clean, 'product name') ||
                str_contains($clean, 'item name') ||
                $clean === 'produk'
            )) {
                $mapping['nama_produk'] = $colKey;
            }

            // Variasi
            if (empty($mapping['variasi']) && (
                str_contains($clean, 'nama variasi') ||
                str_contains($clean, 'variasi') ||
                str_contains($clean, 'variation')
            )) {
                $mapping['variasi'] = $colKey;
            }

            // Qty
            if (empty($mapping['qty']) && (
                str_contains($clean, 'sku quantity') ||
                $clean === 'jumlah' ||
                $clean === 'qty' ||
                str_contains($clean, 'quantity')
            )) {
                $mapping['qty'] = $colKey;
            }

            // Harga Satuan
            if (empty($mapping['harga_satuan']) && (
                str_contains($clean, 'sku unit original price') ||
                str_contains($clean, 'harga deal') ||
                str_contains($clean, 'harga awal') ||
                str_contains($clean, 'paid price') ||
                str_contains($clean, 'deal price') ||
                str_contains($clean, 'harga setelah diskon') ||
                $clean === 'harga satuan' ||
                $clean === 'unit price'
            )) {
                $mapping['harga_satuan'] = $colKey;
            }

            // Subtotal
            if (empty($mapping['subtotal']) && (
                str_contains($clean, 'total harga produk') ||
                str_contains($clean, 'subtotal') ||
                str_contains($clean, 'item subtotal')
            )) {
                $mapping['subtotal'] = $colKey;
            }

            // Total Pembayaran
            if (empty($mapping['total_pembayaran']) && (
                str_contains($clean, 'total pembayaran') ||
                str_contains($clean, 'total bayar') ||
                str_contains($clean, 'total amount') ||
                str_contains($clean, 'order amount') ||
                $clean === 'grand total'
            )) {
                $mapping['total_pembayaran'] = $colKey;
            }

            // Biaya Admin / Potongan Marketplace
            if (empty($mapping['biaya_admin']) && (
                str_contains($clean, 'biaya administrasi') ||
                str_contains($clean, 'biaya layanan') ||
                str_contains($clean, 'total potongan biaya') ||
                str_contains($clean, 'potongan shopee') ||
                str_contains($clean, 'marketplace commission') ||
                str_contains($clean, 'biaya transaksi') ||
                str_contains($clean, 'total biaya')
            )) {
                $mapping['biaya_admin'] = $colKey;
            }

            // Penghasilan Bersih / Dana Dilepaskan
            if (empty($mapping['penghasilan_bersih']) && (
                str_contains($clean, 'total penghasilan') ||
                str_contains($clean, 'dana dilepaskan') ||
                str_contains($clean, 'pendapatan bersih') ||
                str_contains($clean, 'penghasilan bersih') ||
                str_contains($clean, 'net amount') ||
                str_contains($clean, 'settlement amount')
            )) {
                $mapping['penghasilan_bersih'] = $colKey;
            }

            // Pembeli / Username
            if (empty($mapping['pembeli']) && (
                str_contains($clean, 'username (pembeli)') ||
                str_contains($clean, 'username pembeli') ||
                str_contains($clean, 'buyer username') ||
                str_contains($clean, 'customer name') ||
                str_contains($clean, 'nama penerima') ||
                str_contains($clean, 'pembeli') ||
                str_contains($clean, 'buyer')
            )) {
                $mapping['pembeli'] = $colKey;
            }

            // No. Resi
            if (empty($mapping['resi']) && (
                str_contains($clean, 'tracking id') ||
                str_contains($clean, 'tracking code') ||
                str_contains($clean, 'tracking number') ||
                str_contains($clean, 'no. resi') ||
                str_contains($clean, 'no resi') ||
                str_contains($clean, 'nomor resi')
            )) {
                $mapping['resi'] = $colKey;
            }

            // Kurir
            if (empty($mapping['kurir']) && (
                str_contains($clean, 'shipping provider') ||
                str_contains($clean, 'opsi pengiriman') ||
                str_contains($clean, 'jasa kirim') ||
                str_contains($clean, 'kurir') ||
                str_contains($clean, 'shipping carrier')
            )) {
                $mapping['kurir'] = $colKey;
            }
        }

        return $mapping;
    }

    /**
     * Deteksi channel asal marketplace berdasarkan header.
     */
    private function detectChannel(array $mapping): string
    {
        // Default shopee
        return 'shopee';
    }

    /**
     * Parsing angka dari string format Excel.
     */
    private function parseNumber(mixed $val): float
    {
        if (empty($val)) {
            return 0.0;
        }

        if (is_int($val) || is_float($val)) {
            return (float) $val;
        }

        $str = trim((string) $val);
        $str = preg_replace('/[^\d.,-]/', '', $str);

        if ($str === '') {
            return 0.0;
        }

        if (str_contains($str, ',') && str_contains($str, '.')) {
            if (strrpos($str, ',') > strrpos($str, '.')) {
                $str = str_replace('.', '', $str);
                $str = str_replace(',', '.', $str);
            } else {
                $str = str_replace(',', '', $str);
            }
        } elseif (str_contains($str, '.')) {
            if (substr_count($str, '.') > 1) {
                $str = str_replace('.', '', $str);
            } else {
                $parts = explode('.', $str);
                if (strlen($parts[1]) === 3) {
                    $str = str_replace('.', '', $str);
                }
            }
        } elseif (str_contains($str, ',')) {
            $parts = explode(',', $str);
            if (strlen($parts[1]) === 3) {
                $str = str_replace(',', '', $str);
            } else {
                $str = str_replace(',', '.', $str);
            }
        }

        return (float) $str;
    }

    /**
     * Parsing tanggal.
     */
    private function parseDate(mixed $val): string
    {
        if (empty($val)) {
            return now()->toDateString();
        }

        try {
            if (is_numeric($val)) {
                $dateTime = Date::excelToDateTimeObject($val);

                return $dateTime->format('Y-m-d');
            }

            return Carbon::parse($val)->toDateString();
        } catch (\Throwable $e) {
            return now()->toDateString();
        }
    }

    /**
     * Normalisasi status pesanan dari marketplace.
     */
    private function normalizeStatus(string $status): string
    {
        $s = strtolower(trim($status));

        if (str_contains($s, 'selesai') || str_contains($s, 'completed') || str_contains($s, 'delivered') || str_contains($s, 'diterima')) {
            return 'selesai';
        }

        if (str_contains($s, 'batal') || str_contains($s, 'cancelled') || str_contains($s, 'canceled')) {
            return 'batal';
        }

        if (str_contains($s, 'retur') || str_contains($s, 'kembali') || str_contains($s, 'refund') || str_contains($s, 'return')) {
            return 'retur';
        }

        if (str_contains($s, 'kirim') || str_contains($s, 'transit') || str_contains($s, 'shipped')) {
            return 'dikirim';
        }

        if (str_contains($s, 'perlu') || str_contains($s, 'diproses') || str_contains($s, 'to ship') || str_contains($s, 'unpaid') || str_contains($s, 'pending')) {
            return 'perlu_dikirim';
        }

        return 'selesai';
    }
}
