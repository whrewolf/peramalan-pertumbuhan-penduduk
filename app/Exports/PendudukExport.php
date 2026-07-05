<?php

namespace App\Exports;

use App\Models\Penduduk;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class PendudukExport implements FromCollection, WithHeadings, WithMapping
{
    public function collection()
    {
        return Penduduk::orderBy('periode')->get();
    }

    public function headings(): array
    {
        return [
            'Periode',
            'Kelahiran',
            'Kematian',
            'Migrasi Masuk',
            'Migrasi Keluar',
            'Penduduk',
        ];
    }

    public function map($penduduk): array
    {
        return [
            $penduduk->periode,
            $penduduk->kelahiran,
            $penduduk->kematian,
            $penduduk->migrasi_masuk,
            $penduduk->migrasi_keluar,
            $penduduk->jumlah_penduduk,
        ];
    }
}