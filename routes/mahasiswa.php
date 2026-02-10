<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Mahasiswa\BimbinganController;
use App\Http\Controllers\Mahasiswa\DashboardController;
use App\Http\Controllers\Mahasiswa\PermintaanPembimbingController;

Route::middleware(['auth', 'role:mahasiswa'])->prefix('mahasiswa')->name('mahasiswa.')->group(function () {
  // Permintaan Pembimbing
  Route::get('/permintaan-pembimbing', [PermintaanPembimbingController::class, 'create'])->name('permintaan-pembimbing.create');
  Route::post('/permintaan-pembimbing', [PermintaanPembimbingController::class, 'store'])->name('permintaan-pembimbing.store');

  Route::middleware('has.pembimbing')->group(function () {
    Route::get("/dashboard", [DashboardController::class, 'index'])->name("dashboard");

    Route::get('/bimbingan', [BimbinganController::class, 'index'])->name('bimbingan.index');
  });
});
