<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Keuangan {{ ucfirst($tab) }} - {{ $tahun }}</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'DejaVu Sans', Arial, sans-serif;
            font-size: 9.5px;
            color: #1f2937;
            padding: 18px;
            line-height: 1.4;
        }
        .header {
            text-align: center;
            border-bottom: 3px double #26476f;
            padding-bottom: 10px;
            margin-bottom: 14px;
        }
        .header h1 {
            font-size: 15px;
            color: #26476f;
            margin-bottom: 3px;
            font-weight: bold;
        }
        .header .subtitle {
            font-size: 10px;
            color: #4b5563;
            margin-bottom: 2px;
        }
        .header .periode {
            font-size: 11px;
            font-weight: bold;
            color: #26476f;
            margin-top: 5px;
            padding: 3px 10px;
            background-color: #f3f4f6;
            border-radius: 4px;
            display: inline-block;
        }
        .meta {
            width: 100%;
            margin-bottom: 12px;
            font-size: 8.5px;
            color: #6b7280;
            border-collapse: collapse;
        }
        .meta td { padding: 0; vertical-align: top; }
        .meta .meta-left  { text-align: left; }
        .meta .meta-right { text-align: right; }
        .summary-grid {
            width: 100%;
            border-collapse: separate;
            border-spacing: 5px;
            margin-bottom: 12px;
        }
        .summary-grid td {
            background-color: #f9fafb;
            border: 1px solid #e5e7eb;
            border-radius: 5px;
            padding: 8px 10px;
            width: 25%;
            vertical-align: top;
        }
        .summary-label {
            font-size: 7.5px;
            font-weight: bold;
            color: #6b7280;
            text-transform: uppercase;
            margin-bottom: 3px;
        }
        .summary-value {
            font-size: 10.5px;
            font-weight: bold;
            color: #111827;
            margin-bottom: 2px;
        }
        .summary-value.green { color: #059669; }
        .summary-value.red   { color: #dc2626; }
        .summary-value.blue  { color: #0284c7; }
        .summary-value.amber { color: #d97706; }
        .summary-value.navy  { color: #26476f; }
        .summary-sub { font-size: 7.5px; color: #6b7280; }
        .section-title {
            font-size: 9.5px;
            font-weight: bold;
            color: #26476f;
            margin-bottom: 5px;
            padding-bottom: 3px;
            border-bottom: 2px solid #26476f;
        }
        table.data-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 12px;
            font-size: 8.5px;
        }
        table.data-table thead th {
            background-color: #26476f;
            color: #ffffff;
            padding: 5px 6px;
            text-align: center;
            font-size: 7.5px;
            font-weight: bold;
            text-transform: uppercase;
            border: 1px solid #1a304d;
        }
        table.data-table tbody td {
            padding: 4px 6px;
            border-bottom: 1px solid #e5e7eb;
            text-align: right;
            color: #374151;
            vertical-align: middle;
        }
        table.data-table tbody td:first-child {
            text-align: left;
            font-weight: 600;
            color: #1f2937;
        }
        table.data-table tbody td.text-center { text-align: center; }
        table.data-table tbody td.text-left { text-align: left; }
        table.data-table tbody tr:nth-child(even) td { background-color: #f9fafb; }
        table.data-table tfoot td {
            padding: 6px;
            background-color: #e5e7eb;
            border-top: 2px solid #9ca3af;
            font-weight: bold;
            font-size: 8.5px;
            text-align: right;
            color: #111827;
        }
        table.data-table tfoot td:first-child { text-align: left; }
        .text-green { color: #059669; font-weight: bold; }
        .text-red   { color: #dc2626; font-weight: bold; }
        .text-amber { color: #d97706; font-weight: bold; }
        .text-navy  { color: #26476f; font-weight: bold; }
        .badge {
            display: inline-block;
            font-size: 7.5px;
            font-weight: bold;
            padding: 1px 5px;
            border-radius: 6px;
            background-color: #f3f4f6;
            color: #374151;
            border: 1px solid #d1d5db;
        }
        .footer {
            margin-top: 20px;
            padding-top: 8px;
            border-top: 1px solid #e5e7eb;
            font-size: 7.5px;
            color: #9ca3af;
            text-align: center;
        }
        .footer-signature {
            width: 100%;
            margin-top: 30px;
            border-collapse: collapse;
        }
        .footer-signature .sign-box {
            width: 50%;
            text-align: center;
            vertical-align: top;
            padding: 0 20px;
        }
        .footer-signature .sign-label { font-size: 9px; color: #6b7280; }
        .footer-signature .sign-space { height: 50px; }
        .footer-signature .sign-line {
            border-top: 1px solid #374151;
            padding-top: 5px;
            font-size: 9px;
            font-weight: bold;
            color: #1f2937;
            width: 160px;
            margin: 0 auto;
        }
        .empty-row {
            text-align: center !important;
            padding: 16px !important;
            color: #9ca3af;
            font-style: italic;
        }
    </style>
</head>
<body>

    <div class="header">
        <h1>LAPORAN KEUANGAN & OPERASIONAL TOKO SOUVENIR JIBRANIME</h1>
        <div class="subtitle">Sistem Monitoring Multi-Marketplace, Produksi & Gudang</div>
        <div class="periode">
            @php
                $labelTab = [
                    'laba-rugi'    => 'Laba/Rugi Bisnis',
                    'penjualan'    => 'Penjualan Marketplace',
                    'pembelian'    => 'Pembelian Bahan Baku',
                    'pengeluaran'  => 'Biaya Operasional & Ads',
                    'produksi'     => 'Produksi & QC Gudang',
                    'pribadi'      => 'Keuangan Pribadi (Prive)',
                    'rekonsiliasi' => 'Rekonsiliasi Kas & Modal',
                    'kasbon'       => 'Buku Kasbon Tim',
                ][$tab] ?? 'Laporan';

                $labelPeriode = ($bulan === 'all')
                    ? 'Semua Bulan ' . $tahun
                    : ($bulanList[(int) $bulan] ?? '') . ' ' . $tahun;
            @endphp
            Laporan {{ $labelTab }} — {{ $labelPeriode }}
        </div>
    </div>

    <table class="meta">
        <tr>
            <td class="meta-left"><strong>Dicetak:</strong> {{ now()->translatedFormat('d F Y, H:i') }} WIB</td>
            <td class="meta-right"><strong>Oleh:</strong> {{ auth()->user()->name ?? '-' }}</td>
        </tr>
    </table>

    @if($tab === 'laba-rugi')
        <table class="summary-grid">
            <tr>
                <td>
                    <div class="summary-label">Penghasilan Bersih MP</div>
                    <div class="summary-value green">Rp {{ number_format($total['penghasilan_bersih'] ?? $total['penjualan'], 0, ',', '.') }}</div>
                    <div class="summary-sub">Omzet Kotor: Rp {{ number_format($total['penjualan'], 0, ',', '.') }}</div>
                </td>
                <td>
                    <div class="summary-label">Belanja Bahan (HPP)</div>
                    <div class="summary-value blue">Rp {{ number_format($total['pembelian'], 0, ',', '.') }}</div>
                    <div class="summary-sub">Laba Kotor: Rp {{ number_format($total['laba_kotor'], 0, ',', '.') }}</div>
                </td>
                <td>
                    <div class="summary-label">Biaya Bisnis & Ads</div>
                    <div class="summary-value amber">Rp {{ number_format($total['pengeluaran'], 0, ',', '.') }}</div>
                    <div class="summary-sub">Iklan: Rp {{ number_format($total['biaya_iklan'] ?? 0, 0, ',', '.') }}</div>
                </td>
                <td>
                    <div class="summary-label">Laba Bersih</div>
                    <div class="summary-value {{ $total['laba_bersih'] >= 0 ? 'green' : 'red' }}">
                        Rp {{ number_format($total['laba_bersih'], 0, ',', '.') }}
                    </div>
                    <div class="summary-sub">Margin: {{ $total['margin_bersih'] ?? 0 }}%</div>
                </td>
            </tr>
        </table>

        <div class="section-title">RINCIAN LABA/RUGI OPERASIONAL</div>
        <table class="data-table">
            <thead>
                <tr>
                    <th>Periode</th>
                    <th>Omzet Kotor</th>
                    <th>Potongan Admin</th>
                    <th>Penghasilan Bersih</th>
                    <th>Pembelian Bahan</th>
                    <th>Laba Kotor</th>
                    <th>Biaya Bisnis</th>
                    <th>Laba Bersih</th>
                </tr>
            </thead>
            <tbody>
                @foreach($rows as $row)
                    <tr>
                        <td>{{ $row['label'] }}</td>
                        <td>{{ $row['penjualan'] > 0 ? 'Rp ' . number_format($row['penjualan'], 0, ',', '.') : '-' }}</td>
                        <td class="text-red">{{ ($row['biaya_admin'] ?? 0) > 0 ? '-Rp ' . number_format($row['biaya_admin'], 0, ',', '.') : '-' }}</td>
                        <td class="text-green">{{ ($row['penghasilan_bersih'] ?? 0) > 0 ? 'Rp ' . number_format($row['penghasilan_bersih'], 0, ',', '.') : '-' }}</td>
                        <td class="text-navy">{{ $row['pembelian'] > 0 ? 'Rp ' . number_format($row['pembelian'], 0, ',', '.') : '-' }}</td>
                        <td class="{{ $row['laba_kotor'] >= 0 ? 'text-green' : 'text-red' }}">
                            {{ $row['laba_kotor'] != 0 ? 'Rp ' . number_format($row['laba_kotor'], 0, ',', '.') : '-' }}
                        </td>
                        <td class="text-amber">{{ $row['pengeluaran'] > 0 ? 'Rp ' . number_format($row['pengeluaran'], 0, ',', '.') : '-' }}</td>
                        <td class="{{ $row['laba_bersih'] >= 0 ? 'text-green' : 'text-red' }}">
                            {{ $row['laba_bersih'] != 0 ? 'Rp ' . number_format($row['laba_bersih'], 0, ',', '.') : '-' }}
                        </td>
                    </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr>
                    <td>TOTAL</td>
                    <td>Rp {{ number_format($total['penjualan'], 0, ',', '.') }}</td>
                    <td class="text-red">-Rp {{ number_format($total['biaya_admin'] ?? 0, 0, ',', '.') }}</td>
                    <td class="text-green">Rp {{ number_format($total['penghasilan_bersih'] ?? $total['penjualan'], 0, ',', '.') }}</td>
                    <td class="text-navy">Rp {{ number_format($total['pembelian'], 0, ',', '.') }}</td>
                    <td class="{{ $total['laba_kotor'] >= 0 ? 'text-green' : 'text-red' }}">Rp {{ number_format($total['laba_kotor'], 0, ',', '.') }}</td>
                    <td class="text-amber">Rp {{ number_format($total['pengeluaran'], 0, ',', '.') }}</td>
                    <td class="{{ $total['laba_bersih'] >= 0 ? 'text-green' : 'text-red' }}">Rp {{ number_format($total['laba_bersih'], 0, ',', '.') }}</td>
                </tr>
            </tfoot>
        </table>
    @endif

    @if($tab === 'penjualan')
        <table class="summary-grid">
            <tr>
                <td>
                    <div class="summary-label">Omzet Kotor Valid</div>
                    <div class="summary-value navy">Rp {{ number_format($total['penjualan'], 0, ',', '.') }}</div>
                    <div class="summary-sub">{{ number_format($total['transaksi'], 0, ',', '.') }} pesanan</div>
                </td>
                <td>
                    <div class="summary-label">Potongan Admin MP</div>
                    <div class="summary-value red">Rp {{ number_format($total['biaya_admin'] ?? 0, 0, ',', '.') }}</div>
                    <div class="summary-sub">Diskon: Rp {{ number_format($total['diskon'], 0, ',', '.') }}</div>
                </td>
                <td>
                    <div class="summary-label">Penghasilan Bersih</div>
                    <div class="summary-value green">Rp {{ number_format($total['penghasilan_bersih'] ?? $total['penjualan'], 0, ',', '.') }}</div>
                    <div class="summary-sub">Dana bersih seller</div>
                </td>
                <td>
                    <div class="summary-label">Item Terjual</div>
                    <div class="summary-value">{{ number_format($total['item'], 0, ',', '.') }} pcs</div>
                    <div class="summary-sub">Unit produk souvenir</div>
                </td>
            </tr>
        </table>

        <div class="section-title">DETAIL PESANAN MARKETPLACE ({{ $rows->count() }} transaksi)</div>
        <table class="data-table">
            <thead>
                <tr>
                    <th>Kode</th>
                    <th>Tanggal</th>
                    <th>Channel</th>
                    <th>Pelanggan</th>
                    <th>Status</th>
                    <th>Qty</th>
                    <th>Omzet Kotor</th>
                    <th>Fee Admin</th>
                    <th>Dana Bersih</th>
                </tr>
            </thead>
            <tbody>
                @forelse($rows as $trx)
                    <tr>
                        <td>{{ $trx->kode_transaksi }}</td>
                        <td class="text-center">{{ $trx->tanggal->format('d/m/Y') }}</td>
                        <td class="text-center">{{ strtoupper($trx->channel ?? 'SHOPEE') }}</td>
                        <td class="text-left">{{ $trx->nama_pelanggan ?? '-' }}</td>
                        <td class="text-center">{{ $trx->status_pesanan_label }}</td>
                        <td class="text-center">{{ $trx->details->sum('qty') }}</td>
                        <td>Rp {{ number_format($trx->grand_total, 0, ',', '.') }}</td>
                        <td class="text-red">{{ $trx->biaya_admin_marketplace > 0 ? '-Rp ' . number_format($trx->biaya_admin_marketplace, 0, ',', '.') : '-' }}</td>
                        <td class="text-green">Rp {{ number_format((float) $trx->grand_total - (float) $trx->biaya_admin_marketplace, 0, ',', '.') }}</td>
                    </tr>
                @empty
                    <tr><td colspan="9" class="empty-row">Tidak ada data transaksi</td></tr>
                @endforelse
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="5">TOTAL PESANAN VALID</td>
                    <td class="text-center">{{ number_format($total['item'], 0, ',', '.') }}</td>
                    <td>Rp {{ number_format($total['penjualan'], 0, ',', '.') }}</td>
                    <td class="text-red">-Rp {{ number_format($total['biaya_admin'] ?? 0, 0, ',', '.') }}</td>
                    <td class="text-green">Rp {{ number_format($total['penghasilan_bersih'] ?? $total['penjualan'], 0, ',', '.') }}</td>
                </tr>
            </tfoot>
        </table>
    @endif

    @if($tab === 'pembelian')
        <table class="summary-grid">
            <tr>
                <td>
                    <div class="summary-label">Total Pembelian</div>
                    <div class="summary-value navy">Rp {{ number_format($total['pembelian'], 0, ',', '.') }}</div>
                    <div class="summary-sub">{{ number_format($total['transaksi'], 0, ',', '.') }} transaksi</div>
                </td>
                <td>
                    <div class="summary-label">Total Ongkir</div>
                    <div class="summary-value amber">Rp {{ number_format($total['ongkir'] ?? 0, 0, ',', '.') }}</div>
                    <div class="summary-sub">Diskon: Rp {{ number_format($total['diskon'], 0, ',', '.') }}</div>
                </td>
                <td>
                    <div class="summary-label">Kuantitas Bahan</div>
                    <div class="summary-value">{{ number_format($total['item'], 0, ',', '.') }} unit</div>
                    <div class="summary-sub">Total qty bahan masuk</div>
                </td>
                <td>
                    <div class="summary-label">Paket Diterima</div>
                    <div class="summary-value green">{{ number_format($total['diterima'] ?? 0, 0, ',', '.') }} Paket</div>
                    <div class="summary-sub">{{ number_format($total['dalam_perjalanan'] ?? 0, 0, ',', '.') }} dalam pengiriman</div>
                </td>
            </tr>
        </table>

        <div class="section-title">DETAIL TRANSAKSI PEMBELIAN BAHAN BAKU ({{ $rows->count() }} transaksi)</div>
        <table class="data-table">
            <thead>
                <tr>
                    <th>Kode</th>
                    <th>Tanggal</th>
                    <th>Supplier</th>
                    <th>Platform</th>
                    <th>Status</th>
                    <th>Qty</th>
                    <th>Ongkir</th>
                    <th>Grand Total</th>
                </tr>
            </thead>
            <tbody>
                @forelse($rows as $pbl)
                    <tr>
                        <td>{{ $pbl->kode_pembelian }}</td>
                        <td class="text-center">{{ $pbl->tanggal->format('d/m/Y') }}</td>
                        <td class="text-left">{{ $pbl->supplier ?? '-' }}</td>
                        <td class="text-center">{{ $pbl->platform_label }}</td>
                        <td class="text-center">{{ $pbl->status_label }}</td>
                        <td class="text-center">{{ $pbl->details->sum('qty') }}</td>
                        <td>{{ $pbl->ongkir > 0 ? 'Rp ' . number_format($pbl->ongkir, 0, ',', '.') : '-' }}</td>
                        <td class="text-navy">Rp {{ number_format($pbl->grand_total, 0, ',', '.') }}</td>
                    </tr>
                @empty
                    <tr><td colspan="8" class="empty-row">Tidak ada data transaksi</td></tr>
                @endforelse
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="5">TOTAL PEMBELIAN</td>
                    <td class="text-center">{{ number_format($total['item'], 0, ',', '.') }}</td>
                    <td>Rp {{ number_format($total['ongkir'] ?? 0, 0, ',', '.') }}</td>
                    <td class="text-navy">Rp {{ number_format($total['pembelian'], 0, ',', '.') }}</td>
                </tr>
            </tfoot>
        </table>
    @endif

    @if($tab === 'pengeluaran' || $tab === 'pribadi')
        <div class="section-title">DETAIL PENGELUARAN ({{ $rows->count() }} data)</div>
        <table class="data-table">
            <thead>
                <tr>
                    <th>Kode</th>
                    <th>Tanggal</th>
                    <th>Kategori</th>
                    <th>Keterangan</th>
                    <th>Jumlah</th>
                </tr>
            </thead>
            <tbody>
                @forelse($rows as $pgl)
                    <tr>
                        <td>{{ $pgl->kode_pengeluaran }}</td>
                        <td class="text-center">{{ $pgl->tanggal->format('d/m/Y') }}</td>
                        <td class="text-left">{{ $pgl->kategori_label }}</td>
                        <td class="text-left">{{ $pgl->keterangan }}</td>
                        <td class="text-red">Rp {{ number_format($pgl->jumlah, 0, ',', '.') }}</td>
                    </tr>
                @empty
                    <tr><td colspan="5" class="empty-row">Tidak ada data pengeluaran</td></tr>
                @endforelse
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="4">TOTAL</td>
                    <td class="text-red">Rp {{ number_format($total['pengeluaran'], 0, ',', '.') }}</td>
                </tr>
            </tfoot>
        </table>
    @endif

    @if($tab === 'produksi')
        <div class="section-title">RINCIAN HASIL PRODUKSI MASUK & QC ({{ $rows->count() }} batch)</div>
        <table class="data-table">
            <thead>
                <tr>
                    <th>Kode</th>
                    <th>Tanggal</th>
                    <th>Petugas QC</th>
                    <th>Tipe</th>
                    <th>Lolos QC (Bagus)</th>
                    <th>Reject QC</th>
                </tr>
            </thead>
            <tbody>
                @forelse($rows as $prd)
                    <tr>
                        <td>{{ $prd->kode_penerimaan }}</td>
                        <td class="text-center">{{ $prd->tanggal->format('d/m/Y') }}</td>
                        <td class="text-left">{{ $prd->nama_petugas ?? '-' }}</td>
                        <td class="text-center">{{ ucfirst(str_replace('_', ' ', $prd->tipe_produksi ?? 'produksi')) }}</td>
                        <td class="text-green">{{ number_format($prd->total_qty_bagus, 0, ',', '.') }} pcs</td>
                        <td class="text-red">{{ number_format($prd->total_qty_reject, 0, ',', '.') }} pcs</td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="empty-row">Tidak ada data produksi</td></tr>
                @endforelse
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="4">TOTAL (Rasio Lolos QC: {{ $total['rasio_qc'] }}%)</td>
                    <td class="text-green">{{ number_format($total['total_bagus'], 0, ',', '.') }} pcs</td>
                    <td class="text-red">{{ number_format($total['total_reject'], 0, ',', '.') }} pcs</td>
                </tr>
            </tfoot>
        </table>
    @endif

    @if($tab === 'rekonsiliasi')
        <div class="section-title">REKONSILIASI PER BULAN (MODAL, PENCAIRAN & PRIVE)</div>
        <table class="data-table">
            <thead>
                <tr>
                    <th>Bulan</th>
                    <th>Modal Disetor</th>
                    <th>Pendapatan Lain / Pencairan</th>
                    <th>Prive Ditarik</th>
                    <th>Selisih Netto</th>
                </tr>
            </thead>
            <tbody>
                @foreach($rows as $rek)
                    <tr>
                        <td>{{ $rek['bulan_label'] }}</td>
                        <td class="text-green">{{ $rek['modal'] > 0 ? 'Rp ' . number_format($rek['modal'], 0, ',', '.') : '-' }}</td>
                        <td class="text-navy">{{ ($rek['pendapatan_lain'] ?? 0) > 0 ? 'Rp ' . number_format($rek['pendapatan_lain'], 0, ',', '.') : '-' }}</td>
                        <td class="text-red">{{ $rek['prive'] > 0 ? 'Rp ' . number_format($rek['prive'], 0, ',', '.') : '-' }}</td>
                        <td class="{{ $rek['selisih'] >= 0 ? 'text-green' : 'text-amber' }}">
                            {{ $rek['selisih'] != 0 ? 'Rp ' . number_format($rek['selisih'], 0, ',', '.') : '-' }}
                        </td>
                    </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr>
                    <td>TOTAL</td>
                    <td class="text-green">Rp {{ number_format($total['modal_disetor'], 0, ',', '.') }}</td>
                    <td class="text-navy">Rp {{ number_format($total['pendapatan_lain'] ?? 0, 0, ',', '.') }}</td>
                    <td class="text-red">Rp {{ number_format($total['prive_ditarik'], 0, ',', '.') }}</td>
                    <td class="{{ $total['saldo_netto'] >= 0 ? 'text-green' : 'text-amber' }}">
                        Rp {{ number_format($total['saldo_netto'], 0, ',', '.') }}
                    </td>
                </tr>
            </tfoot>
        </table>
    @endif

    @if($tab === 'kasbon')
        <div class="section-title">DETAIL TRANSAKSI KASBON ({{ $rows->count() }} data)</div>
        <table class="data-table">
            <thead>
                <tr>
                    <th>Kode</th>
                    <th>Tanggal</th>
                    <th>Nama Karyawan</th>
                    <th>Keterangan</th>
                    <th>Jumlah</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @forelse($rows as $kb)
                    <tr>
                        <td>{{ $kb->kode_pengeluaran }}</td>
                        <td class="text-center">{{ $kb->tanggal->format('d/m/Y') }}</td>
                        <td class="text-left">{{ $kb->karyawan_nama ?? '-' }}</td>
                        <td class="text-left">{{ $kb->keterangan }}</td>
                        <td class="text-navy">Rp {{ number_format($kb->jumlah, 0, ',', '.') }}</td>
                        <td class="text-center">{{ $kb->status_kasbon === 'lunas' ? 'LUNAS' : 'BELUM LUNAS' }}</td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="empty-row">Tidak ada data kasbon pada periode ini</td></tr>
                @endforelse
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="4">TOTAL</td>
                    <td class="text-navy">Rp {{ number_format($total['total_kasbon'], 0, ',', '.') }}</td>
                    <td class="text-center">Sisa: Rp {{ number_format($total['total_belum_lunas'], 0, ',', '.') }}</td>
                </tr>
            </tfoot>
        </table>
    @endif

    <table class="footer-signature">
        <tr>
            <td class="sign-box">
                <div class="sign-label">Dibuat oleh,</div>
                <div class="sign-space"></div>
                <div class="sign-line">{{ auth()->user()->name ?? 'Admin' }}</div>
            </td>
            <td class="sign-box">
                <div class="sign-label">Disetujui oleh,</div>
                <div class="sign-space"></div>
                <div class="sign-line">Pemilik Toko</div>
            </td>
        </tr>
    </table>

    <div class="footer">
        <div>Laporan ini digenerate secara otomatis oleh Sistem Monitoring Keuangan Toko Souvenir Jibranime</div>
        <div>Dicetak pada: {{ now()->translatedFormat('d F Y, H:i:s') }} WIB</div>
    </div>

</body>
</html>
