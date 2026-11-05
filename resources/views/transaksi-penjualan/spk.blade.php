<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SPK Produksi & Serah Terima Bahan - {{ $transaksi->kode_transaksi }}</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
            color: #1e293b;
            background: #f1f5f9;
            padding: 24px;
            font-size: 13px;
            line-height: 1.5;
        }

        .spk-container {
            max-width: 820px;
            margin: 0 auto;
            background: #ffffff;
            border-radius: 8px;
            padding: 32px 36px;
            box-shadow: 0 4px 16px rgba(0, 0, 0, 0.06);
            border: 1px solid #e2e8f0;
        }

        .actions-bar {
            max-width: 820px;
            margin: 0 auto 16px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .btn {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 9px 18px;
            border-radius: 6px;
            font-size: 13px;
            font-weight: 600;
            text-decoration: none;
            cursor: pointer;
            border: none;
            transition: all 0.15s ease-in-out;
        }

        .btn-primary {
            background: #0ea5e9;
            color: #ffffff;
        }

        .btn-primary:hover {
            background: #0284c7;
        }

        .btn-outline {
            background: #ffffff;
            color: #475569;
            border: 1px solid #cbd5e1;
        }

        .btn-outline:hover {
            background: #f8fafc;
            color: #0f172a;
        }

        .header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            padding-bottom: 18px;
            border-bottom: 2px solid #0f172a;
            margin-bottom: 20px;
        }

        .store-brand {
            display: flex;
            flex-direction: column;
        }

        .store-name {
            font-size: 20px;
            font-weight: 700;
            color: #0f172a;
            letter-spacing: -0.02em;
        }

        .store-sub {
            font-size: 12px;
            color: #64748b;
            margin-top: 2px;
        }

        .doc-title-box {
            text-align: right;
        }

        .doc-title {
            font-size: 18px;
            font-weight: 800;
            color: #0f172a;
            letter-spacing: 0.04em;
        }

        .doc-sub {
            font-size: 11px;
            font-weight: 600;
            color: #0ea5e9;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            margin-top: 3px;
        }

        .info-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 18px;
            margin-bottom: 20px;
            background: #f8fafc;
            padding: 16px 18px;
            border-radius: 6px;
            border: 1px solid #e2e8f0;
        }

        .info-block-title {
            font-size: 10px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.06em;
            color: #64748b;
            margin-bottom: 8px;
        }

        .info-row {
            display: flex;
            margin-bottom: 5px;
            font-size: 12.5px;
        }

        .info-label {
            width: 140px;
            color: #64748b;
            flex-shrink: 0;
        }

        .info-value {
            color: #0f172a;
            font-weight: 600;
            word-break: break-all;
        }

        .badge-channel {
            display: inline-block;
            padding: 2px 8px;
            border-radius: 4px;
            font-size: 11px;
            font-weight: 700;
            text-transform: uppercase;
            background: #e0f2fe;
            color: #0369a1;
        }

        .badge-status {
            display: inline-block;
            padding: 2px 8px;
            border-radius: 4px;
            font-size: 11px;
            font-weight: 700;
            background: #fef3c7;
            color: #92400e;
        }

        .section-header {
            font-size: 12px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            color: #0f172a;
            margin: 22px 0 10px;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .section-header::after {
            content: '';
            flex: 1;
            height: 1px;
            background: #cbd5e1;
        }

        .table-wrap {
            margin-bottom: 16px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th {
            background: #f8fafc;
            color: #475569;
            font-size: 11px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.04em;
            padding: 8px 10px;
            text-align: left;
            border-bottom: 1.5px solid #cbd5e1;
        }

        td {
            padding: 10px;
            border-bottom: 1px solid #e2e8f0;
            font-size: 12px;
            vertical-align: middle;
        }

        .text-center {
            text-align: center;
        }

        .item-name {
            font-weight: 600;
            color: #0f172a;
        }

        .item-code {
            font-size: 11px;
            color: #64748b;
            font-family: ui-monospace, SFMono-Regular, Menlo, Monaco, Consolas, monospace;
            margin-top: 1px;
        }

        .qty-badge {
            display: inline-block;
            font-size: 12.5px;
            font-weight: 700;
            color: #0f172a;
            background: #f1f5f9;
            padding: 2px 8px;
            border-radius: 4px;
        }

        .qc-box {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            font-size: 11px;
            color: #64748b;
        }

        .checkbox-square {
            width: 14px;
            height: 14px;
            border: 1.5px solid #94a3b8;
            border-radius: 3px;
            display: inline-block;
        }

        .notes-card {
            background: #fffbeb;
            border: 1px solid #fef3c7;
            border-left: 4px solid #f59e0b;
            padding: 12px 16px;
            border-radius: 4px;
            margin-bottom: 18px;
        }

        .notes-title {
            font-size: 11px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            color: #92400e;
            margin-bottom: 3px;
        }

        .notes-body {
            font-size: 12px;
            color: #78350f;
            white-space: pre-wrap;
        }

        .handover-card {
            border: 1px dashed #0284c7;
            background: #f0f9ff;
            border-radius: 6px;
            padding: 14px 16px;
            margin-top: 18px;
            margin-bottom: 20px;
        }

        .handover-title {
            font-size: 11.5px;
            font-weight: 700;
            color: #0369a1;
            text-transform: uppercase;
            letter-spacing: 0.04em;
            margin-bottom: 10px;
            display: flex;
            align-items: center;
            gap: 6px;
        }

        .handover-table th {
            background: #e0f2fe;
            color: #0369a1;
            font-size: 10.5px;
            border-bottom: 1px solid #bae6fd;
        }

        .handover-table td {
            background: #ffffff;
            border-bottom: 1px solid #e0f2fe;
            padding: 6px 10px;
            font-size: 11.5px;
        }

        .signatures {
            display: grid;
            grid-template-columns: 1fr 1fr 1fr 1fr;
            gap: 14px;
            margin-top: 24px;
            padding-top: 16px;
            border-top: 1px dashed #cbd5e1;
        }

        .sign-col {
            text-align: center;
        }

        .sign-role {
            font-size: 10.5px;
            font-weight: 700;
            color: #64748b;
            text-transform: uppercase;
            letter-spacing: 0.04em;
            margin-bottom: 45px;
            min-height: 28px;
        }

        .sign-line {
            border-bottom: 1px solid #94a3b8;
            margin: 0 10px 4px;
        }

        .sign-name {
            font-size: 10.5px;
            font-weight: 600;
            color: #0f172a;
        }

        @media print {
            body {
                background: #ffffff;
                padding: 0;
            }

            .actions-bar {
                display: none !important;
            }

            .spk-container {
                box-shadow: none;
                border: none;
                padding: 0;
                max-width: 100%;
            }

            @page {
                size: A4 portrait;
                margin: 12mm 12mm 12mm 12mm;
            }
        }
    </style>
</head>
<body>

    <div class="actions-bar">
        <a href="{{ route('transaksi-penjualan.show', $transaksi) }}" class="btn btn-outline">
            <i class="fas fa-arrow-left"></i>
            <span>Kembali ke Detail Pesanan</span>
        </a>
        <button onclick="window.print()" class="btn btn-primary">
            <i class="fas fa-print"></i>
            <span>Cetak SPK & Bukti Serah Terima</span>
        </button>
    </div>

    <div class="spk-container">

        {{-- HEADER DOKUMEN --}}
        <div class="header">
            <div class="store-brand">
                <span class="store-name">Toko Souvenir Jibranime</span>
                <span class="store-sub">Divisi Produksi Workshop & Gudang Bahan Baku</span>
            </div>
            <div class="doc-title-box">
                <div class="doc-title">SURAT PERINTAH KERJA</div>
                <div class="doc-sub">SPK PRODUKSI SOUVENIR &amp; SERAH TERIMA BAHAN</div>
            </div>
        </div>

        {{-- INFORMASI PESANAN & PRODUKSI --}}
        <div class="info-grid">
            <div>
                <div class="info-block-title">Identitas Dokumen & Saluran</div>
                <div class="info-row">
                    <span class="info-label">No. SPK / Pesanan</span>
                    <span class="info-value">{{ $transaksi->kode_transaksi }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">No. Pesanan Marketplace</span>
                    <span class="info-value">{{ $transaksi->no_pesanan_marketplace ?: '-' }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Marketplace</span>
                    <span class="info-value">
                        <span class="badge-channel">{{ $transaksi->channel_label }}</span>
                    </span>
                </div>
                <div class="info-row">
                    <span class="info-label">Status Saat Ini</span>
                    <span class="info-value">
                        <span class="badge-status">{{ $transaksi->status_pesanan_label }}</span>
                    </span>
                </div>
            </div>

            <div>
                <div class="info-block-title">Instruksi & Waktu Pengerjaan</div>
                <div class="info-row">
                    <span class="info-label">Tanggal Masuk</span>
                    <span class="info-value">{{ $transaksi->tanggal ? $transaksi->tanggal->translatedFormat('d F Y') : '-' }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Nama Pelanggan</span>
                    <span class="info-value">{{ $transaksi->nama_pelanggan ?: 'Pelanggan Online' }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Admin Pemroses</span>
                    <span class="info-value">{{ $transaksi->user->name ?? 'Admin Toko' }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Ekspedisi Eksternal</span>
                    <span class="info-value">{{ $transaksi->kurir ? strtoupper($transaksi->kurir) : 'Standar Marketplace' }}</span>
                </div>
            </div>
        </div>

        {{-- 1. TABEL ITEM SOUVENIR JADI YANG HARUS DIBUAT --}}
        <div class="section-header">
            <i class="fas fa-gift text-primary"></i> 1. Rincian Souvenir Jadi yang Dikerjakan (Target Output)
        </div>
        <div class="table-wrap">
            <table>
                <thead>
                    <tr>
                        <th style="width: 35px;" class="text-center">No</th>
                        <th>Souvenir / Item Jadi</th>
                        <th style="width: 120px;" class="text-center">Target Qty</th>
                        <th style="width: 220px;" class="text-center">Checklist Kontrol Kualitas (QC)</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($transaksi->details as $index => $detail)
                        <tr>
                            <td class="text-center" style="font-weight: 600; color: #64748b;">
                                {{ $index + 1 }}
                            </td>
                            <td>
                                <div class="item-name">{{ $detail->produk->nama_produk ?? 'Souvenir Custom' }}</div>
                                <div class="item-code">KODE: {{ $detail->produk->kode_produk ?? '-' }}</div>
                                @if($detail->catatan)
                                    <div style="font-size: 11px; color: #b45309; margin-top: 3px; font-weight: 500;">
                                        Instruksi: {{ $detail->catatan }}
                                    </div>
                                @endif
                            </td>
                            <td class="text-center">
                                <span class="qty-badge">
                                    {{ number_format($detail->qty) }} {{ $detail->produk->satuan->singkatan ?? 'pcs' }}
                                </span>
                            </td>
                            <td>
                                <div style="display: flex; flex-direction: column; gap: 4px;">
                                    <div class="qc-box">
                                        <span class="checkbox-square"></span>
                                        <span>Desain / Sablon Sesuai File</span>
                                    </div>
                                    <div class="qc-box">
                                        <span class="checkbox-square"></span>
                                        <span>Bebas Cacat / Gores / Retak</span>
                                    </div>
                                    <div class="qc-box">
                                        <span class="checkbox-square"></span>
                                        <span>Packing Kardus &amp; Bubblewrap Rapi</span>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center" style="padding: 20px; color: #94a3b8;">
                                Tidak ada data souvenir pada pesanan ini.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- CATATAN KHUSUS / INSTRUKSI PELANGGAN --}}
        @if($transaksi->catatan)
            <div class="notes-card">
                <div class="notes-title">Instruksi Khusus Desain / Permintaan Pelanggan</div>
                <div class="notes-body">{{ $transaksi->catatan }}</div>
            </div>
        @endif

        {{-- 2. LEMBAR SERAH TERIMA BAHAN BAKU DARI GUDANG KE WORKSHOP --}}
        <div class="section-header">
            <i class="fas fa-dolly text-primary"></i> 2. Formulir Serah Terima Bahan Baku (Gudang &rarr; Tim Workshop)
        </div>
        <div class="handover-card">
            <div class="handover-title">
                <i class="fas fa-clipboard-check"></i> Rincian Bahan Mentah Sesuai Resep (BOM) &amp; Perlengkapan Kemasan
            </div>
            <table class="handover-table">
                <thead>
                    <tr>
                        <th style="width: 30px;" class="text-center">No</th>
                        <th>Bahan Mentah / Packaging Diambil Dari Gudang</th>
                        <th style="width: 120px;" class="text-center">Kebutuhan SPK</th>
                        <th style="width: 100px;" class="text-center">Aktual Diambil</th>
                        <th style="width: 110px;" class="text-center">Kondisi Bahan</th>
                    </tr>
                </thead>
                <tbody>
                    @php $kebutuhanBahan = $transaksi->rincian_kebutuhan_bahan; @endphp
                    @forelse($kebutuhanBahan as $idx => $b)
                        <tr>
                            <td class="text-center">{{ $idx + 1 }}</td>
                            <td>
                                <strong>{{ $b['nama_bahan'] }}</strong>
                                <span style="font-size: 10px; color: #64748b; font-family: monospace;">({{ $b['kode_bahan'] }})</span>
                            </td>
                            <td class="text-center" style="font-weight: 700; color: #0284c7;">
                                {{ number_format($b['qty_kebutuhan'], $b['qty_kebutuhan'] == (int)$b['qty_kebutuhan'] ? 0 : 2) }} {{ $b['satuan'] }}
                            </td>
                            <td class="text-center" style="color: #94a3b8;">....... {{ $b['satuan'] }}</td>
                            <td class="text-center" style="color: #64748b;">[ &nbsp; ] Baik &nbsp; [ &nbsp; ] Cacat</td>
                        </tr>
                    @empty
                        <tr>
                            <td class="text-center">1</td>
                            <td>Blanko Utama (Mug Polos / Akrilik Potong / Gantungan Kunci Krawangan)</td>
                            <td class="text-center" style="color: #64748b;">Sesuai Qty SPK</td>
                            <td class="text-center" style="color: #94a3b8;">....... pcs</td>
                            <td class="text-center" style="color: #64748b;">[ &nbsp; ] Baik &nbsp; [ &nbsp; ] Cacat</td>
                        </tr>
                        <tr>
                            <td class="text-center">2</td>
                            <td>Material Pendukung (Ring Gantungan, Kertas Sublim, Tinta, Mika)</td>
                            <td class="text-center" style="color: #64748b;">Secukupnya</td>
                            <td class="text-center" style="color: #94a3b8;">....... unit</td>
                            <td class="text-center" style="color: #64748b;">[ &nbsp; ] Baik &nbsp; [ &nbsp; ] Cacat</td>
                        </tr>
                        <tr>
                            <td class="text-center">3</td>
                            <td>Perlengkapan Kemasan (Box Kardus Khusus, Plastik OPP, Pita, Bubblewrap)</td>
                            <td class="text-center" style="color: #64748b;">Sesuai Qty SPK</td>
                            <td class="text-center" style="color: #94a3b8;">....... set</td>
                            <td class="text-center" style="color: #64748b;">[ &nbsp; ] Baik &nbsp; [ &nbsp; ] Cacat</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- TANDA TANGAN 4 PIHAK KOORDINASI LENGKAP --}}
        <div class="signatures">
            <div class="sign-col">
                <div class="sign-role">Diterbitkan Oleh (Admin)</div>
                <div class="sign-line"></div>
                <div class="sign-name">{{ $transaksi->user->name ?? 'Admin Toko' }}</div>
            </div>
            <div class="sign-col">
                <div class="sign-role">Diserahkan (Gudang Bahan)</div>
                <div class="sign-line"></div>
                <div class="sign-name">( ................................ )</div>
            </div>
            <div class="sign-col">
                <div class="sign-role">Pegawai Produksi (Workshop)</div>
                <div class="sign-line"></div>
                <div class="sign-name">( ................................ )</div>
            </div>
            <div class="sign-col">
                <div class="sign-role">Verifikasi Hasil Jadi (QC)</div>
                <div class="sign-line"></div>
                <div class="sign-name">( ................................ )</div>
            </div>
        </div>

    </div>

</body>
</html>
