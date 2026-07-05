<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;

class LaporanExport implements FromArray, WithHeadings, WithTitle
{
    protected $laporan;

    public function __construct(array $laporan)
    {
        $this->laporan = $laporan;
    }

    public function array(): array
    {
        return array_map(function ($item) {
            return [
                $item['periode'],
                $item['aktual'] ?? '-',
                $item['prediksi_1_tahun'] ?? '-',
                isset($item['error_persen_1_tahun']) ? $item['error_persen_1_tahun'] . '%' : '-',
                $item['prediksi_rata_rata'] ?? '-',
                isset($item['error_persen_rata_rata']) ? $item['error_persen_rata_rata'] . '%' : '-',
            ];
        }, $this->laporan);
    }

    public function headings(): array
    {
        return [
            'Periode',
            'Jumlah Aktual',
            'Prediksi 1 Thn',
            'Error (%) 1 Thn',
            'Prediksi Rata‑rata',
            'Error (%) Rata‑rata',
        ];
    }

    public function title(): string
    {
        return 'Laporan Peramalan';
    }
}