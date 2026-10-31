<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Keuangan {{ ucfirst($tab) }} - {{ $tahun }}</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            font-family: 'DejaVu Sans', Arial, sans-serif;
            font-size: 10px;
            color: #1f2937;
            padding: 20px;
            line-height: 1.4;
        }

        /* ============================================
           HEADER
           ============================================ */
        .header {
            text-align: center;
            border-bottom: 3px double #26476f;
            padding-bottom: 12px;
            margin-bottom: 16px;
        }
        .header h1 {
            font-size: 16px;
            color: #26476f;
            margin-bottom: 4px;
            letter-spacing: 0.5px;
            font-weight: bold;
        }
        .header .subtitle {
            font-size: 11px;
            color: #4b5563;
            margin-bottom: 2px;
        }
        .header .periode {
            font-size: 12px;
            font-weight: bold;
            color: #26476f;
            margin-top: 6px;
            padding: 4px 12px;
            background-color: #f3f4f6;
            border-radius: 4px;
            display: inline-block;
        }

        /* ============================================
           META INFO (pakai table)
           ============================================ */
        .meta {
            width: 100%;
            margin-bottom: 14px;
            font-size: 9px;
            color: #6b7280;
            border-collapse: collapse;
        }
        .meta td {
            padding: 0;
            vertical-align: top;
        }
        .meta .meta-left  { text-align: left; }
        .meta .meta-right { text-align: right; }

        /* ============================================
           SUMMARY CARDS
           ============================================ */
        .summary-grid {
            width: 100%;
            border-collapse: separate;
            border-spacing: 6px;
            margin-bottom: 14px;
        }
        .summary-grid td {
            background-color: #f9fafb;
            border: 1px solid #e5e7eb;
            border-radius: 6px;
            padding: 10px 12px;
            width: 25%;
            vertical-align: top;
        }
        .summary-label {
            font-size: 8px;
            font-weight: bold;
            color: #6b7280;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 4px;
        }
        .summary-value {
            font-size: 11px;
            font-weight: bold;
            color: #111827;
            margin-bottom: 2px;
        }
        .summary-value.green { color: #059669; }
        .summary-value.red   { color: #dc2626; }
        .summary-value.blue  { color: #2563eb; }
        .summary-value.amber { color: #d97706; }
        .summary-value.navy  { color: #26476f; }
        .summary-sub {
            font-size: 8px;
            color: #9ca3af;
        }

        /* ============================================
           TABLE
           ============================================ */
        .section-title {
            font-size: 10px;
            font-weight: bold;
            color: #26476f;
            margin-bottom: 6px;
            padding-bottom: 4px;
            border-bottom: 2px solid #26476f;
        }

        table.data-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 12px;
            font-size: 9px;
        }

        table.data-table thead th {
            background-color: #26476f;
            color: #ffffff;
            padding: 6px 8px;
            text-align: right;
            font-size: 8px;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 0.3px;
            border: 1px solid #1a304d;
            white-space: nowrap;
        }
        table.data-table thead th:first-child { text-align: left; }
        table.data-table thead th.text-center { text-align: center; }

        table.data-table tbody td {
            padding: 5px 8px;
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
        table.data-table tbody tr:nth-child(even) td {
            background-color: #f9fafb;
        }

        table.data-table tfoot td {
            padding: 7px 8px;
            background-color: #e5e7eb;
            border-top: 2px solid #9ca3af;
            font-weight: bold;
            font-size: 9px;
            text-align: right;
            color: #111827;
        }
        table.data-table tfoot td:first-child {
            text-align: left;
        }

        .text-green { color: #059669; font-weight: bold; }
        .text-red   { color: #dc2626; font-weight: bold; }
        .text-amber { color: #d97706; font-weight: bold; }
        .text-navy  { color: #26476f; font-weight: bold; }
        .text-muted { color: #9ca3af; }

        /* ============================================
           BADGE
           ============================================ */
        .badge {
            display: inline-block;
            font-size: 8px;
            font-weight: bold;
            padding: 2px 6px;
            border-radius: 8px;
            background-color: #fef3c7;
            color: #92400e;
            border: 1px solid #fde68a;
        }

        /* ============================================
           FOOTER
           ============================================ */
        .footer {
            margin-top: 24px;
            padding-top: 10px;
            border-top: 1px solid #e5e7eb;
            font-size: 8px;
            color: #9ca3af;
            text-align: center;
        }

        /* ============================================
           SIGNATURE (pakai table — DomPDF friendly)
           ============================================ */
        .footer-signature {
            width: 100%;
            margin-top: 40px;
            border-collapse: collapse;
        }
        .footer-signature .sign-box {
            width: 50%;
            text-align: center;
            vertical-align: top;
            padding: 0 20px;
        }
        .footer-signature .sign-label {
            font-size: 10px;
            color: #6b7280;
            margin-bottom: 60px;
        }
        .footer-signature .sign-space {
            height: 60px;
        }
        .footer-signature .sign-line {
            border-top: 1px solid #374151;
            padding-top: 6px;
            font-size: 10px;
            font-weight: bold;
            color: #1f2937;
            width: 180px;
            margin: 0 auto;
        }

        .empty-row {
            text-align: center;
            padding: 20px;
            color: #9ca3af;
            font-style: italic;
        }
    </style>
</head>
<body>

    {{-- ============================================
         HEADER
         ============================================ --}}
    <div class="header">
        <h1>LAPORAN KEUANGAN TOKO SOUVENIR</h1>
        <div class="subtitle">Sistem Monitoring Keuangan</div>
        <div class="periode">
            @php
                $labelTab = [
                    'laba-rugi'   => 'Laba/Rugi',
                    'penjualan'   => 'Penjualan',
                    'pembelian'   => 'Pembelian',
                    'pengeluaran' => 'Pengeluaran',
                ][$tab] ?? 'Laporan';

                $labelPeriode = ($bulan === 'all')
                    ? 'Semua Bulan ' . $tahun
                    : ($bulanList[(int) $bulan] ?? '') . ' ' . $tahun;
            @endphp
            Laporan {{ $labelTab }} — {{ $labelPeriode }}
        </div>
    </div>

    {{-- ============================================
         META INFO
         ============================================ --}}
    <table class="meta">
        <tr>
            <td class="meta-left">
                <strong>Dicetak:</strong> {{ now()->translatedFormat('d F Y, H:i') }} WIB
            </td>
            <td class="meta-right">
                <strong>Oleh:</strong> {{ auth()->user()->name ?? '-' }}
            </td>
        </tr>
    </table>

    {{-- ============================================
         TAB: LABA/RUGI
         ============================================ --}}
    @if($tab === 'laba-rugi')

        <table class="summary-grid">
            <tr>
                <td>
                    <div class="summary-label">Penjualan</div>
                    <div class="summary-value green">Rp {{ number_format($total['penjualan'], 0, ',', '.') }}</div>
                    <div class="summary-sub">Pendapatan</div>
                </td>
                <td>
                    <div class="summary-label">Pembelian</div>
                    <div class="summary-value navy">Rp {{ number_format($total['pembelian'], 0, ',', '.') }}</div>
                    <div class="summary-sub">Modal / HPP</div>
                </td>
                <td>
                    <div class="summary-label">Pengeluaran</div>
                    <div class="summary-value amber">Rp {{ number_format($total['pengeluaran'], 0, ',', '.') }}</div>
                    <div class="summary-sub">Biaya operasional</div>
                </td>
                <td>
                    <div class="summary-label">Laba Bersih</div>
                    <div class="summary-value {{ $total['laba_bersih'] >= 0 ? 'green' : 'red' }}">
                        Rp {{ number_format($total['laba_bersih'], 0, ',', '.') }}
                    </div>
                    <div class="summary-sub">Kotor: Rp {{ number_format($total['laba_kotor'], 0, ',', '.') }}</div>
                </td>
            </tr>
        </table>

        <div class="section-title">RINCIAN LABA/RUGI PER BULAN</div>

        <table class="data-table">
            <thead>
                <tr>
                    <th>Bulan</th>
                    <th>Penjualan</th>
                    <th>Pembelian</th>
                    <th>Laba Kotor</th>
                    <th>Pengeluaran</th>
                    <th>Laba Bersih</th>
                </tr>
            </thead>
            <tbody>
                @foreach($rows as $row)
                    <tr>
                        <td>{{ $row['label'] }}</td>
                        <td class="text-green">{{ $row['penjualan'] > 0 ? 'Rp ' . number_format($row['penjualan'], 0, ',', '.') : '-' }}</td>
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
                    <td class="text-green">Rp {{ number_format($total['penjualan'], 0, ',', '.') }}</td>
                    <td class="text-navy">Rp {{ number_format($total['pembelian'], 0, ',', '.') }}</td>
                    <td class="{{ $total['laba_kotor'] >= 0 ? 'text-green' : 'text-red' }}">Rp {{ number_format($total['laba_kotor'], 0, ',', '.') }}</td>
                    <td class="text-amber">Rp {{ number_format($total['pengeluaran'], 0, ',', '.') }}</td>
                    <td class="{{ $total['laba_bersih'] >= 0 ? 'text-green' : 'text-red' }}">Rp {{ number_format($total['laba_bersih'], 0, ',', '.') }}</td>
                </tr>
            </tfoot>
        </table>

    @endif

    {{-- ============================================
         TAB: PENJUALAN
         ============================================ --}}
    @if($tab === 'penjualan')

        <table class="summary-grid">
            <tr>
                <td>
                    <div class="summary-label">Total Penjualan</div>
                    <div class="summary-value green">Rp {{ number_format($total['penjualan'], 0, ',', '.') }}</div>
                    <div class="summary-sub">Pendapatan</div>
                </td>
                <td>
                    <div class="summary-label">Jumlah Transaksi</div>
                    <div class="summary-value navy">{{ number_format($total['transaksi'], 0, ',', '.') }}x</div>
                    <div class="summary-sub">Transaksi</div>
                </td>
                <td>
                    <div class="summary-label">Total Diskon</div>
                    <div class="summary-value amber">Rp {{ number_format($total['diskon'], 0, ',', '.') }}</div>
                    <div class="summary-sub">Potongan harga</div>
                </td>
                <td>
                    <div class="summary-label">Item Terjual</div>
                    <div class="summary-value">{{ number_format($total['item'], 0, ',', '.') }} pcs</div>
                    <div class="summary-sub">Unit produk</div>
                </td>
            </tr>
        </table>

        <div class="section-title">DETAIL TRANSAKSI PENJUALAN ({{ $rows->count() }} transaksi)</div>

        <table class="data-table">
            <thead>
                <tr>
                    <th>Kode</th>
                    <th>Tanggal</th>
                    <th>Pelanggan</th>
                    <th class="text-center">Item</th>
                    <th>Diskon</th>
                    <th>Grand Total</th>
                </tr>
            </thead>
            <tbody>
                @forelse($rows as $trx)
                    <tr>
                        <td style="font-family: 'Courier New', monospace;">{{ $trx->kode_transaksi }}</td>
                        <td>{{ $trx->tanggal->format('d M Y') }}</td>
                        <td style="text-align: left;">{{ $trx->nama_pelanggan ?? '-' }}</td>
                        <td class="text-center">{{ $trx->details->count() }}</td>
                        <td class="text-amber">
                            {{ $trx->total_diskon > 0 ? 'Rp ' . number_format($trx->total_diskon, 0, ',', '.') : '-' }}
                        </td>
                        <td class="text-green">Rp {{ number_format($trx->grand_total, 0, ',', '.') }}</td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="empty-row">Tidak ada data transaksi</td></tr>
                @endforelse
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="5">TOTAL</td>
                    <td class="text-green">Rp {{ number_format($total['penjualan'], 0, ',', '.') }}</td>
                </tr>
            </tfoot>
        </table>

    @endif

    {{-- ============================================
         TAB: PEMBELIAN
         ============================================ --}}
    @if($tab === 'pembelian')

        <table class="summary-grid">
            <tr>
                <td>
                    <div class="summary-label">Total Pembelian</div>
                    <div class="summary-value navy">Rp {{ number_format($total['pembelian'], 0, ',', '.') }}</div>
                    <div class="summary-sub">Modal / HPP</div>
                </td>
                <td>
                    <div class="summary-label">Jumlah Transaksi</div>
                    <div class="summary-value">{{ number_format($total['transaksi'], 0, ',', '.') }}x</div>
                    <div class="summary-sub">Transaksi</div>
                </td>
                <td>
                    <div class="summary-label">Total Diskon</div>
                    <div class="summary-value amber">Rp {{ number_format($total['diskon'], 0, ',', '.') }}</div>
                    <div class="summary-sub">Dari supplier</div>
                </td>
                <td>
                    <div class="summary-label">Item Masuk</div>
                    <div class="summary-value">{{ number_format($total['item'], 0, ',', '.') }} pcs</div>
                    <div class="summary-sub">Unit produk</div>
                </td>
            </tr>
        </table>

        <div class="section-title">DETAIL TRANSAKSI PEMBELIAN ({{ $rows->count() }} transaksi)</div>

        <table class="data-table">
            <thead>
                <tr>
                    <th>Kode</th>
                    <th>Tanggal</th>
                    <th>Supplier</th>
                    <th class="text-center">Item</th>
                    <th>Diskon</th>
                    <th>Grand Total</th>
                </tr>
            </thead>
            <tbody>
                @forelse($rows as $pbl)
                    <tr>
                        <td style="font-family: 'Courier New', monospace;">{{ $pbl->kode_pembelian }}</td>
                        <td>{{ $pbl->tanggal->format('d M Y') }}</td>
                        <td style="text-align: left;">{{ $pbl->supplier ?? '-' }}</td>
                        <td class="text-center">{{ $pbl->details->count() }}</td>
                        <td class="text-amber">
                            {{ $pbl->total_diskon > 0 ? 'Rp ' . number_format($pbl->total_diskon, 0, ',', '.') : '-' }}
                        </td>
                        <td class="text-navy">Rp {{ number_format($pbl->grand_total, 0, ',', '.') }}</td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="empty-row">Tidak ada data transaksi</td></tr>
                @endforelse
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="5">TOTAL</td>
                    <td class="text-navy">Rp {{ number_format($total['pembelian'], 0, ',', '.') }}</td>
                </tr>
            </tfoot>
        </table>

    @endif

    {{-- ============================================
         TAB: PENGELUARAN
         ============================================ --}}
    @if($tab === 'pengeluaran')

        <table class="summary-grid">
            <tr>
                <td>
                    <div class="summary-label">Total Pengeluaran</div>
                    <div class="summary-value red">Rp {{ number_format($total['pengeluaran'], 0, ',', '.') }}</div>
                    <div class="summary-sub">Biaya</div>
                </td>
                <td>
                    <div class="summary-label">Jumlah Transaksi</div>
                    <div class="summary-value">{{ number_format($total['transaksi'], 0, ',', '.') }}x</div>
                    <div class="summary-sub">Pengeluaran</div>
                </td>
                <td colspan="2">
                    <div class="summary-label">Kategori Terbanyak</div>
                    <div class="summary-value">
                        @if($total['per_kategori']->isNotEmpty())
                            {{ $total['per_kategori']->first()['kategori'] }}
                        @else
                            -
                        @endif
                    </div>
                    <div class="summary-sub">
                        @if($total['per_kategori']->isNotEmpty())
                            Rp {{ number_format($total['per_kategori']->first()['total'], 0, ',', '.') }}
                        @else
                            Belum ada data
                        @endif
                    </div>
                </td>
            </tr>
        </table>

        {{-- REKAP PER KATEGORI --}}
        @if($total['per_kategori']->isNotEmpty())
            <div class="section-title">REKAP PENGELUARAN PER KATEGORI</div>
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Kategori</th>
                        <th class="text-center">Jumlah Transaksi</th>
                        <th>Total</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($total['per_kategori'] as $pk)
                        <tr>
                            <td>{{ $pk['kategori'] }}</td>
                            <td class="text-center">{{ $pk['count'] }}x</td>
                            <td class="text-red">Rp {{ number_format($pk['total'], 0, ',', '.') }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif

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
                        <td style="font-family: 'Courier New', monospace;">{{ $pgl->kode_pengeluaran }}</td>
                        <td>{{ $pgl->tanggal->format('d M Y') }}</td>
                        <td style="text-align: left;">
                            <span class="badge">{{ $pgl->kategori_label }}</span>
                        </td>
                        <td style="text-align: left;">{{ $pgl->keterangan }}</td>
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

    {{-- ============================================
         FOOTER — TANDA TANGAN
         ============================================ --}}
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
        <div>Laporan ini digenerate secara otomatis oleh Sistem Monitoring Keuangan Toko Souvenir</div>
        <div>Dicetak pada: {{ now()->translatedFormat('d F Y, H:i:s') }} WIB</div>
    </div>

</body>
</html>