<?php

namespace App\Http\Controllers;

use App\Models\Penduduk;

class DashboardController extends Controller
{
    public function index()
    {
        // Ringkasan data
        $latest = Penduduk::latest('periode')->first();
        $totalPenduduk = $latest->jumlah_penduduk ?? 0;
        $totalKelahiran = Penduduk::sum('kelahiran');
        $totalKematian = Penduduk::sum('kematian');
        $totalMigrasiMasuk = Penduduk::sum('migrasi_masuk');
        $totalMigrasiKeluar = Penduduk::sum('migrasi_keluar');
        $migrasiBersih = $totalMigrasiMasuk - $totalMigrasiKeluar;

        // Data untuk grafik (bulanan)
        $dataPenduduk = Penduduk::orderBy('periode')->get();
        $tahunLabels = $dataPenduduk->pluck('periode');       // berisi string "YYYY-MM"
        $jumlahPendudukValues = $dataPenduduk->pluck('jumlah_penduduk');

        return view('dashboard', compact(
            'totalPenduduk',
            'totalKelahiran',
            'totalKematian',
            'totalMigrasiMasuk',
            'totalMigrasiKeluar',
            'migrasiBersih',
            'tahunLabels',
            'jumlahPendudukValues'
        ));
    }
}