<?php

namespace App\Exports;

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
use PhpOffice\PhpSpreadsheet\Worksheet\PageSetup;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class LaporanKeuanganExport implements FromArray, WithColumnWidths, WithEvents, WithHeadings, WithStyles, WithTitle
{
    public function __construct(
        protected array $data,
        protected string $tab,
        protected string $judul,
        protected string $periode
    ) {}

    public function array(): array
    {
        return $this->data;
    }

    public function headings(): array
    {
        return match ($this->tab) {
            'laba-rugi' => [
                'Periode',
                'Omzet Kotor',
                'Potongan Admin MP',
                'Penghasilan Bersih',
                'Pembelian Bahan (HPP)',
                'Laba Kotor',
                'Biaya Operasional & Ads',
                'Laba Bersih',
            ],
            'penjualan' => [
                'Kode',
                'Tanggal',
                'Channel',
                'Pelanggan',
                'Status',
                'Qty Item',
                'Omzet Kotor',
                'Potongan Admin',
                'Penghasilan Bersih',
            ],
            'pembelian' => [
                'Kode',
                'Tanggal',
                'Supplier / Toko',
                'Platform',
                'Status Paket',
                'Qty Item',
                'Ongkir',
                'Grand Total',
            ],
            'pengeluaran' => ['Kode', 'Tanggal', 'Kategori Bisnis', 'Keterangan', 'Jumlah'],
            'produksi' => ['Kode Penerimaan', 'Tanggal', 'Petugas QC', 'Tipe Produksi', 'Qty Bagus (Lolos)', 'Qty Reject (Cacat)'],
            'pribadi' => ['Kode', 'Tanggal', 'Kategori Pribadi', 'Keterangan', 'Jumlah'],
            'rekonsiliasi' => ['Bulan', 'Modal Disetor', 'Pendapatan Lain / Pencairan', 'Prive Ditarik', 'Selisih Arus Kas'],
            'kasbon' => ['Kode', 'Tanggal', 'Nama Peminjam', 'Keterangan', 'Jumlah', 'Status'],
            default => [],
        };
    }

    public function title(): string
    {
        return 'Laporan '.ucfirst(str_replace('-', ' ', $this->tab));
    }

    public function columnWidths(): array
    {
        return match ($this->tab) {
            'laba-rugi' => ['A' => 18, 'B' => 18, 'C' => 18, 'D' => 20, 'E' => 20, 'F' => 18, 'G' => 22, 'H' => 20],
            'penjualan' => ['A' => 18, 'B' => 14, 'C' => 14, 'D' => 22, 'E' => 16, 'F' => 10, 'G' => 18, 'H' => 18, 'I' => 20],
            'pembelian' => ['A' => 18, 'B' => 14, 'C' => 22, 'D' => 16, 'E' => 16, 'F' => 10, 'G' => 16, 'H' => 20],
            'pengeluaran', 'pribadi' => ['A' => 20, 'B' => 15, 'C' => 25, 'D' => 40, 'E' => 20],
            'produksi' => ['A' => 20, 'B' => 15, 'C' => 22, 'D' => 20, 'E' => 18, 'F' => 18],
            'rekonsiliasi' => ['A' => 18, 'B' => 20, 'C' => 24, 'D' => 20, 'E' => 20],
            'kasbon' => ['A' => 20, 'B' => 15, 'C' => 22, 'D' => 35, 'E' => 20, 'F' => 18],
            default => [],
        };
    }

    private function getLastColumn(): string
    {
        return match ($this->tab) {
            'penjualan' => 'I',
            'laba-rugi', 'pembelian' => 'H',
            'produksi', 'kasbon' => 'F',
            'pengeluaran', 'pribadi', 'rekonsiliasi' => 'E',
            default => 'F',
        };
    }

    public function styles(Worksheet $sheet): ?array
    {
        $lastCol = $this->getLastColumn();

        $sheet->insertNewRowBefore(1, 4);

        $sheet->mergeCells("A1:{$lastCol}1");
        $sheet->setCellValue('A1', 'LAPORAN KEUANGAN & OPERASIONAL TOKO SOUVENIR');
        $sheet->getStyle('A1')->applyFromArray([
            'font' => ['bold' => true, 'size' => 15, 'color' => ['rgb' => '26476F']],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
        ]);

        $sheet->mergeCells("A2:{$lastCol}2");
        $sheet->setCellValue('A2', $this->judul.' — '.$this->periode);
        $sheet->getStyle('A2')->applyFromArray([
            'font' => ['bold' => true, 'size' => 11, 'color' => ['rgb' => '4B5563']],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
        ]);

        $sheet->mergeCells("A3:{$lastCol}3");
        $sheet->setCellValue('A3', 'Dicetak: '.now()->translatedFormat('d F Y, H:i').' WIB');
        $sheet->getStyle('A3')->applyFromArray([
            'font' => ['italic' => true, 'size' => 9, 'color' => ['rgb' => '6B7280']],
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
                $lastColumn = $this->getLastColumn();

                $sheet->getStyle("A{$headingRow}:{$lastColumn}{$headingRow}")->applyFromArray([
                    'font' => [
                        'bold' => true,
                        'color' => ['rgb' => 'FFFFFF'],
                        'size' => 10,
                    ],
                    'fill' => [
                        'fillType' => Fill::FILL_SOLID,
                        'startColor' => ['rgb' => '26476F'],
                    ],
                    'alignment' => [
                        'horizontal' => Alignment::HORIZONTAL_CENTER,
                        'vertical' => Alignment::VERTICAL_CENTER,
                    ],
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => Border::BORDER_THIN,
                            'color' => ['rgb' => '1A304D'],
                        ],
                    ],
                ]);

                $sheet->getStyle("A{$headingRow}:{$lastColumn}{$highestRow}")->applyFromArray([
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => Border::BORDER_THIN,
                            'color' => ['rgb' => 'E5E7EB'],
                        ],
                    ],
                ]);

                for ($row = $headingRow + 1; $row <= $highestRow; $row++) {
                    if (($row - $headingRow) % 2 === 0) {
                        $sheet->getStyle("A{$row}:{$lastColumn}{$row}")->applyFromArray([
                            'fill' => [
                                'fillType' => Fill::FILL_SOLID,
                                'startColor' => ['rgb' => 'F9FAFB'],
                            ],
                        ]);
                    }
                }

                $sheet->getStyle("A{$highestRow}:{$lastColumn}{$highestRow}")->applyFromArray([
                    'font' => ['bold' => true, 'color' => ['rgb' => '111827']],
                    'fill' => [
                        'fillType' => Fill::FILL_SOLID,
                        'startColor' => ['rgb' => 'E5E7EB'],
                    ],
                ]);

                $currencyColumns = match ($this->tab) {
                    'laba-rugi' => ['B', 'C', 'D', 'E', 'F', 'G', 'H'],
                    'penjualan' => ['G', 'H', 'I'],
                    'pembelian' => ['G', 'H'],
                    'pengeluaran', 'pribadi', 'kasbon' => ['E'],
                    'rekonsiliasi' => ['B', 'C', 'D', 'E'],
                    default => [],
                };

                foreach ($currencyColumns as $col) {
                    $sheet->getStyle("{$col}".($headingRow + 1).":{$col}{$highestRow}")
                        ->getNumberFormat()
                        ->setFormatCode('#,##0');
                }

                $sheet->getRowDimension($headingRow)->setRowHeight(28);
                $sheet->freezePane('A'.($headingRow + 1));
                $sheet->setAutoFilter("A{$headingRow}:{$lastColumn}{$highestRow}");

                $sheet->getPageSetup()
                    ->setOrientation(PageSetup::ORIENTATION_LANDSCAPE)
                    ->setPaperSize(PageSetup::PAPERSIZE_A4);

                $sheet->getPageSetup()->setFitToWidth(1);
                $sheet->getPageSetup()->setFitToHeight(0);
            },
        ];
    }
}
