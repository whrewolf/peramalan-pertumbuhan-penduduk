<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Penduduk;
use Carbon\Carbon;

class PendudukSeeder extends Seeder
{
    public function run()
    {
        $data = [
            ['tahun' => 2016, 'jumlah_penduduk' => 10000, 'kelahiran' => 500, 'kematian' => 200, 'migrasi' => 100],
            ['tahun' => 2017, 'jumlah_penduduk' => 10500, 'kelahiran' => 520, 'kematian' => 210, 'migrasi' => 120],
            ['tahun' => 2018, 'jumlah_penduduk' => 11000, 'kelahiran' => 540, 'kematian' => 220, 'migrasi' => 130],
            ['tahun' => 2019, 'jumlah_penduduk' => 11600, 'kelahiran' => 560, 'kematian' => 230, 'migrasi' => 140],
            ['tahun' => 2020, 'jumlah_penduduk' => 12200, 'kelahiran' => 580, 'kematian' => 240, 'migrasi' => 150],
            ['tahun' => 2021, 'jumlah_penduduk' => 12800, 'kelahiran' => 600, 'kematian' => 250, 'migrasi' => 160],
            ['tahun' => 2022, 'jumlah_penduduk' => 13500, 'kelahiran' => 620, 'kematian' => 260, 'migrasi' => 170],
            ['tahun' => 2023, 'jumlah_penduduk' => 14200, 'kelahiran' => 640, 'kematian' => 270, 'migrasi' => 180],
            ['tahun' => 2024, 'jumlah_penduduk' => 15000, 'kelahiran' => 660, 'kematian' => 280, 'migrasi' => 190],
        ];

        foreach ($data as $item) {
            Penduduk::updateOrCreate(
                ['tahun' => $item['tahun']],
                [
                    'jumlah_penduduk' => $item['jumlah_penduduk'],
                    'kelahiran' => $item['kelahiran'],
                    'kematian' => $item['kematian'],
                    'migrasi' => $item['migrasi']
                ]
            );
        }
    }
}