<?php

namespace App\Imports;

use App\Models\Penduduk;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class PendudukImport implements ToModel, WithHeadingRow
{
    public function model(array $row)
    {
        // Abaikan baris jika periode kosong
        if (empty($row['periode'])) {
            return null;
        }

        // Siapkan data, konversi kosong ke null
        $data = [
            'periode'         => $row['periode'],
            'kelahiran'       => $this->nullIfEmpty($row['kelahiran'] ?? null),
            'kematian'        => $this->nullIfEmpty($row['kematian'] ?? null),
            'migrasi_masuk'   => $this->nullIfEmpty($row['migrasi_masuk'] ?? null),
            'migrasi_keluar'  => $this->nullIfEmpty($row['migrasi_keluar'] ?? null),
            'jumlah_penduduk' => $this->nullIfEmpty($row['penduduk'] ?? null),
        ];

        // Gunakan updateOrCreate agar periode yang sama ter-update
        return Penduduk::updateOrCreate(
            ['periode' => $data['periode']],
            $data
        );
    }

    /**
     * Ubah string kosong menjadi null
     */
    private function nullIfEmpty($value)
    {
        return (is_null($value) || $value === '') ? null : $value;
    }
}