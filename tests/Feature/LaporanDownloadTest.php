<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\DataTervalidasi;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LaporanDownloadTest extends TestCase
{
    use RefreshDatabase;

    public function test_download_pdf_tanpa_login_gagal()
    {
        $response = $this->get('/laporan/pdf');
        $response->assertRedirect('/login');
    }

    public function test_admin_download_pdf_berhasil()
    {
        $user = User::factory()->create();

        // Seed data minimal agar laporan tidak kosong
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

        $response = $this->actingAs($user)->get('/laporan/pdf');
        $response->assertStatus(200);
        $this->assertStringContainsString('.pdf', $response->headers->get('content-disposition'));
    }

    public function test_admin_download_excel_berhasil()
    {
        $user = User::factory()->create();

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

        $response = $this->actingAs($user)->get('/laporan/excel');
        $response->assertStatus(200);
        $this->assertStringContainsString('.xlsx', $response->headers->get('content-disposition'));
    }
}