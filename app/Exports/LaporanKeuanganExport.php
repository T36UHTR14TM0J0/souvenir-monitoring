<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;

class LaporanKeuanganExport implements FromArray, WithHeadings, WithStyles, WithTitle, WithColumnWidths, WithEvents
{
    protected $data;
    protected $tab;
    protected $judul;
    protected $periode;

    public function __construct(array $data, string $tab, string $judul, string $periode)
    {
        $this->data    = $data;
        $this->tab     = $tab;
        $this->judul   = $judul;
        $this->periode = $periode;
    }

    public function array(): array
    {
        return $this->data;
    }

    public function headings(): array
    {
        return match ($this->tab) {
            'laba-rugi'   => ['Bulan', 'Penjualan', 'Pembelian', 'Laba Kotor', 'Pengeluaran', 'Laba Bersih'],
            'penjualan'   => ['Kode', 'Tanggal', 'Pelanggan', 'Item', 'Diskon', 'Grand Total'],
            'pembelian'   => ['Kode', 'Tanggal', 'Supplier', 'Item', 'Diskon', 'Grand Total'],
            'pengeluaran' => ['Kode', 'Tanggal', 'Kategori', 'Keterangan', 'Jumlah'],
            default       => [],
        };
    }

    public function title(): string
    {
        return 'Laporan ' . ucfirst(str_replace('-', '/', $this->tab));
    }

    public function columnWidths(): array
    {
        return match ($this->tab) {
            'laba-rugi'   => ['A' => 15, 'B' => 20, 'C' => 20, 'D' => 20, 'E' => 20, 'F' => 20],
            'penjualan'   => ['A' => 20, 'B' => 15, 'C' => 25, 'D' => 8,  'E' => 18, 'F' => 20],
            'pembelian'   => ['A' => 20, 'B' => 15, 'C' => 25, 'D' => 8,  'E' => 18, 'F' => 20],
            'pengeluaran' => ['A' => 20, 'B' => 15, 'C' => 20, 'D' => 40, 'E' => 20],
            default       => [],
        };
    }

    public function styles(Worksheet $sheet): ?array
    {
        // Insert judul di atas (4 baris kosong)
        $sheet->insertNewRowBefore(1, 4);

        // Judul utama
        $sheet->mergeCells('A1:F1');
        $sheet->setCellValue('A1', 'LAPORAN KEUANGAN TOKO SOUVENIR');
        $sheet->getStyle('A1')->applyFromArray([
            'font'      => ['bold' => true, 'size' => 16, 'color' => ['rgb' => '26476F']],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
        ]);

        // Subjudul
        $sheet->mergeCells('A2:F2');
        $sheet->setCellValue('A2', $this->judul . ' — ' . $this->periode);
        $sheet->getStyle('A2')->applyFromArray([
            'font'      => ['bold' => true, 'size' => 12, 'color' => ['rgb' => '4B5563']],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
        ]);

        // Meta info
        $sheet->mergeCells('A3:F3');
        $sheet->setCellValue('A3', 'Dicetak: ' . now()->translatedFormat('d F Y, H:i') . ' WIB');
        $sheet->getStyle('A3')->applyFromArray([
            'font'      => ['italic' => true, 'size' => 9, 'color' => ['rgb' => '6B7280']],
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
                $headingRow = 5;
                $lastColumn = match ($this->tab) {
                    'laba-rugi', 'penjualan', 'pembelian' => 'F',
                    'pengeluaran'                          => 'E',
                    default                                => 'E',
                };

                // Style header row (row 5)
                $sheet->getStyle("A{$headingRow}:{$lastColumn}{$headingRow}")->applyFromArray([
                    'font' => [
                        'bold'  => true,
                        'color' => ['rgb' => 'FFFFFF'],
                        'size'  => 10,
                    ],
                    'fill' => [
                        'fillType'   => Fill::FILL_SOLID,
                        'startColor' => ['rgb' => '26476F'],
                    ],
                    'alignment' => [
                        'horizontal' => Alignment::HORIZONTAL_CENTER,
                        'vertical'   => Alignment::VERTICAL_CENTER,
                    ],
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => Border::BORDER_THIN,
                            'color'       => ['rgb' => '1A304D'],
                        ],
                    ],
                ]);

                // Border semua data
                $sheet->getStyle("A{$headingRow}:{$lastColumn}{$highestRow}")->applyFromArray([
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => Border::BORDER_THIN,
                            'color'       => ['rgb' => 'E5E7EB'],
                        ],
                    ],
                ]);

                // Zebra striping (baris genap abu muda)
                for ($row = $headingRow + 1; $row <= $highestRow; $row++) {
                    if (($row - $headingRow) % 2 === 0) {
                        $sheet->getStyle("A{$row}:{$lastColumn}{$row}")->applyFromArray([
                            'fill' => [
                                'fillType'   => Fill::FILL_SOLID,
                                'startColor' => ['rgb' => 'F9FAFB'],
                            ],
                        ]);
                    }
                }

                // Bold baris terakhir (tfoot/TOTAL)
                $sheet->getStyle("A{$highestRow}:{$lastColumn}{$highestRow}")->applyFromArray([
                    'font' => ['bold' => true, 'color' => ['rgb' => '111827']],
                    'fill' => [
                        'fillType'   => Fill::FILL_SOLID,
                        'startColor' => ['rgb' => 'E5E7EB'],
                    ],
                ]);

                // Format kolom angka (mata uang)
                $currencyColumns = match ($this->tab) {
                    'laba-rugi'   => ['B', 'C', 'D', 'E', 'F'],
                    'penjualan'   => ['E', 'F'],
                    'pembelian'   => ['E', 'F'],
                    'pengeluaran' => ['E'],
                    default       => [],
                };

                foreach ($currencyColumns as $col) {
                    $sheet->getStyle("{$col}" . ($headingRow + 1) . ":{$col}{$highestRow}")
                        ->getNumberFormat()
                        ->setFormatCode('#,##0');
                }

                // Header row height
                $sheet->getRowDimension($headingRow)->setRowHeight(28);

                // Freeze pane di bawah header
                $sheet->freezePane("A" . ($headingRow + 1));

                // Auto filter
                $sheet->setAutoFilter("A{$headingRow}:{$lastColumn}{$highestRow}");

                // Page setup (landscape A4)
                $sheet->getPageSetup()
                    ->setOrientation(\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::ORIENTATION_LANDSCAPE)
                    ->setPaperSize(\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::PAPERSIZE_A4);

                $sheet->getPageSetup()->setFitToWidth(1);
                $sheet->getPageSetup()->setFitToHeight(0);
            },
        ];
    }
}