<?php

namespace App\Http\Controllers;

use App\Models\RiwayatPrediksi;

class RiwayatPrediksiController extends Controller
{
    public function index()
    {
        // Data 1 Tahun Sebelumnya (ascending untuk grafik)
        $riwayat1Tahun = RiwayatPrediksi::where('metode', '1_tahun')
                            ->orderBy('periode', 'asc')
                            ->get();

        // Data Rata-rata Historis (ascending)
        $riwayatRataRata = RiwayatPrediksi::where('metode', 'rata_rata')
                            ->orderBy('periode', 'asc')
                            ->get();

        // Grafik 1 Tahun
        $labels1 = $riwayat1Tahun->pluck('periode')->values();
        $prediksi1 = $riwayat1Tahun->pluck('prediksi_jumlah')->values();
        $mape1 = $riwayat1Tahun->pluck('mape')->values();

        // Grafik Rata-rata
        $labelsRata = $riwayatRataRata->pluck('periode')->values();
        $prediksiRata = $riwayatRataRata->pluck('prediksi_jumlah')->values();
        $mapeRata = $riwayatRataRata->pluck('mape')->values();

        // Periode unik gabungan untuk tabel
        $periodeUnik = $riwayat1Tahun->pluck('periode')
                        ->merge($riwayatRataRata->pluck('periode'))
                        ->unique()
                        ->sort()
                        ->values();

        return view('riwayat.index', compact(
            'riwayat1Tahun', 'riwayatRataRata',
            'labels1', 'prediksi1', 'mape1',
            'labelsRata', 'prediksiRata', 'mapeRata',
            'periodeUnik'
        ));
    }

    public function destroy($id)
    {
        $riwayat = RiwayatPrediksi::findOrFail($id);
        $riwayat->delete();
        return redirect()->route('riwayat.index')->with('success', 'Riwayat berhasil dihapus.');
    }

    public function destroyAll()
    {
        RiwayatPrediksi::truncate();
        return redirect()->route('riwayat.index')->with('success', 'Semua riwayat prediksi berhasil dihapus.');
    }

    public function destroyByPeriode($periode)
{
    RiwayatPrediksi::where('periode', $periode)->delete();
    return redirect()->route('riwayat.index')->with('success', "Semua riwayat untuk periode $periode berhasil dihapus.");
}
}