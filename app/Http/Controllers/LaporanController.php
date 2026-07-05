<?php

namespace App\Http\Controllers;

use App\Models\DataTervalidasi;
use App\Models\RiwayatPrediksi;
use Barryvdh\DomPDF\Facade\Pdf;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\LaporanExport;

class LaporanController extends Controller
{
    public function index()
    {
        $laporanData = $this->getLaporanData();
        return view('laporan.index', $laporanData);
    }

    public function downloadPDF()
    {
        $data = $this->getLaporanData();
        $pdf = Pdf::loadView('laporan.pdf', $data);
        return $pdf->download('laporan_peramalan_penduduk.pdf');
    }

    public function downloadExcel()
    {
        $data = $this->getLaporanData();
        return Excel::download(new LaporanExport($data['laporan']), 'laporan_peramalan_penduduk.xlsx');
    }

    private function getLaporanData()
{
    $dataAktual = DataTervalidasi::orderBy('periode')->get();

    // Ambil riwayat prediksi 1 tahun
    $riwayat1Tahun = RiwayatPrediksi::where('metode', '1_tahun')
                        ->orderBy('periode')
                        ->get()
                        ->keyBy('periode');

    // Ambil riwayat prediksi rata-rata
    $riwayatRataRata = RiwayatPrediksi::where('metode', 'rata_rata')
                        ->orderBy('periode')
                        ->get()
                        ->keyBy('periode');

    $laporan = [];
    foreach ($dataAktual as $item) {
        $prediksi1Tahun = null;
        $error1Tahun = null;
        $errorPersen1Tahun = null;

        $prediksiRataRata = null;
        $errorRataRata = null;
        $errorPersenRataRata = null;

        // Prediksi 1 tahun
        if (isset($riwayat1Tahun[$item->periode])) {
            $prediksi1Tahun = $riwayat1Tahun[$item->periode]->prediksi_jumlah;
            if (!is_null($item->jumlah_penduduk) && !is_null($prediksi1Tahun)) {
                $error1Tahun = $item->jumlah_penduduk - $prediksi1Tahun;
                $errorPersen1Tahun = $item->jumlah_penduduk != 0
                    ? abs(($error1Tahun / $item->jumlah_penduduk) * 100)
                    : 0;
            }
        }

        // Prediksi rata-rata
        if (isset($riwayatRataRata[$item->periode])) {
            $prediksiRataRata = $riwayatRataRata[$item->periode]->prediksi_jumlah;
            if (!is_null($item->jumlah_penduduk) && !is_null($prediksiRataRata)) {
                $errorRataRata = $item->jumlah_penduduk - $prediksiRataRata;
                $errorPersenRataRata = $item->jumlah_penduduk != 0
                    ? abs(($errorRataRata / $item->jumlah_penduduk) * 100)
                    : 0;
            }
        }

        $migrasiBersih = (!is_null($item->migrasi_masuk) && !is_null($item->migrasi_keluar))
            ? $item->migrasi_masuk - $item->migrasi_keluar
            : null;

        $laporan[] = [
            'periode'            => $item->periode,
            'aktual'             => $item->jumlah_penduduk,
            'kelahiran'          => $item->kelahiran,
            'kematian'           => $item->kematian,
            'migrasi_masuk'      => $item->migrasi_masuk,
            'migrasi_keluar'     => $item->migrasi_keluar,
            'migrasi_bersih'     => $migrasiBersih,
            'prediksi_1_tahun'   => $prediksi1Tahun,
            'error_1_tahun'      => !is_null($error1Tahun) ? round($error1Tahun) : null,
            'error_persen_1_tahun' => !is_null($errorPersen1Tahun) ? round($errorPersen1Tahun, 2) : null,
            'prediksi_rata_rata' => $prediksiRataRata,
            'error_rata_rata'    => !is_null($errorRataRata) ? round($errorRataRata) : null,
            'error_persen_rata_rata' => !is_null($errorPersenRataRata) ? round($errorPersenRataRata, 2) : null,
        ];
    }

    $hasPrediksi = $riwayat1Tahun->isNotEmpty() || $riwayatRataRata->isNotEmpty();
    $mape1Tahun = $riwayat1Tahun->avg('mape');
    $mapeRataRata = $riwayatRataRata->avg('mape');

    $koefisien = null; // tidak ditampilkan

    return compact('laporan', 'hasPrediksi', 'mape1Tahun', 'mapeRataRata', 'koefisien');
}
}