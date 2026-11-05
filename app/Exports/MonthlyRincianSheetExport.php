<?php

namespace App\Exports;

use App\Models\KasMasuk;
use App\Models\Pengeluaran;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class MonthlyRincianSheetExport implements FromArray, WithColumnWidths, WithEvents, WithHeadings, WithStyles, WithTitle
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
                'keterangan' => $km->keterangan ?: 'Penerimaan Modal / Kas Masuk',
                'is_modal' => false,
            ]);
        }

        foreach ($pengeluaran as $pg) {
            $isModal = in_array($pg->kategori, ['bahan_baku', 'jasa_produksi', 'perlengkapan'], true);

            $ket = $pg->keterangan;
            if ($pg->tipe_alokasi === 'kasbon') {
                $nama = $pg->karyawan_nama ? strtoupper($pg->karyawan_nama).' [KASBON]' : 'KASBON KARYAWAN';
                $ket = $nama.($ket ? ' - '.$ket : '');
            } elseif ($pg->tipe_alokasi === 'pribadi') {
                $ket = '[PRIVE] '.($ket ?: $pg->kategori_label);
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

        foreach ($sorted as $item) {
            $operasional = null;
            $modal = null;
            $penerimaan = null;

            $tglStr = $item['tanggal'] instanceof Carbon ? $item['tanggal']->format('d/m/Y') : date('d/m/Y', strtotime((string) $item['tanggal']));

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

            $this->rows[] = [
                $no++,
                $tglStr,
                $operasional,
                $modal,
                $penerimaan,
                $item['keterangan'],
                $runningSaldo,
            ];
        }

        $this->saldoAkhir = $runningSaldo;

        // Baris Ringkasan Total
        $this->rows[] = [
            '',
            'TOTAL',
            $this->totalOperasional,
            $this->totalModal,
            $this->totalPenerimaan,
            'TOTAL BELANJA (MODAL + OPERASIONAL): Rp '.number_format($this->totalOperasional + $this->totalModal, 0, ',', '.'),
            $this->saldoAkhir,
        ];
    }

    public function array(): array
    {
        return $this->rows;
    }

    public function title(): string
    {
        return Carbon::create(null, $this->bulan, 1)->translatedFormat('F');
    }

    public function headings(): array
    {
        return [
            'NO',
            'TANGGAL',
            'OPERASIONAL',
            'MODAL',
            'PENERIMAAN DEBIT',
            'KETERANGAN',
            'SALDO TOTAL / HARI',
        ];
    }

    public function columnWidths(): array
    {
        return [
            'A' => 6,
            'B' => 14,
            'C' => 20,
            'D' => 20,
            'E' => 22,
            'F' => 45,
            'G' => 22,
        ];
    }

    public function styles(Worksheet $sheet): ?array
    {
        // Sisipkan 3 baris di awal untuk Header Judul File Klien
        $sheet->insertNewRowBefore(1, 3);

        $namaBulan = strtoupper(Carbon::create(null, $this->bulan, 1)->translatedFormat('F'));
        $sheet->mergeCells('A1:G1');
        $sheet->setCellValue('A1', "RINCIAN PEMBELANJAAN BULAN {$namaBulan} {$this->tahun}");
        $sheet->getStyle('A1')->applyFromArray([
            'font' => ['bold' => true, 'size' => 14, 'color' => ['rgb' => '1A304D']],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
        ]);

        $sheet->mergeCells('A2:G2');
        $sheet->setCellValue('A2', 'Buku Kas Rincian Toko Souvenir — Format Rincian Belanja & Penerimaan Kas');
        $sheet->getStyle('A2')->applyFromArray([
            'font' => ['italic' => true, 'size' => 9.5, 'color' => ['rgb' => '64748B']],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
        ]);

        return [];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();
                $highestRow = $sheet->getHighestRow();
                $headerRow = 4;

                // Style Table Headings
                $sheet->getStyle("A{$headerRow}:G{$headerRow}")->applyFromArray([
                    'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF'], 'size' => 10],
                    'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => '1A304D']],
                    'alignment' => [
                        'horizontal' => Alignment::HORIZONTAL_CENTER,
                        'vertical' => Alignment::VERTICAL_CENTER,
                    ],
                ]);
                $sheet->getRowDimension($headerRow)->setRowHeight(26);

                // Format Angka & Penjajaran Data Baris
                if ($highestRow > $headerRow) {
                    $dataStart = $headerRow + 1;
                    $dataEnd = $highestRow - 1; // Baris sebelum baris TOTAL

                    if ($dataEnd >= $dataStart) {
                        $sheet->getStyle("A{$dataStart}:A{$dataEnd}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                        $sheet->getStyle("B{$dataStart}:B{$dataEnd}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                        $sheet->getStyle("C{$dataStart}:E{$dataEnd}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);
                        $sheet->getStyle("F{$dataStart}:F{$dataEnd}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_LEFT);
                        $sheet->getStyle("G{$dataStart}:G{$dataEnd}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);

                        // Number format untuk kolom nominal Rupiah
                        $sheet->getStyle("C{$dataStart}:E{$dataEnd}")->getNumberFormat()->setFormatCode('#,##0');
                        $sheet->getStyle("G{$dataStart}:G{$dataEnd}")->getNumberFormat()->setFormatCode('#,##0');

                        // Border tipis seluruh isi data
                        $sheet->getStyle("A{$dataStart}:G{$dataEnd}")->applyFromArray([
                            'borders' => [
                                'allBorders' => [
                                    'borderStyle' => Border::BORDER_THIN,
                                    'color' => ['rgb' => 'E2E8F0'],
                                ],
                            ],
                        ]);
                    }

                    // Style Baris TOTAL Terakhir
                    $sheet->getStyle("A{$highestRow}:G{$highestRow}")->applyFromArray([
                        'font' => ['bold' => true, 'color' => ['rgb' => '0F172A'], 'size' => 10],
                        'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => 'E0F2FE']],
                        'borders' => [
                            'top' => ['borderStyle' => Border::BORDER_THIN, 'color' => ['rgb' => '0284C7']],
                            'bottom' => ['borderStyle' => Border::BORDER_DOUBLE, 'color' => ['rgb' => '0284C7']],
                        ],
                    ]);
                    $sheet->getStyle("C{$highestRow}:E{$highestRow}")->getNumberFormat()->setFormatCode('#,##0');
                    $sheet->getStyle("G{$highestRow}")->getNumberFormat()->setFormatCode('#,##0');
                    $sheet->getStyle("B{$highestRow}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                    $sheet->getRowDimension($highestRow)->setRowHeight(24);
                }
            },
        ];
    }
}
