<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\Export;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class RincianPembelanjaanClientExport implements Export, WithMultipleSheets
{
    public function __construct(
        protected string|int $bulan,
        protected int $tahun
    ) {}

    public function sheets(): array
    {
        $sheets = [];

        if ($this->bulan === 'all') {
            // Jika ekspor tahunan (semua bulan), buatkan 12 tab sheet Januari s.d. Desember
            for ($m = 1; $m <= 12; $m++) {
                $sheets[] = new MonthlyRincianSheetExport($m, $this->tahun);
            }
        } else {
            // Jika ekspor bulan tertentu, buatkan 1 sheet khusus bulan tersebut
            $sheets[] = new MonthlyRincianSheetExport((int) $this->bulan, $this->tahun);
        }

        return $sheets;
    }
}
