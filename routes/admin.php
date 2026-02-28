<?php

use App\Http\Controllers\Admin\DosenController;
use App\Http\Controllers\Admin\MahasiswaController;
use App\Http\Controllers\Admin\UjianController;
use App\Http\Controllers\Admin\PublikasiController;
use Illuminate\Support\Facades\Route;

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

  // Manajemen Dosen (resource)
  Route::get('/dosen', [DosenController::class, 'index'])->name('dosen.index');
  Route::get('/dosen/create', [DosenController::class, 'create'])->name('dosen.create');
  Route::post('/dosen', [DosenController::class, 'store'])->name('dosen.store');
  Route::get('/dosen/{id}', [DosenController::class, 'show'])->name('dosen.show');
  Route::get('/dosen/{id}/edit', [DosenController::class, 'edit'])->name('dosen.edit');
  Route::put('/dosen/{id}', [DosenController::class, 'update'])->name('dosen.update');
  Route::delete('/dosen/{id}', [DosenController::class, 'destroy'])->name('dosen.destroy');

  Route::get('/publikasi', [PublikasiController::class, 'index'])->name('publikasi.index');
  Route::get('/publikasi/create', [PublikasiController::class, 'create'])->name('publikasi.create');
  Route::post('/publikasi', [PublikasiController::class, 'store'])->name('publikasi.store');
  Route::get('/publikasi/{id}', [PublikasiController::class, 'show'])->name('publikasi.show');
  Route::get('/publikasi/{id}/edit', [PublikasiController::class, 'edit'])->name('publikasi.edit');
  Route::put('/publikasi/{id}', [PublikasiController::class, 'update'])->name('publikasi.update');
  Route::delete('/publikasi/{id}', [PublikasiController::class, 'destroy'])->name('publikasi.destroy');
});
