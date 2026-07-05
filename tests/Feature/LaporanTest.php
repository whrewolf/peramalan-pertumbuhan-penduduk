<?php

namespace Tests\Feature;

use App\Models\DataTervalidasi;
use App\Models\RiwayatPrediksi;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LaporanTest extends TestCase
{
    use RefreshDatabase;

    private function seedData()
    {
        for ($bulan = 1; $bulan <= 6; $bulan++) {
            DataTervalidasi::create([
                'periode' => '2022-' . str_pad($bulan, 2, '0', STR_PAD_LEFT),
                'kelahiran' => 100,
                'kematian' => 50,
                'migrasi_masuk' => 30,
                'migrasi_keluar' => 20,
                'jumlah_penduduk' => 5000 + $bulan,
            ]);
        }
    }

    public function test_laporan_dapat_diakses_publik()
    {
        $this->seedData();
        $response = $this->get('/laporan');
        $response->assertStatus(200);
        $response->assertSee('Catatan:');
    }

    public function test_admin_dapat_download_pdf()
    {
        $this->seedData();
        RiwayatPrediksi::create([
            'periode' => '2022-01',
            'prediksi_jumlah' => 5000,
            'mape' => 0.5,
            'metode' => '1_tahun',
            'koefisien_json' => json_encode(['b0' => 1000, 'b1' => 1, 'b2' => 2, 'b3' => 3, 'b4' => 4]),
        ]);

        $user = User::factory()->create();
        $response = $this->actingAs($user)->get('/laporan/pdf');
        $response->assertStatus(200);
        $response->assertHeader('Content-Type', 'application/pdf');
    }

    public function test_admin_dapat_download_excel()
    {
        $this->seedData();
        RiwayatPrediksi::create([
            'periode' => '2022-01',
            'prediksi_jumlah' => 5000,
            'mape' => 0.5,
            'metode' => '1_tahun',
            'koefisien_json' => json_encode(['b0' => 1000, 'b1' => 1, 'b2' => 2, 'b3' => 3, 'b4' => 4]),
        ]);

        $user = User::factory()->create();
        $response = $this->actingAs($user)->get('/laporan/excel');
        $response->assertStatus(200);
    }

    public function test_tamu_tidak_dapat_download_pdf()
    {
        $this->seedData();
        $response = $this->get('/laporan/pdf');
        $response->assertRedirect('/login');
    }
}