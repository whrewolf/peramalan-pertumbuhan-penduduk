<?php

namespace App\Services;

class RegresiLinearService
{
    /**
     * Menghitung koefisien regresi linear berganda dengan 4 variabel bebas:
     * X1 = kelahiran, X2 = kematian, X3 = migrasi_masuk, X4 = migrasi_keluar
     */
    public function hitungRegresi($data)
    {
        $n = count($data);
        $sumY = 0;
        $sumX1 = 0; $sumX2 = 0; $sumX3 = 0; $sumX4 = 0;
        $sumX1Y = 0; $sumX2Y = 0; $sumX3Y = 0; $sumX4Y = 0;
        $sumX1X2 = 0; $sumX1X3 = 0; $sumX1X4 = 0;
        $sumX2X3 = 0; $sumX2X4 = 0; $sumX3X4 = 0;
        $sumX1Kuadrat = 0; $sumX2Kuadrat = 0; $sumX3Kuadrat = 0; $sumX4Kuadrat = 0;

        foreach ($data as $item) {
            $Y = $item->jumlah_penduduk;
            $X1 = $item->kelahiran;
            $X2 = $item->kematian;
            $X3 = $item->migrasi_masuk;
            $X4 = $item->migrasi_keluar;

            $sumY += $Y;
            $sumX1 += $X1;
            $sumX2 += $X2;
            $sumX3 += $X3;
            $sumX4 += $X4;

            $sumX1Y += $X1 * $Y;
            $sumX2Y += $X2 * $Y;
            $sumX3Y += $X3 * $Y;
            $sumX4Y += $X4 * $Y;

            $sumX1X2 += $X1 * $X2;
            $sumX1X3 += $X1 * $X3;
            $sumX1X4 += $X1 * $X4;
            $sumX2X3 += $X2 * $X3;
            $sumX2X4 += $X2 * $X4;
            $sumX3X4 += $X3 * $X4;

            $sumX1Kuadrat += $X1 * $X1;
            $sumX2Kuadrat += $X2 * $X2;
            $sumX3Kuadrat += $X3 * $X3;
            $sumX4Kuadrat += $X4 * $X4;
        }

        // Matriks normal 5x5
        $A = [
            [$n, $sumX1, $sumX2, $sumX3, $sumX4],
            [$sumX1, $sumX1Kuadrat, $sumX1X2, $sumX1X3, $sumX1X4],
            [$sumX2, $sumX1X2, $sumX2Kuadrat, $sumX2X3, $sumX2X4],
            [$sumX3, $sumX1X3, $sumX2X3, $sumX3Kuadrat, $sumX3X4],
            [$sumX4, $sumX1X4, $sumX2X4, $sumX3X4, $sumX4Kuadrat]
        ];
        $B = [$sumY, $sumX1Y, $sumX2Y, $sumX3Y, $sumX4Y];

        $koefisien = $this->eliminasiGauss($A, $B);

        return [
            'b0' => $koefisien[0],
            'b1' => $koefisien[1],
            'b2' => $koefisien[2],
            'b3' => $koefisien[3],
            'b4' => $koefisien[4],
        ];
    }

    /**
     * Eliminasi Gauss untuk menyelesaikan sistem persamaan linear
     */
    private function eliminasiGauss($A, $B)
    {
        $n = count($A);
        for ($i = 0; $i < $n; $i++) {
            $pivot = $A[$i][$i];
            for ($j = $i; $j < $n; $j++) {
                $A[$i][$j] /= $pivot;
            }
            $B[$i] /= $pivot;

            for ($k = 0; $k < $n; $k++) {
                if ($k != $i) {
                    $factor = $A[$k][$i];
                    for ($j = $i; $j < $n; $j++) {
                        $A[$k][$j] -= $factor * $A[$i][$j];
                    }
                    $B[$k] -= $factor * $B[$i];
                }
            }
        }
        return $B;
    }

    /**
     * Menghitung nilai prediksi berdasarkan koefisien
     */
    public function prediksi($b0, $b1, $b2, $b3, $b4, $kelahiran, $kematian, $migrasi_masuk, $migrasi_keluar)
    {
        return $b0 + ($b1 * $kelahiran) + ($b2 * $kematian) + ($b3 * $migrasi_masuk) + ($b4 * $migrasi_keluar);
    }

    /**
     * Menghitung MAPE (Mean Absolute Percentage Error)
     */
    public function hitungMAPE($dataAktual, $dataPrediksi)
    {
        $total = 0;
        $n = count($dataAktual);
        for ($i = 0; $i < $n; $i++) {
            if ($dataAktual[$i] != 0) {
                $total += abs(($dataAktual[$i] - $dataPrediksi[$i]) / $dataAktual[$i]);
            }
        }
        return ($total / $n) * 100;
    }
}