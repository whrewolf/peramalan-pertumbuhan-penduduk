<?php

namespace App\Http\Controllers;

use App\Models\Penduduk;
use App\Models\DataTervalidasi;
use Illuminate\Http\Request;

class AnalisisController extends Controller
{
    public function index()
    {
        $dataMentah = Penduduk::orderBy('periode')->get();

        // 1. Deteksi Missing Value
        $missingValues = $dataMentah->filter(function ($item) {
            return is_null($item->kelahiran) || is_null($item->kematian) ||
                   is_null($item->migrasi_masuk) || is_null($item->migrasi_keluar) ||
                   is_null($item->jumlah_penduduk);
        });

        // 2. Interpolasi linear di memori (tidak ubah database)
        $data = $dataMentah->toArray();
        $n = count($data);
        foreach (['kelahiran', 'kematian', 'migrasi_masuk', 'migrasi_keluar', 'jumlah_penduduk'] as $kolom) {
            for ($i = 0; $i < $n; $i++) {
                if (!isset($data[$i][$kolom]) || is_null($data[$i][$kolom])) {
                    $prev = null;
                    for ($j = $i - 1; $j >= 0; $j--) {
                        if (!is_null($data[$j][$kolom])) {
                            $prev = $data[$j][$kolom];
                            break;
                        }
                    }
                    $next = null;
                    for ($j = $i + 1; $j < $n; $j++) {
                        if (!is_null($data[$j][$kolom])) {
                            $next = $data[$j][$kolom];
                            break;
                        }
                    }
                    if (!is_null($prev) && !is_null($next)) {
                        $data[$i][$kolom] = ($prev + $next) / 2;
                    } elseif (!is_null($prev)) {
                        $data[$i][$kolom] = $prev;
                    } elseif (!is_null($next)) {
                        $data[$i][$kolom] = $next;
                    }
                }
            }
        }
        $dataBersih = collect($data);

        // 3. Validasi anomali
        $validasiErrors = [];
        foreach ($dataBersih as $item) {
            $errors = [];
            if ($item['kelahiran'] > $item['jumlah_penduduk']) $errors[] = 'Kelahiran > Penduduk';
            if ($item['kematian'] > $item['jumlah_penduduk']) $errors[] = 'Kematian > Penduduk';
            if ($item['migrasi_masuk'] > $item['jumlah_penduduk']) $errors[] = 'Migrasi Masuk > Penduduk';
            if ($item['migrasi_keluar'] > $item['jumlah_penduduk']) $errors[] = 'Migrasi Keluar > Penduduk';
            if (!empty($errors)) {
                $validasiErrors[] = ['periode' => $item['periode'], 'errors' => implode(', ', $errors)];
            }
        }

        // Data untuk grafik (dari data bersih di memori)
        $labels = $dataBersih->pluck('periode');
        $kelahiran = $dataBersih->pluck('kelahiran');
        $kematian = $dataBersih->pluck('kematian');
        $migrasiMasuk = $dataBersih->pluck('migrasi_masuk');
        $migrasiKeluar = $dataBersih->pluck('migrasi_keluar');
        $penduduk = $dataBersih->pluck('jumlah_penduduk');

        // Cek apakah sudah ada data tervalidasi
        $dataTervalidasi = DataTervalidasi::count();

        return view('analisis.index', compact(
            'missingValues',
            'validasiErrors',
            'labels',
            'kelahiran',
            'kematian',
            'migrasiMasuk',
            'migrasiKeluar',
            'penduduk',
            'dataTervalidasi'
        ));
    }

    /**
     * Simpan hasil preprocessing ke tabel data_tervalidasi
     */
    public function simpanPreprocessing(Request $request)
    {
        $dataMentah = Penduduk::orderBy('periode')->get();
        $data = $dataMentah->toArray();
        $n = count($data);

        // Lakukan interpolasi + tandai mana yang hasil interpolasi
        foreach (['kelahiran', 'kematian', 'migrasi_masuk', 'migrasi_keluar', 'jumlah_penduduk'] as $kolom) {
            for ($i = 0; $i < $n; $i++) {
                if (!isset($data[$i][$kolom]) || is_null($data[$i][$kolom])) {
                    $prev = null;
                    for ($j = $i - 1; $j >= 0; $j--) {
                        if (!is_null($data[$j][$kolom])) {
                            $prev = $data[$j][$kolom];
                            break;
                        }
                    }
                    $next = null;
                    for ($j = $i + 1; $j < $n; $j++) {
                        if (!is_null($data[$j][$kolom])) {
                            $next = $data[$j][$kolom];
                            break;
                        }
                    }
                    if (!is_null($prev) && !is_null($next)) {
                        $data[$i][$kolom] = ($prev + $next) / 2;
                        $data[$i]['is_interpolated'] = true;
                    } elseif (!is_null($prev)) {
                        $data[$i][$kolom] = $prev;
                        $data[$i]['is_interpolated'] = true;
                    } elseif (!is_null($next)) {
                        $data[$i][$kolom] = $next;
                        $data[$i]['is_interpolated'] = true;
                    }
                }
            }
        }

        // Hapus data lama & simpan baru
        DataTervalidasi::truncate();
        foreach ($data as $item) {
            DataTervalidasi::create([
                'periode'         => $item['periode'],
                'kelahiran'       => $item['kelahiran'] ?? null,
                'kematian'        => $item['kematian'] ?? null,
                'migrasi_masuk'   => $item['migrasi_masuk'] ?? null,
                'migrasi_keluar'  => $item['migrasi_keluar'] ?? null,
                'jumlah_penduduk' => $item['jumlah_penduduk'] ?? null,
                'is_interpolated' => $item['is_interpolated'] ?? false,
            ]);
        }

        return redirect()->back()->with('success', 'Preprocessing berhasil disimpan. Data siap digunakan di Peramalan.');
    }
}