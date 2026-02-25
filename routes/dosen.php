<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Dosen\JadwalController;
use App\Http\Controllers\Dosen\MahasiswaBimbingan;
use App\Http\Controllers\Dosen\UndanganController;

Route::middleware(['auth', 'role:dosen'])->prefix('dosen')->name('dosen.')->group(function () {
  Route::get("/dashboard", function () {
    return view("dosen.dashboard");
  })->name("dashboard");

  Route::get('/bimbingan', [MahasiswaBimbingan::class, 'index'])->name('bimbingan.index');

  Route::get('/bimbingan/{submission}/detail', [MahasiswaBimbingan::class, 'getDetail'])->name('bimbingan.detail');

  Route::put('/bimbingan/{submission}/review', [MahasiswaBimbingan::class, 'review'])->name('bimbingan.review');

  Route::get('/undangan', [UndanganController::class, 'index'])->name('undangan.index');

  Route::get('/jadwal', [JadwalController::class, 'index'])->name('jadwal.index');
});
