<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\RiwayatPrediksi;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RiwayatTest extends TestCase
{
    use RefreshDatabase;

    public function test_halaman_riwayat_tanpa_login_redirect()
    {
        $response = $this->get('/riwayat-prediksi');
        $response->assertRedirect('/login');
    }

    public function test_admin_melihat_riwayat()
    {
        $user = User::factory()->create();

        // Buat data riwayat
        RiwayatPrediksi::create([
            'periode' => '2023-01',
            'prediksi_jumlah' => 5100,
            'mape' => 2.5,
            'koefisien_json' => json_encode(['b0' => 1000, 'b1' => 2, 'b2' => -1, 'b3' => 1, 'b4' => -0.5]),
            'metode' => '1_tahun',
        ]);

        RiwayatPrediksi::create([
            'periode' => '2023-01',
            'prediksi_jumlah' => 5150,
            'mape' => 1.8,
            'koefisien_json' => json_encode(['b0' => 1000, 'b1' => 2, 'b2' => -1, 'b3' => 1, 'b4' => -0.5]),
            'metode' => 'rata_rata',
        ]);

        $response = $this->actingAs($user)->get('/riwayat-prediksi');
        $response->assertStatus(200);
        $response->assertSee('2023-01');
    }

    public function test_admin_hapus_semua_riwayat()
    {
        $user = User::factory()->create();

        RiwayatPrediksi::create([
            'periode' => '2023-01',
            'prediksi_jumlah' => 5100,
            'mape' => 2.5,
            'koefisien_json' => '{}',
            'metode' => '1_tahun',
        ]);

        $response = $this->actingAs($user)->delete('/riwayat-prediksi/hapus-semua');
        $response->assertRedirect('/riwayat-prediksi');
        $this->assertDatabaseCount('riwayat_prediksi', 0);
    }
}