<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bon Penerimaan Hasil Produksi - {{ $penerimaanProduk->kode_penerimaan }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            color: #333;
            margin: 0;
            padding: 20px;
            background: #fff;
        }
        .header {
            text-align: center;
            border-bottom: 2px solid #333;
            padding-bottom: 10px;
            margin-bottom: 15px;
        }
        .header h2 {
            margin: 0;
            font-size: 18px;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        .header p {
            margin: 3px 0 0;
            font-size: 11px;
            color: #666;
        }
        .info-table {
            width: 100%;
            margin-bottom: 15px;
            border-collapse: collapse;
        }
        .info-table td {
            padding: 4px 6px;
            vertical-align: top;
        }
        .info-table td.label {
            width: 160px;
            font-weight: bold;
            color: #555;
        }
        .items-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        .items-table th, .items-table td {
            border: 1px solid #999;
            padding: 6px 8px;
        }
        .items-table th {
            background-color: #f2f2f2;
            font-weight: bold;
            text-align: center;
        }
        .signatures {
            width: 100%;
            margin-top: 30px;
            border-collapse: collapse;
        }
        .signatures td {
            width: 50%;
            text-align: center;
            vertical-align: top;
        }
        .sig-space {
            height: 60px;
        }
        .sig-line {
            display: inline-block;
            width: 180px;
            border-bottom: 1px solid #333;
            margin-top: 5px;
        }
        @media print {
            .no-print {
                display: none;
            }
            body {
                padding: 0;
            }
        }
    </style>
</head>
<body>

    <div class="no-print" style="margin-bottom: 15px; text-align: right;">
        <button onclick="window.print()" style="padding: 8px 16px; background: #26476f; color: #fff; border: none; border-radius: 4px; cursor: pointer; font-weight: bold;">
            Cetak Dokumen
        </button>
        <button onclick="window.close()" style="padding: 8px 16px; background: #64748b; color: #fff; border: none; border-radius: 4px; cursor: pointer;">
            Tutup
        </button>
    </div>

    <div class="header">
        <h2>BON PENERIMAAN HASIL PRODUKSI (READY STOCK / MTO)</h2>
        <p>SOUVENIR MONITORING &amp; PRODUCTION SYSTEM</p>
    </div>

    <table class="info-table">
        <tr>
            <td class="label">No. Penerimaan:</td>
            <td><strong>{{ $penerimaanProduk->kode_penerimaan }}</strong></td>
            <td class="label">Tanggal Masuk:</td>
            <td>{{ $penerimaanProduk->tanggal->translatedFormat('d F Y') }}</td>
        </tr>
        <tr>
            <td class="label">Tipe Produksi:</td>
            <td><strong>{{ $penerimaanProduk->tipe_produksi === 'pesanan_custom' ? 'Pesanan Custom (MTO)' : 'Ready Stock / Regular' }}</strong></td>
            <td class="label">Petugas Gudang:</td>
            <td>{{ $penerimaanProduk->user->name ?? 'Admin' }}</td>
        </tr>
        <tr>
            <td class="label">Penanggung Jawab QC:</td>
            <td><strong>{{ $penerimaanProduk->nama_penanggung_jawab }}</strong></td>
            <td class="label">Referensi Bon Bahan:</td>
            <td>{{ $penerimaanProduk->no_referensi_pemakaian ?: '-' }}</td>
        </tr>
        @if($penerimaanProduk->catatan)
        <tr>
            <td class="label">Catatan Tambahan:</td>
            <td colspan="3"><em>{{ $penerimaanProduk->catatan }}</em></td>
        </tr>
        @endif
    </table>

    <table class="items-table">
        <thead>
            <tr>
                <th style="width: 35px;">No</th>
                <th style="width: 110px;">Kode Produk</th>
                <th>Nama Produk Souvenir Jadi</th>
                <th style="width: 90px;">Lolos QC (Bagus)</th>
                <th style="width: 80px;">Reject</th>
                <th style="width: 70px;">Satuan</th>
                <th>Keterangan</th>
            </tr>
        </thead>
        <tbody>
            @foreach($penerimaanProduk->details as $index => $item)
            <tr>
                <td style="text-align: center;">{{ $index + 1 }}</td>
                <td style="font-family: monospace; text-align: center;">{{ $item->produk->kode_produk ?? '-' }}</td>
                <td><strong>{{ $item->nama_produk }}</strong></td>
                <td style="text-align: center; font-weight: bold; color: #166534;">+{{ number_format($item->qty_bagus) }}</td>
                <td style="text-align: center; color: #991b1b;">{{ $item->qty_reject > 0 ? number_format($item->qty_reject) : '-' }}</td>
                <td style="text-align: center;">{{ $item->satuan }}</td>
                <td>{{ $item->keterangan ?: '-' }}</td>
            </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr style="background-color: #f9f9f9;">
                <td colspan="3" style="text-align: right; font-weight: bold;">TOTAL HASIL PRODUKSI MASUK:</td>
                <td style="text-align: center; font-weight: bold; color: #166534;">+{{ number_format($penerimaanProduk->total_bagus) }}</td>
                <td style="text-align: center; font-weight: bold; color: #991b1b;">{{ number_format($penerimaanProduk->total_reject) }}</td>
                <td colspan="2">Unit Souvenir</td>
            </tr>
        </tfoot>
    </table>

    <table class="signatures">
        <tr>
            <td>
                <div>Diserahkan Oleh (Kepala QC / Workshop),</div>
                <div class="sig-space"></div>
                <div class="sig-line"></div>
                <div>( {{ $penerimaanProduk->nama_penanggung_jawab }} )</div>
            </td>
            <td>
                <div>Diterima &amp; Disimpan Oleh (Petugas Gudang),</div>
                <div class="sig-space"></div>
                <div class="sig-line"></div>
                <div>( {{ $penerimaanProduk->user->name ?? 'Petugas Gudang' }} )</div>
            </td>
        </tr>
    </table>

</body>
</html>
