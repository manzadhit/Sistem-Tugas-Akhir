<?php

use App\Http\Controllers\Mahasiswa\PermintaanPembimbingController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'role:mahasiswa'])->prefix('mahasiswa')->name('mahasiswa.')->group(function () {
  // Permintaan Pembimbing
  Route::get('/permintaan-pembimbing', [PermintaanPembimbingController::class, 'create'])->name('permintaan-pembimbing.create');
  Route::post('/permintaan-pembimbing', [PermintaanPembimbingController::class, 'store'])->name('permintaan-pembimbing.store');

  Route::middleware('has.pembimbing')->group(function () {
    Route::get("/dashboard", function () {
      return view("mahasiswa.dashboard");
    })->name("dashboard");
  });
});
