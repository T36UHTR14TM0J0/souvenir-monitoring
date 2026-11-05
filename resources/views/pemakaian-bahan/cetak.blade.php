<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bon Pengambilan Bahan - {{ $pemakaianBahan->kode_pemakaian }}</title>
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
            width: 150px;
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
        <h2>BON PENGAMBILAN BAHAN BAKU WORKSHOP</h2>
        <p>SOUVENIR MONITORING &amp; PRODUCTION SYSTEM</p>
    </div>

    <table class="info-table">
        <tr>
            <td class="label">No. Pengambilan:</td>
            <td><strong>{{ $pemakaianBahan->kode_pemakaian }}</strong></td>
            <td class="label">Tanggal Ambil:</td>
            <td>{{ $pemakaianBahan->tanggal->translatedFormat('d F Y') }}</td>
        </tr>
        <tr>
            <td class="label">Operator Pengambil:</td>
            <td><strong>{{ $pemakaianBahan->nama_operator }}</strong></td>
            <td class="label">Petugas Gudang:</td>
            <td>{{ $pemakaianBahan->user->name ?? 'Admin' }}</td>
        </tr>
        <tr>
            <td class="label">Keperluan / Pesanan:</td>
            <td colspan="3">{{ $pemakaianBahan->keperluan }}</td>
        </tr>
        @if($pemakaianBahan->catatan)
        <tr>
            <td class="label">Catatan:</td>
            <td colspan="3"><em>{{ $pemakaianBahan->catatan }}</em></td>
        </tr>
        @endif
    </table>

    <table class="items-table">
        <thead>
            <tr>
                <th style="width: 35px;">No</th>
                <th style="width: 110px;">Kode Bahan</th>
                <th>Nama Material / Blanko</th>
                <th style="width: 80px;">Kuantitas</th>
                <th style="width: 70px;">Satuan</th>
                <th>Keterangan Tambahan</th>
            </tr>
        </thead>
        <tbody>
            @foreach($pemakaianBahan->details as $index => $item)
            <tr>
                <td style="text-align: center;">{{ $index + 1 }}</td>
                <td style="font-family: monospace; text-align: center;">{{ $item->produk->kode_produk ?? '-' }}</td>
                <td><strong>{{ $item->nama_bahan }}</strong></td>
                <td style="text-align: center; font-weight: bold;">{{ number_format($item->qty) }}</td>
                <td style="text-align: center;">{{ $item->satuan }}</td>
                <td>{{ $item->keterangan ?: '-' }}</td>
            </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr style="background-color: #f9f9f9;">
                <td colspan="3" style="text-align: right; font-weight: bold;">TOTAL UNIT BAHAN KELUAR:</td>
                <td style="text-align: center; font-weight: bold;">{{ number_format($pemakaianBahan->total_kuantitas) }}</td>
                <td colspan="2">Unit Material</td>
            </tr>
        </tfoot>
    </table>

    <table class="signatures">
        <tr>
            <td>
                <div>Diserahkan Oleh (Petugas Gudang),</div>
                <div class="sig-space"></div>
                <div class="sig-line"></div>
                <div>( {{ $pemakaianBahan->user->name ?? 'Petugas Gudang' }} )</div>
            </td>
            <td>
                <div>Diterima Oleh (Operator Produksi),</div>
                <div class="sig-space"></div>
                <div class="sig-line"></div>
                <div>( {{ $pemakaianBahan->nama_operator }} )</div>
            </td>
        </tr>
    </table>

</body>
</html>
