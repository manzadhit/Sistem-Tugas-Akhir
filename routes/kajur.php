<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Kajur\DashboardController;
use App\Http\Controllers\Kajur\PembimbingController;

Route::middleware(['auth', 'role:kajur'])->prefix('kajur')->name('kajur.')->group(function () {
  Route::get("/dashboard", [DashboardController::class, 'index'])->name("dashboard");

  Route::get("/permintaan-pembimbing", [PembimbingController::class, 'index'])->name('permintaan-pembimbing');

  Route::get('/permintaan-pembimbing/{permintaan}', [PembimbingController::class, 'show'])->name("penetapan-pembimbing");
  Route::get('/permintaan-pembimbing/{permintaan}/show-bukti', [PembimbingController::class, 'showBukti'])->name('show-bukti');
  Route::get('/permintaan-pembimbing/{permintaan}/download-bukti', [PembimbingController::class, 'downloadBukti'])->name('download-bukti');
  Route::put('/permintaan-pembimbing/{permintaan}/verify-bukti', [PembimbingController::class, 'verifyBukti'])->name('verify-bukti');
});
