<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Mahasiswa\BimbinganController;
use App\Http\Controllers\Mahasiswa\DashboardController;
use App\Http\Controllers\Mahasiswa\KajurSubmissionController;
use App\Http\Controllers\Mahasiswa\PermintaanPembimbingController;
use App\Models\KajurSubmission;

Route::middleware(['auth', 'role:mahasiswa'])->prefix('mahasiswa')->name('mahasiswa.')->group(function () {
  // Permintaan Pembimbing
  Route::get('/permintaan-pembimbing', [PermintaanPembimbingController::class, 'create'])->name('permintaan-pembimbing.create');
  Route::post('/permintaan-pembimbing', [PermintaanPembimbingController::class, 'store'])->name('permintaan-pembimbing.store');

  Route::middleware('has.pembimbing')->group(function () {
    Route::get("/dashboard", [DashboardController::class, 'index'])->name("dashboard");

    Route::get('/bimbingan', [BimbinganController::class, 'index'])->name('bimbingan.index');

    Route::post('/bimbingan/create-submission', [BimbinganController::class, 'createSubmission'])->name('bimbingan.createSubmission');

    Route::get('/bimbingan/minta-penguji', [BimbinganController::class, 'mintaPenguji'])->name('bimbingan.mintaPenguji');

    Route::post('/bimbingan/create-kajur-submission', [KajurSubmissionController::class, 'createKajurSubmission'])->name('bimbingan.createKajurSubmission');
  });
});
