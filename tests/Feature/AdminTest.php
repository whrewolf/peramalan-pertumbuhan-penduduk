<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\DataTervalidasi;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminTest extends TestCase
{
    use RefreshDatabase;

    private function seedData()
    {
        for ($bulan = 1; $bulan <= 12; $bulan++) {
            DataTervalidasi::create([
                'periode' => '2022-' . str_pad($bulan, 2, '0', STR_PAD_LEFT),
                'kelahiran' => 100,
                'kematian' => 50,
                'migrasi_masuk' => 30,
                'migrasi_keluar' => 20,
                'jumlah_penduduk' => 5000 + $bulan,
            ]);
        }
        for ($bulan = 1; $bulan <= 12; $bulan++) {
            DataTervalidasi::create([
                'periode' => '2023-' . str_pad($bulan, 2, '0', STR_PAD_LEFT),
                'kelahiran' => 110,
                'kematian' => 45,
                'migrasi_masuk' => 25,
                'migrasi_keluar' => 25,
                'jumlah_penduduk' => 5200 + $bulan,
            ]);
        }
    }

    public function test_admin_akses_penduduk()
    {
        $user = User::factory()->create();
        $response = $this->actingAs($user)->get('/penduduk');
        $response->assertStatus(200);
        $response->assertSee('Data Penduduk');
    }

    public function test_admin_akses_analisis()
    {
        $user = User::factory()->create();
        $this->seedData();
        $response = $this->actingAs($user)->get('/analisis');
        $response->assertStatus(200);
        $response->assertSee('Preprocessing Data');
    }

    public function test_admin_akses_riwayat()
    {
        $user = User::factory()->create();
        $response = $this->actingAs($user)->get('/riwayat-prediksi');
        $response->assertStatus(200);
    }

    public function test_admin_akses_profile()
    {
        $user = User::factory()->create();
        $response = $this->actingAs($user)->get('/profile');
        $response->assertStatus(200);
        $response->assertSee('Profil');
    }
}