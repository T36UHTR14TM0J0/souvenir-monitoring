<?php

namespace App\Exports;

use App\Models\KasMasuk;
use App\Models\Pengeluaran;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\Export;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithCustomStartCell;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;

class MonthlyRincianSheetExport implements Export, FromArray, WithColumnWidths, WithCustomStartCell, WithEvents, WithTitle
{
    protected array $rows = [];

    protected float $totalOperasional = 0.0;

    protected float $totalModal = 0.0;

    protected float $totalPenerimaan = 0.0;

    protected float $saldoAkhir = 0.0;

    public function __construct(
        protected int $bulan,
        protected int $tahun
    ) {
        $this->buildData();
    }

    private function buildData(): void
    {
        $kasMasuk = KasMasuk::whereMonth('tanggal', $this->bulan)
            ->whereYear('tanggal', $this->tahun)
            ->get();

        $pengeluaran = Pengeluaran::whereMonth('tanggal', $this->bulan)
            ->whereYear('tanggal', $this->tahun)
            ->get();

        $combined = collect();

        foreach ($kasMasuk as $km) {
            $combined->push([
                'type' => 'kas_masuk',
                'tanggal' => $km->tanggal,
                'id' => $km->id,
                'nominal' => (float) $km->jumlah,
                'keterangan' => $km->keterangan ?: 'PENERIMAAN SALDO / MODAL',
                'is_modal' => false,
            ]);
        }

        foreach ($pengeluaran as $pg) {
            $isModal = in_array($pg->kategori, ['bahan_baku', 'jasa_produksi', 'perlengkapan'], true);

            $ket = $pg->keterangan;
            if ($pg->tipe_alokasi === 'kasbon') {
                $nama = $pg->karyawan_nama ? strtoupper($pg->karyawan_nama).' [ KASBON ]' : 'KASBON KARYAWAN';
                if ($ket && ! str_contains(strtoupper($ket), strtoupper($pg->karyawan_nama ?? 'KASBON'))) {
                    $ket = $nama.' - '.$ket;
                } else {
                    $ket = $nama;
                }
            } elseif ($pg->tipe_alokasi === 'pribadi') {
                $ket = str_starts_with(strtoupper($ket ?: ''), '[ PRIVE ]') ? $ket : '[ PRIVE ] '.($ket ?: $pg->kategori_label);
            }

            $combined->push([
                'type' => 'pengeluaran',
                'tanggal' => $pg->tanggal,
                'id' => $pg->id,
                'nominal' => (float) $pg->jumlah,
                'keterangan' => strtoupper($ket ?: $pg->kategori_label),
                'is_modal' => $isModal,
            ]);
        }

        $sorted = $combined->sort(function ($a, $b) {
            $dateA = $a['tanggal'] instanceof Carbon ? $a['tanggal']->format('Y-m-d') : (string) $a['tanggal'];
            $dateB = $b['tanggal'] instanceof Carbon ? $b['tanggal']->format('Y-m-d') : (string) $b['tanggal'];
            $cmp = strcmp($dateA, $dateB);
            if ($cmp !== 0) {
                return $cmp;
            }

            return $a['id'] <=> $b['id'];
        })->values();

        $runningSaldo = 0.0;
        $no = 1;

        if ($sorted->isEmpty()) {
            $this->rows[] = [
                1,
                null,
                null,
                null,
                null,
                null,
                null,
                'TIDAK ADA PENGELUARAN / PENERIMAAN PADA BULAN INI',
                null,
                null,
                null,
                null,
            ];
        } else {
            foreach ($sorted as $item) {
                $operasional = null;
                $modal = null;
                $penerimaan = null;

                if ($item['type'] === 'kas_masuk') {
                    $penerimaan = $item['nominal'];
                    $runningSaldo += $penerimaan;
                    $this->totalPenerimaan += $penerimaan;
                } else {
                    if ($item['is_modal']) {
                        $modal = $item['nominal'];
                        $this->totalModal += $modal;
                    } else {
                        $operasional = $item['nominal'];
                        $this->totalOperasional += $operasional;
                    }
                    $runningSaldo -= $item['nominal'];
                }

                // Baris data dengan 12 kolom (A sampai L) sesuai struktur Excel asli klien
                $this->rows[] = [
                    $no++,
                    $operasional,
                    null, // Col C (di-merge dengan B)
                    $modal, // Col D (MODAL)
                    $penerimaan, // Col E (PENERIMAAN DEBIT)
                    null, // Col F (di-merge dengan E)
                    null, // Col G (di-merge dengan E)
                    $item['keterangan'], // Col H (KETERANGAN)
                    null, // Col I (di-merge dengan H)
                    null, // Col J (di-merge dengan H)
                    $runningSaldo, // Col K (SALDO TOTAL/HARI)
                    null, // Col L (di-merge dengan K)
                ];
            }
        }

        $this->saldoAkhir = $runningSaldo;

        // Baris TOTAL di bagian akhir tabel
        $this->rows[] = [
            'TOTAL',
            $this->totalOperasional,
            null,
            $this->totalModal,
            $this->totalPenerimaan,
            null,
            null,
            'TOTAL BELANJA (MODAL + OPERASIONAL): Rp '.number_format($this->totalOperasional + $this->totalModal, 0, ',', '.'),
            null,
            null,
            $this->saldoAkhir,
            null,
        ];
    }

    public function array(): array
    {
        return $this->rows;
    }

    public function startCell(): string
    {
        return 'A4';
    }

    public function title(): string
    {
        return Carbon::create(null, $this->bulan, 1)->translatedFormat('F');
    }

    public function columnWidths(): array
    {
        return [
            'A' => 7.5,
            'B' => 17.0,
            'C' => 1.5,
            'D' => 18.0,
            'E' => 18.0,
            'F' => 3.5,
            'G' => 3.5,
            'H' => 32.0,
            'I' => 3.5,
            'J' => 3.5,
            'K' => 18.0,
            'L' => 3.5,
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();
                $highestRow = $sheet->getHighestRow();

                // -------------------------------------------------------------
                // 1. BARIS 1: JUDUL UTAMA (Colspan A1:L1)
                // -------------------------------------------------------------
                $namaBulan = strtoupper(Carbon::create(null, $this->bulan, 1)->translatedFormat('F'));
                $sheet->setCellValue('A1', "RINCIAN PEMBELANJAAN BULAN {$namaBulan}");
                $sheet->mergeCells('A1:L1');
                $sheet->getStyle('A1:L1')->applyFromArray([
                    'font' => ['name' => 'Calibri', 'size' => 11, 'bold' => true, 'color' => ['rgb' => '000000']],
                    'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => '8EA9DB']],
                    'alignment' => [
                        'horizontal' => Alignment::HORIZONTAL_CENTER,
                        'vertical' => Alignment::VERTICAL_CENTER,
                    ],
                ]);
                $sheet->getRowDimension(1)->setRowHeight(25);

                // -------------------------------------------------------------
                // 2. BARIS 2 & 3: HEADINGS DENGAN EXACT COLSPAN & ROWSPAN ASLI KLIEN
                // -------------------------------------------------------------
                // A2:A3 -> NO (Rowspan 2 baris)
                $sheet->setCellValue('A2', 'NO');
                $sheet->mergeCells('A2:A3');
                $sheet->getStyle('A2:A3')->applyFromArray([
                    'font' => ['name' => 'Calibri', 'size' => 11, 'bold' => true, 'color' => ['rgb' => '000000']],
                    'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => 'BDD7EE']],
                    'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_CENTER],
                ]);

                // B2:D2 -> PENGELUARAN (Colspan 3 kolom)
                $sheet->setCellValue('B2', 'PENGELUARAN');
                $sheet->mergeCells('B2:D2');
                $sheet->getStyle('B2:D2')->applyFromArray([
                    'font' => ['name' => 'Calibri', 'size' => 11, 'bold' => true, 'color' => ['rgb' => '000000']],
                    'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => 'D6DCE4']],
                    'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_CENTER],
                ]);

                // B3:C3 -> OPERASIONAL (Colspan 2 kolom)
                $sheet->setCellValue('B3', 'OPERASIONAL');
                $sheet->mergeCells('B3:C3');
                $sheet->getStyle('B3:C3')->applyFromArray([
                    'font' => ['name' => 'Calibri', 'size' => 11, 'bold' => true, 'color' => ['rgb' => 'FFFFFF']],
                    'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => '4472C4']],
                    'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_CENTER],
                ]);

                // D3 -> MODAL
                $sheet->setCellValue('D3', 'MODAL');
                $sheet->getStyle('D3')->applyFromArray([
                    'font' => ['name' => 'Calibri', 'size' => 11, 'bold' => true, 'color' => ['rgb' => 'FFFFFF']],
                    'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => '4472C4']],
                    'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_CENTER],
                ]);

                // E2:G3 -> PENERIMAAN DEBIT (Colspan 3 kolom & Rowspan 2 baris)
                $sheet->setCellValue('E2', 'PENERIMAAN DEBIT');
                $sheet->mergeCells('E2:G3');
                $sheet->getStyle('E2:G3')->applyFromArray([
                    'font' => ['name' => 'Calibri', 'size' => 11, 'bold' => true, 'color' => ['rgb' => 'FFFFFF']],
                    'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => '4472C4']],
                    'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_CENTER],
                ]);

                // H2:J3 -> KETERANGAN (Colspan 3 kolom & Rowspan 2 baris)
                $sheet->setCellValue('H2', 'KETERANGAN');
                $sheet->mergeCells('H2:J3');
                $sheet->getStyle('H2:J3')->applyFromArray([
                    'font' => ['name' => 'Calibri', 'size' => 11, 'bold' => true, 'color' => ['rgb' => '000000']],
                    'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => 'BDD7EE']],
                    'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_CENTER],
                ]);

                // K2:L3 -> SALDO TOTAL/HARI (Colspan 2 kolom & Rowspan 2 baris)
                $sheet->setCellValue('K2', 'SALDO TOTAL/HARI');
                $sheet->mergeCells('K2:L3');
                $sheet->getStyle('K2:L3')->applyFromArray([
                    'font' => ['name' => 'Calibri', 'size' => 11, 'bold' => true, 'color' => ['rgb' => 'FFFFFF']],
                    'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => '4472C4']],
                    'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_CENTER],
                ]);

                $sheet->getRowDimension(2)->setRowHeight(20);
                $sheet->getRowDimension(3)->setRowHeight(20);

                // -------------------------------------------------------------
                // 3. MERGE CELLS PADA SETIAP BARIS DATA (Row 4 s.d. HighestRow)
                // -------------------------------------------------------------
                for ($r = 4; $r <= $highestRow; $r++) {
                    $sheet->mergeCells("B{$r}:C{$r}");
                    $sheet->mergeCells("E{$r}:G{$r}");
                    $sheet->mergeCells("H{$r}:J{$r}");
                    $sheet->mergeCells("K{$r}:L{$r}");

                    $sheet->getRowDimension($r)->setRowHeight(20);
                }

                // -------------------------------------------------------------
                // 4. FORMAT ANGKA & PENJAJARAN SELURUH DATA
                // -------------------------------------------------------------
                if ($highestRow >= 4) {
                    $sheet->getStyle("A4:A{$highestRow}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                    $sheet->getStyle("B4:C{$highestRow}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);
                    $sheet->getStyle("D4:D{$highestRow}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);
                    $sheet->getStyle("E4:G{$highestRow}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);
                    $sheet->getStyle("H4:J{$highestRow}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_LEFT);
                    $sheet->getStyle("K4:L{$highestRow}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);

                    // Number format Rupiah Excel
                    $sheet->getStyle("B4:D{$highestRow}")->getNumberFormat()->setFormatCode('#,##0');
                    $sheet->getStyle("E4:G{$highestRow}")->getNumberFormat()->setFormatCode('#,##0');
                    $sheet->getStyle("K4:L{$highestRow}")->getNumberFormat()->setFormatCode('#,##0');

                    // Border seluruh tabel dari A1 sampai L{highestRow}
                    $sheet->getStyle("A1:L{$highestRow}")->applyFromArray([
                        'borders' => [
                            'allBorders' => [
                                'borderStyle' => Border::BORDER_THIN,
                                'color' => ['rgb' => 'A6B9D0'],
                            ],
                        ],
                    ]);

                    // Style khusus baris TOTAL terakhir
                    $sheet->getStyle("A{$highestRow}:L{$highestRow}")->applyFromArray([
                        'font' => ['name' => 'Calibri', 'size' => 11, 'bold' => true, 'color' => ['rgb' => '000000']],
                        'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => 'BDD7EE']],
                        'borders' => [
                            'top' => ['borderStyle' => Border::BORDER_THIN, 'color' => ['rgb' => '4472C4']],
                            'bottom' => ['borderStyle' => Border::BORDER_DOUBLE, 'color' => ['rgb' => '4472C4']],
                        ],
                    ]);
                    $sheet->getStyle("A{$highestRow}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                }
            },
        ];
    }
}
