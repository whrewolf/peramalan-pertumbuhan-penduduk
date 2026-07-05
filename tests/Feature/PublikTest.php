<?php

namespace Tests\Feature;

use App\Models\DataTervalidasi;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PublikTest extends TestCase
{
    use RefreshDatabase;

    private function seedDataMinimal()
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

    public function test_halaman_utama()
    {
        $this->seedDataMinimal();
        $response = $this->get('/');
        $response->assertStatus(200);
        $response->assertSee('SIPENDUDUK');
    }

    public function test_halaman_peramalan()
    {
        $this->seedDataMinimal();
        $response = $this->get('/peramalan');
        $response->assertStatus(200);
        $response->assertSee('Pilih Tahun Peramalan');
    }

    public function test_halaman_laporan()
    {
        $this->seedDataMinimal();
        $response = $this->get('/laporan');
        $response->assertStatus(200);
        $response->assertSee('Catatan:'); // Teks pasti muncul di halaman laporan
    }

    public function test_akses_admin_tanpa_login()
    {
        $response = $this->get('/penduduk');
        $response->assertRedirect('/login');
    }
}