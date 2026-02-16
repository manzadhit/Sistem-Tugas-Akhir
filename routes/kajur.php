<?php

use App\Http\Controllers\Kajur\DashboardController;
use App\Http\Controllers\Kajur\PembimbingController;
use App\Http\Controllers\Kajur\PengujiController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'role:kajur'])->prefix('kajur')->name('kajur.')->group(function () {
  Route::get("/dashboard", [DashboardController::class, 'index'])->name("dashboard");

  Route::get("/permintaan-pembimbing", [PembimbingController::class, 'index'])->name('permintaan-pembimbing');

  Route::get('/permintaan-pembimbing/{permintaan}', [PembimbingController::class, 'show'])->name("penetapan-pembimbing");
  Route::get('/permintaan-pembimbing/{permintaan}/show-bukti', [PembimbingController::class, 'showBukti'])->name('show-bukti');
  Route::get('/permintaan-pembimbing/{permintaan}/download-bukti', [PembimbingController::class, 'downloadBukti'])->name('download-bukti');
  Route::put('/permintaan-pembimbing/{permintaan}/verify-bukti', [PembimbingController::class, 'verifyBukti'])->name('verify-bukti');
  Route::post('/permintaan-pembimbing/{permintaan}/tetapkan', [PembimbingController::class, 'tetapkanPembimbing'])->name('tetapkanPembimbing');


  Route::get('/permintaan-penguji', [PengujiController::class, 'index'])->name('permintaan-penguji.index');
  Route::get('/permintaan-penguji/{permintaan}', [PengujiController::class, 'show'])->name('penetapan-penguji');
  Route::put('/permintaan-penguji/{permintaan}/verify-laporan', [PengujiController::class, 'verifyLaporan'])->name('verify-laporan');
  Route::post('/permintaan-penguji/{permintaan}/tetapkan', [PengujiController::class, 'tetapkanPenguji'])->name('tetapkanPenguji');
});
