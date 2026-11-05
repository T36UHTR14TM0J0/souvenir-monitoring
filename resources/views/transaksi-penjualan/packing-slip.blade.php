<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Packing Slip - {{ $transaksi->no_pesanan_marketplace ?: $transaksi->kode_transaksi }}</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Helvetica, Arial, sans-serif;
        }

        body {
            background-color: #f1f5f9;
            color: #0f172a;
            padding: 24px;
            font-size: 13px;
            line-height: 1.5;
        }

        .slip-wrapper {
            max-width: 800px;
            margin: 0 auto;
            background: #ffffff;
            border: 1px solid #cbd5e1;
            border-radius: 8px;
            padding: 32px;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05);
        }

        /* Header */
        .slip-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            border-bottom: 2px solid #0f172a;
            padding-bottom: 16px;
            margin-bottom: 24px;
        }

        .brand-title {
            font-size: 20px;
            font-weight: 800;
            letter-spacing: -0.02em;
            color: #0f172a;
            text-transform: uppercase;
        }

        .brand-subtitle {
            font-size: 12px;
            color: #64748b;
            margin-top: 2px;
        }

        .doc-title {
            text-align: right;
        }

        .doc-badge {
            display: inline-block;
            font-size: 12px;
            font-weight: 700;
            padding: 4px 10px;
            border-radius: 4px;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            margin-bottom: 4px;
        }

        .badge-shopee { background: #fff7ed; color: #c2410c; border: 1px solid #fed7aa; }
        .badge-lazada { background: #eff6ff; color: #1d4ed8; border: 1px solid #bfdbfe; }
        .badge-tiktok { background: #f8fafc; color: #0f172a; border: 1px solid #cbd5e1; }

        .doc-name {
            font-size: 16px;
            font-weight: 800;
            color: #0f172a;
            letter-spacing: -0.01em;
        }

        /* Info Grid */
        .info-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 16px;
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 6px;
            padding: 16px;
            margin-bottom: 24px;
        }

        .info-col p {
            margin-bottom: 6px;
            font-size: 12px;
        }

        .info-col p:last-child {
            margin-bottom: 0;
        }

        .info-label {
            display: inline-block;
            width: 130px;
            color: #64748b;
            font-weight: 500;
        }

        .info-value {
            font-weight: 600;
            color: #0f172a;
        }

        .highlight-mono {
            font-family: monospace;
            font-size: 13px;
            color: #0f172a;
            background: #e2e8f0;
            padding: 2px 6px;
            border-radius: 4px;
        }

        /* Items Table */
        .items-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 24px;
        }

        .items-table th {
            background-color: #f1f5f9;
            color: #334155;
            font-size: 11px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            text-align: left;
            padding: 10px 12px;
            border-top: 1px solid #cbd5e1;
            border-bottom: 1px solid #cbd5e1;
        }

        .items-table td {
            padding: 12px;
            border-bottom: 1px solid #e2e8f0;
            font-size: 12px;
            vertical-align: middle;
        }

        .check-box {
            width: 18px;
            height: 18px;
            border: 2px solid #64748b;
            border-radius: 3px;
            display: inline-block;
        }

        .sku-code {
            font-family: monospace;
            font-size: 11px;
            color: #64748b;
        }

        .qty-badge {
            font-weight: 700;
            font-size: 14px;
            color: #0f172a;
        }

        /* Footer & Notes */
        .notes-section {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            gap: 24px;
            border-top: 1px dashed #cbd5e1;
            padding-top: 16px;
        }

        .packing-notes {
            flex: 1;
            font-size: 11px;
            color: #64748b;
            line-height: 1.6;
        }

        .signature-box {
            width: 220px;
            border: 1px solid #cbd5e1;
            border-radius: 6px;
            padding: 12px;
            text-align: center;
        }

        .signature-title {
            font-size: 11px;
            font-weight: 600;
            color: #64748b;
            margin-bottom: 40px;
        }

        .signature-line {
            border-top: 1px solid #94a3b8;
            font-size: 11px;
            font-weight: 600;
            color: #0f172a;
            padding-top: 4px;
        }

        /* Action bar screen only */
        .action-bar {
            max-width: 800px;
            margin: 0 auto 16px auto;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .btn-print {
            background: #0f172a;
            color: #ffffff;
            border: none;
            padding: 8px 16px;
            font-size: 13px;
            font-weight: 600;
            border-radius: 6px;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            text-decoration: none;
        }

        .btn-print:hover {
            background: #1e293b;
        }

        .btn-back {
            background: #ffffff;
            color: #475569;
            border: 1px solid #cbd5e1;
            padding: 8px 16px;
            font-size: 13px;
            font-weight: 600;
            border-radius: 6px;
            cursor: pointer;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }

        .btn-back:hover {
            background: #f8fafc;
            color: #0f172a;
        }

        @media print {
            body {
                background: #ffffff;
                padding: 0;
            }
            .slip-wrapper {
                border: none;
                box-shadow: none;
                padding: 0;
                max-width: 100%;
            }
            .action-bar {
                display: none !important;
            }
        }
    </style>
</head>
<body>

    <div class="action-bar">
        <a href="{{ route('transaksi-penjualan.index') }}" class="btn-back">
            <i class="fas fa-arrow-left"></i> Kembali ke Daftar
        </a>
        <div style="display: flex; gap: 8px;">
            <button onclick="window.print()" class="btn-print">
                <i class="fas fa-print"></i> Cetak Packing Slip
            </button>
        </div>
    </div>

    <div class="slip-wrapper">
        {{-- Header --}}
        <div class="slip-header">
            <div>
                <div class="brand-title">Toko Souvenir Monitoring</div>
                <div class="brand-subtitle">Gudang & Pengemasan Pesanan Online</div>
            </div>
            <div class="doc-title">
                @php
                    $badgeClass = match($transaksi->channel) {
                        'lazada' => 'badge-lazada',
                        'tiktok' => 'badge-tiktok',
                        default => 'badge-shopee',
                    };
                @endphp
                <span class="doc-badge {{ $badgeClass }}">
                    {{ $transaksi->channel_label }}
                </span>
                <div class="doc-name">PACKING SLIP & SURAT JALAN</div>
            </div>
        </div>

        {{-- Info Pesanan --}}
        <div class="info-grid">
            <div class="info-col">
                <p>
                    <span class="info-label">No. Pesanan Online:</span>
                    <span class="info-value highlight-mono">{{ $transaksi->no_pesanan_marketplace ?: '-' }}</span>
                </p>
                <p>
                    <span class="info-label">Kode Transaksi:</span>
                    <span class="info-value">{{ $transaksi->kode_transaksi }}</span>
                </p>
                <p>
                    <span class="info-label">Tanggal Pesanan:</span>
                    <span class="info-value">{{ $transaksi->tanggal->translatedFormat('d F Y') }}</span>
                </p>
            </div>
            <div class="info-col">
                <p>
                    <span class="info-label">Penerima / Pembeli:</span>
                    <span class="info-value">{{ $transaksi->nama_pelanggan ?: 'Pembeli Marketplace' }}</span>
                </p>
                <p>
                    <span class="info-label">Kurir / Ekspedisi:</span>
                    <span class="info-value">{{ $transaksi->kurir ?: 'Ekspedisi Marketplace' }}</span>
                </p>
                <p>
                    <span class="info-label">No. Resi Pengiriman:</span>
                    <span class="info-value highlight-mono">{{ $transaksi->no_resi ?: 'Belum Ada Resi' }}</span>
                </p>
            </div>
        </div>

        {{-- Items Checklist Table --}}
        <table class="items-table">
            <thead>
                <tr>
                    <th style="width: 40px; text-align: center;">Cek</th>
                    <th style="width: 50px;">No</th>
                    <th style="width: 140px;">SKU / Kode</th>
                    <th>Nama Produk Souvenir</th>
                    <th style="width: 80px; text-align: center;">Jumlah</th>
                    <th style="width: 80px;">Satuan</th>
                </tr>
            </thead>
            <tbody>
                @foreach($transaksi->details as $index => $item)
                <tr>
                    <td style="text-align: center;">
                        <span class="check-box"></span>
                    </td>
                    <td>{{ $index + 1 }}</td>
                    <td>
                        <span class="sku-code">{{ $item->produk->kode_produk ?? '-' }}</span>
                    </td>
                    <td>
                        <div style="font-weight: 600; color: #0f172a;">{{ $item->nama_produk }}</div>
                        @if($item->produk && $item->produk->deskripsi)
                            <div style="font-size: 11px; color: #64748b;">{{ Str::limit($item->produk->deskripsi, 60) }}</div>
                        @endif
                    </td>
                    <td style="text-align: center;">
                        <span class="qty-badge">{{ $item->qty }}</span>
                    </td>
                    <td style="color: #64748b;">
                        {{ $item->produk->satuan->singkatan ?? 'pcs' }}
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>

        {{-- Notes & Sign --}}
        <div class="notes-section">
            <div class="packing-notes">
                <div style="font-weight: 700; color: #0f172a; margin-bottom: 4px;">Instruksi Pengemasan Gudang:</div>
                <div>1. Periksa kesesuaian fisik produk dan jumlah item dengan checklist di atas.</div>
                <div>2. Pastikan kemasan souvenir menggunakan bubble wrap dan segel toko rapat.</div>
                <div>3. Tempelkan label resi ekspedisi resmi di bagian atas paket secara jelas.</div>
                @if($transaksi->catatan)
                <div style="margin-top: 6px; padding: 6px 10px; background: #fffbeb; border: 1px solid #fef3c7; border-radius: 4px; color: #92400e;">
                    <b>Catatan Khusus:</b> {{ $transaksi->catatan }}
                </div>
                @endif
            </div>

            <div class="signature-box">
                <div class="signature-title">Petugas Packing Gudang</div>
                <div class="signature-line">
                    ( {{ auth()->user()->name ?? 'Petugas Gudang' }} )
                </div>
                <div style="font-size: 10px; color: #94a3b8; margin-top: 2px;">
                    {{ now()->translatedFormat('d/m/Y H:i') }} WIB
                </div>
            </div>
        </div>
    </div>

</body>
</html>
