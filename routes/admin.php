<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PdfController;
use App\Http\Controllers\Admin\UjianController;
use App\Http\Controllers\Admin\MahasiswaController;

Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
  Route::get("/dashboard", function () {
    return view("admin.dashboard");
  })->name("dashboard");

  Route::get('/ujian/{jenis}', [UjianController::class, 'index'])->name('ujian.verifikasi');

  Route::get('/ujian/{jenis}/{id}/verifikasi', [UjianController::class, 'detailVerifikasi'])->name('ujian.verifikasi.detail');
  Route::post('/ujian/{jenis}/{id}/verifikasi', [UjianController::class, 'prosesVerifikasi'])->name('ujian.verifikasi.proses');

  Route::get('/ujian/{jenis}/{id}/undangan', [UjianController::class, 'showUndangan'])->name('ujian.undangan');
  Route::post('/ujian/{jenis}/{id}/undangan', [UjianController::class, 'storeUndangan'])->name('ujian.undangan.store');

  Route::post('ujian/{jenis}/{id}/undangan/kirim', [UjianController::class, 'kirimUndangan'])->name('ujian.undangan.kirim');

  // Verifikasi Hasil
  Route::get('/ujian/{jenis}/hasil-ujian', [UjianController::class, 'indexHasilUjian'])->name('ujian.hasil-ujian.index');
  Route::get('/ujian/{jenis}/{id}/hasil-ujian', [UjianController::class, 'detailHasilUjian'])->name('ujian.hasil-ujian.detail');
  Route::post('/ujian/{jenis}/{id}/hasil-ujian', [UjianController::class, 'prosesHasilUjian'])->name('ujian.hasil-ujian.proses');

  // Manajemen Mahasiswa (resource)
  Route::get('/mahasiswa', [MahasiswaController::class, 'index'])->name('mahasiswa.index');
  Route::get('/mahasiswa/create', [MahasiswaController::class, 'create'])->name('mahasiswa.create');
  Route::post('/mahasiswa', [MahasiswaController::class, 'store'])->name('mahasiswa.store');
  Route::get('/mahasiswa/{id}', [MahasiswaController::class, 'show'])->name('mahasiswa.show');
  Route::get('/mahasiswa/{id}/edit', [MahasiswaController::class, 'edit'])->name('mahasiswa.edit');
  Route::put('/mahasiswa/{id}', [MahasiswaController::class, 'update'])->name('mahasiswa.update');
  Route::delete('/mahasiswa/{id}', [MahasiswaController::class, 'destroy'])->name('mahasiswa.destroy');
});
