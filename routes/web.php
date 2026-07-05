<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PendudukController;
use App\Http\Controllers\PeramalanController;
use App\Http\Controllers\AnalisisController;
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\RiwayatPrediksiController;
use Illuminate\Support\Facades\Route;

// Halaman utama langsung dashboard publik
Route::get('/', [DashboardController::class, 'index'])->name('dashboard.public');

// ==================== ROUTE PUBLIK ====================
Route::get('/peramalan', [PeramalanController::class, 'index'])->name('peramalan.index');
Route::post('/peramalan/prediksi', [PeramalanController::class, 'prediksi'])->name('peramalan.prediksi');

Route::get('/laporan', [LaporanController::class, 'index'])->name('laporan.index');

// ==================== ROUTE KHUSUS ADMIN (LOGIN) ====================
Route::middleware(['auth'])->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Download laporan hanya untuk admin
    Route::get('/laporan/pdf', [LaporanController::class, 'downloadPDF'])->name('laporan.pdf');
    Route::get('/laporan/excel', [LaporanController::class, 'downloadExcel'])->name('laporan.excel');

    Route::post('/penduduk/import', [PendudukController::class, 'import'])->name('penduduk.import');
    Route::get('/penduduk/export', [PendudukController::class, 'export'])->name('penduduk.export');
    Route::delete('/penduduk/destroy-all', [PendudukController::class, 'destroyAll'])->name('penduduk.destroyAll');
    Route::resource('penduduk', PendudukController::class);

    Route::get('/analisis', [AnalisisController::class, 'index'])->name('analisis.index');
    Route::post('/analisis/simpan', [AnalisisController::class, 'simpanPreprocessing'])->name('analisis.simpan');

    Route::get('/riwayat-prediksi', [RiwayatPrediksiController::class, 'index'])->name('riwayat.index');
    Route::delete('/riwayat-prediksi/hapus-semua', [RiwayatPrediksiController::class, 'destroyAll'])->name('riwayat.destroyAll');
    Route::delete('/riwayat-prediksi/{id}', [RiwayatPrediksiController::class, 'destroy'])->name('riwayat.destroy');
    Route::delete('/riwayat-prediksi/periode/{periode}', [RiwayatPrediksiController::class, 'destroyByPeriode'])->name('riwayat.destroyByPeriode');
});

require __DIR__.'/auth.php';