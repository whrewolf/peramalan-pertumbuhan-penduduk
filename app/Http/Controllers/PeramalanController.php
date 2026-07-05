<?php

namespace App\Http\Controllers;

use App\Models\DataTervalidasi;
use App\Models\RiwayatPrediksi;
use App\Services\RegresiLinearService;
use Illuminate\Http\Request;

class PeramalanController extends Controller
{
    protected $regresiService;

    public function __construct(RegresiLinearService $regresiService)
    {
        $this->regresiService = $regresiService;
    }

    public function index()
    {
        $data = DataTervalidasi::orderBy('periode')->get();
        $cukupData = $data->count() >= 6;

        // Kumpulkan semua tahun unik
        $semuaTahun = $data->pluck('periode')
            ->map(fn($p) => (int) substr($p, 0, 4))
            ->unique()
            ->sort()
            ->values();

        $tahunMinimum = $semuaTahun->first();

        // Hanya tahun yang memiliki data tervalidasi di tahun sebelumnya
        $tahunTersedia = $semuaTahun->filter(function ($tahun) use ($data, $tahunMinimum) {
            if ($tahun <= $tahunMinimum) {
                return false;
            }
            $tahunSebelumnya = $tahun - 1;
            // Cek apakah ada data dengan periode tahun sebelumnya
            $ada = $data->contains(function ($item) use ($tahunSebelumnya) {
                return (int) substr($item->periode, 0, 4) === $tahunSebelumnya;
            });
            return $ada;
        })->values();

        return view('peramalan.index', compact('data', 'cukupData', 'tahunTersedia', 'tahunMinimum'));
    }

    public function prediksi(Request $request)
    {
        $request->validate([
            'tahun'  => 'required|integer|min:1900|max:2100',
            'metode' => 'required|in:1_tahun,rata_rata',
        ]);

        $tahunDipilih = $request->tahun;
        $metode = $request->metode;

        // Validasi minimum
        $periodePertama = DataTervalidasi::orderBy('periode')->first();
        if (!$periodePertama) {
            return response()->json(['success' => false, 'message' => 'Tidak ada data tervalidasi.']);
        }
        $tahunMinimum = (int) substr($periodePertama->periode, 0, 4);
        if ($tahunDipilih <= $tahunMinimum) {
            return response()->json([
                'success' => false,
                'message' => "Tahun $tahunDipilih tidak dapat diramalkan karena merupakan tahun awal data."
            ]);
        }

        $semuaData = DataTervalidasi::orderBy('periode')->get();

        // Data lengkap untuk regresi
        $dataLengkap = $semuaData->filter(function ($item) {
            return !is_null($item->kelahiran) && !is_null($item->kematian) &&
                   !is_null($item->migrasi_masuk) && !is_null($item->migrasi_keluar) &&
                   !is_null($item->jumlah_penduduk);
        });

        if ($dataLengkap->count() < 6) {
            return response()->json([
                'success' => false,
                'message' => 'Data tervalidasi minimal 6 bulan.'
            ]);
        }

        $koefisien = $this->regresiService->hitungRegresi($dataLengkap);

        // Ambil data untuk tahun yang dipilih (hanya untuk label dan nilai aktual)
        $dataTahun = DataTervalidasi::where('periode', 'like', $tahunDipilih . '-%')
                             ->orderBy('periode')
                             ->get();

        if ($dataTahun->isEmpty()) {
            return response()->json([
                'success' => false,
                'message' => "Tidak ada data untuk tahun $tahunDipilih."
            ]);
        }

        // Siapkan variabel
        $labels = [];
        $aktual = [];
        $prediksi = [];
        $bulanKosong = [];

        if ($metode === '1_tahun') {
            // Gunakan data dari TAHUN SEBELUMNYA (X-1)
            $tahunSebelumnya = $tahunDipilih - 1;
            $dataTahunSebelumnya = DataTervalidasi::where('periode', 'like', $tahunSebelumnya . '-%')
                                         ->orderBy('periode')
                                         ->get()
                                         ->keyBy('periode'); // indeks by periode

            if ($dataTahunSebelumnya->isEmpty()) {
                return response()->json([
                    'success' => false,
                    'message' => "Tidak ada data untuk tahun $tahunSebelumnya (diperlukan untuk mode 1 Tahun Sebelumnya)."
                ]);
            }

            foreach ($dataTahun as $item) {
                $labels[] = $item->periode;
                $aktual[] = $item->jumlah_penduduk;

                // Cari bulan yang sama di tahun sebelumnya
                $bulanIni = substr($item->periode, 5, 2); // "01", "02", ...
                $periodeSumber = sprintf('%04d-%02d', $tahunSebelumnya, (int)$bulanIni);
                $sumber = $dataTahunSebelumnya->get($periodeSumber);

                if ($sumber &&
                    !is_null($sumber->kelahiran) && !is_null($sumber->kematian) &&
                    !is_null($sumber->migrasi_masuk) && !is_null($sumber->migrasi_keluar)) {
                    $pred = $this->regresiService->prediksi(
                        $koefisien['b0'], $koefisien['b1'], $koefisien['b2'],
                        $koefisien['b3'], $koefisien['b4'],
                        $sumber->kelahiran, $sumber->kematian,
                        $sumber->migrasi_masuk, $sumber->migrasi_keluar
                    );
                    $prediksi[] = round($pred, 2);
                } else {
                    $prediksi[] = null;
                    $bulanKosong[] = $item->periode;
                }
            }
        } else { // rata_rata
            $dataSebelumnya = $semuaData->filter(function ($item) use ($tahunDipilih) {
                return (int) substr($item->periode, 0, 4) < $tahunDipilih;
            });

            if ($dataSebelumnya->isEmpty()) {
                return response()->json([
                    'success' => false,
                    'message' => "Tidak ada data sebelum tahun $tahunDipilih untuk menghitung rata‑rata."
                ]);
            }

            foreach ($dataTahun as $item) {
                $labels[] = $item->periode;
                $aktual[] = $item->jumlah_penduduk;

                // Ambil bulan dari periode (01, 02, ..., 12)
                $bulanIni = substr($item->periode, 5, 2);

                // Filter data pada bulan yang sama di semua tahun sebelumnya
                $dataBulanSebelumnya = $dataSebelumnya->filter(function ($d) use ($bulanIni) {
                    return substr($d->periode, 5, 2) === $bulanIni;
                });

                if ($dataBulanSebelumnya->isNotEmpty()) {
                    $rataKelahiran = $dataBulanSebelumnya->avg('kelahiran');
                    $rataKematian = $dataBulanSebelumnya->avg('kematian');
                    $rataMigrasiMasuk = $dataBulanSebelumnya->avg('migrasi_masuk');
                    $rataMigrasiKeluar = $dataBulanSebelumnya->avg('migrasi_keluar');

                    if (!is_null($rataKelahiran) && !is_null($rataKematian) &&
                        !is_null($rataMigrasiMasuk) && !is_null($rataMigrasiKeluar)) {
                        $pred = $this->regresiService->prediksi(
                            $koefisien['b0'], $koefisien['b1'], $koefisien['b2'],
                            $koefisien['b3'], $koefisien['b4'],
                            $rataKelahiran, $rataKematian,
                            $rataMigrasiMasuk, $rataMigrasiKeluar
                        );
                        $prediksi[] = round($pred, 2);
                    } else {
                        $prediksi[] = null;
                        $bulanKosong[] = $item->periode;
                    }
                } else {
                    $prediksi[] = null;
                    $bulanKosong[] = $item->periode;
                }
            }
        }

        // Hitung MAPE
        $mape = null;
        $filteredAktual = [];
        $filteredPrediksi = [];
        foreach ($dataTahun as $index => $item) {
            if (!is_null($item->jumlah_penduduk) && !is_null($prediksi[$index])) {
                $filteredAktual[] = $item->jumlah_penduduk;
                $filteredPrediksi[] = $prediksi[$index];
            }
        }
        if (count($filteredAktual) > 0) {
            $mape = $this->regresiService->hitungMAPE($filteredAktual, $filteredPrediksi);
        }

        // Simpan ke riwayat
        foreach ($dataTahun as $index => $item) {
            if (!is_null($item->jumlah_penduduk) && !is_null($prediksi[$index])) {
                RiwayatPrediksi::updateOrCreate(
    ['periode' => $item->periode, 'metode' => $metode],   // <-- kondisi HARUS menyertakan metode
    [
        'prediksi_jumlah' => round($prediksi[$index]),
        'mape' => $mape ? round($mape, 2) : null,
        'koefisien_json' => json_encode($koefisien)
    ]
);
            }
        }

        return response()->json([
            'success' => true,
            'metode' => $metode,
            'koefisien' => $koefisien,
            'mape' => $mape ? round($mape, 2) : null,
            'labels' => $labels,
            'aktual' => $aktual,
            'prediksi' => $prediksi,
            'bulan_kosong' => $bulanKosong,
            'tahun' => $tahunDipilih,
        ]);
    }
}