<?php

use App\Http\Controllers\Mahasiswa\BimbinganController;
use App\Http\Controllers\Mahasiswa\DashboardController;
use App\Http\Controllers\Mahasiswa\KajurSubmissionController;
use App\Http\Controllers\Mahasiswa\PermintaanPembimbingController;
use App\Http\Controllers\Mahasiswa\ProfileController as MahasiswaProfileController;
use App\Http\Controllers\Mahasiswa\UjianController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'role:mahasiswa'])->prefix('mahasiswa')->name('mahasiswa.')->group(function () {
  // Profile
  Route::get('/profile', [MahasiswaProfileController::class, 'edit'])->name('profile.edit');
  Route::put('/profile', [MahasiswaProfileController::class, 'update'])->name('profile.update');

  // Permintaan Pembimbing
  Route::get('/permintaan-pembimbing', [PermintaanPembimbingController::class, 'create'])->name('permintaan-pembimbing.create');
  Route::post('/permintaan-pembimbing', [PermintaanPembimbingController::class, 'store'])->name('permintaan-pembimbing.store');

  Route::middleware('has.pembimbing')->group(function () {
    Route::get("/dashboard", [DashboardController::class, 'index'])->name("dashboard");

    Route::middleware('bimbingan.sequence')
      ->prefix('bimbingan')
      ->where(['jenis' => 'proposal|hasil|skripsi'])
      ->group(function () {
        Route::get('/{jenis}', [BimbinganController::class, 'index'])->name('bimbingan.index');

        Route::get('/{jenis}/bimbingan', [BimbinganController::class, 'bimbingan'])->name('bimbingan.bimbingan');

        Route::post('/{jenis}/create-submission', [BimbinganController::class, 'createSubmission'])->name('bimbingan.createSubmission');

        Route::get('/{jenis}/minta-penguji', [BimbinganController::class, 'mintaPenguji'])->name('bimbingan.mintaPenguji');

        Route::post('/{jenis}/create-kajur-submission', [KajurSubmissionController::class, 'createKajurSubmission'])->name('bimbingan.createKajurSubmission');
      });

    Route::middleware('ujian.sequence')
      ->prefix('ujian')
      ->where(['jenis' => 'proposal|hasil|skripsi'])
      ->group(function () {
        Route::get('/{jenis}', [UjianController::class, 'index'])->name('ujian');

        Route::get('/{jenis}/pengajuan', [UjianController::class, 'showPengajuan'])->name('ujian.pengajuan');
        Route::post('/{jenis}/pengajuan', [UjianController::class, 'submitPengajuan'])->name('ujian.submitPengajuan');

        Route::get('/{jenis}/undangan', [UjianController::class, 'showUndangan'])->name('ujian.undangan');

        Route::get('/{jenis}/hasil-ujian', [UjianController::class, 'showHasilUjian'])->name('ujian.hasil-ujian');
        Route::post('/{jenis}/hasil-ujian', [UjianController::class, 'submitHasilUjian'])->name('ujian.submitHasilUjian');

        Route::get('/{jenis}/selesai', [UjianController::class, 'selesai'])->name('ujian.selesai');
      });
  });
});
