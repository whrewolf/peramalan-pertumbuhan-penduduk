<?php

namespace Tests\Feature;

use App\Models\DataTervalidasi;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PeramalanTest extends TestCase
{
    use RefreshDatabase;

    private function seedDataDuaTahun()
    {
        // Data yang bervariasi setiap bulan agar matriks tidak singular
        for ($tahun = 2022; $tahun <= 2023; $tahun++) {
            for ($bulan = 1; $bulan <= 12; $bulan++) {
                DataTervalidasi::create([
                    'periode' => sprintf('%04d-%02d', $tahun, $bulan),
                    'kelahiran' => 100 + ($bulan * 3) + (($tahun - 2022) * 10),  // variasi
                    'kematian' => 50 + ($bulan % 5) + (($tahun - 2022) * 5),
                    'migrasi_masuk' => 30 + ($bulan % 7) + (($tahun - 2022) * 2),
                    'migrasi_keluar' => 20 + ($bulan % 3) + (($tahun - 2022) * 2),
                    'jumlah_penduduk' => 5000 + ($tahun - 2022) * 100 + ($bulan * 10),
                ]);
            }
        }
    }

    public function test_prediksi_1_tahun_berhasil()
    {
        $this->seedDataDuaTahun();

        $response = $this->postJson('/peramalan/prediksi', [
            'tahun' => 2023,
            'metode' => '1_tahun',
        ]);

        $response->assertStatus(200)
                 ->assertJson(['success' => true]);

        $this->assertDatabaseHas('riwayat_prediksi', [
            'metode' => '1_tahun',
        ]);
    }

    public function test_prediksi_rata_rata_berhasil()
    {
        $this->seedDataDuaTahun();

        $response = $this->postJson('/peramalan/prediksi', [
            'tahun' => 2023,
            'metode' => 'rata_rata',
        ]);

        $response->assertStatus(200)
                 ->assertJson(['success' => true]);
    }

    public function test_prediksi_gagal_data_kurang()
    {
        for ($bulan = 1; $bulan <= 5; $bulan++) {
            DataTervalidasi::create([
                'periode' => '2022-' . str_pad($bulan, 2, '0', STR_PAD_LEFT),
                'kelahiran' => 100 + $bulan * 2,
                'kematian' => 50 + $bulan,
                'migrasi_masuk' => 30 + $bulan,
                'migrasi_keluar' => 20 + $bulan,
                'jumlah_penduduk' => 5000 + $bulan * 10,
            ]);
        }

        $response = $this->postJson('/peramalan/prediksi', [
            'tahun' => 2023,
            'metode' => '1_tahun',
        ]);

        $response->assertStatus(200)
                 ->assertJson(['success' => false]);
    }
}