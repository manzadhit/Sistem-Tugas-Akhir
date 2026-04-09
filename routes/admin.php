<?php

use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\DosenController;
use App\Http\Controllers\Admin\MataKuliahController;
use App\Http\Controllers\Admin\MahasiswaController;
use App\Http\Controllers\Admin\PeriodeAkademikController;
use App\Http\Controllers\Admin\VerifikasiSyaratController;
use App\Http\Controllers\Admin\VerifikasiHasilController;
use App\Http\Controllers\Admin\PublikasiController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
  Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

  // Verifikasi Syarat Ujian
  Route::prefix('ujian/verifikasi-syarat')->name('ujian.syarat.')->group(function () {
    Route::get('/', [VerifikasiSyaratController::class, 'index'])->name('index');
    Route::get('/{id}', [VerifikasiSyaratController::class, 'detail'])->name('detail');
    Route::post('/{id}', [VerifikasiSyaratController::class, 'proses'])->name('proses');
    Route::get('/{id}/undangan', [VerifikasiSyaratController::class, 'showUndangan'])->name('undangan');
    Route::post('/{id}/undangan', [VerifikasiSyaratController::class, 'storeUndangan'])->name('undangan.store');
    Route::post('/{id}/undangan/kirim', [VerifikasiSyaratController::class, 'kirimUndangan'])->name('undangan.kirim');
  });

  // Verifikasi Hasil Ujian
  Route::prefix('ujian/verifikasi-hasil')->name('ujian.hasil.')->group(function () {
    Route::get('/', [VerifikasiHasilController::class, 'index'])->name('index');
    Route::get('/{id}', [VerifikasiHasilController::class, 'detail'])->name('detail');
    Route::post('/{id}', [VerifikasiHasilController::class, 'proses'])->name('proses');
  });

  // Manajemen Mahasiswa (resource)
  Route::get('/mahasiswa', [MahasiswaController::class, 'index'])->name('mahasiswa.index');
  Route::get('/mahasiswa/create', [MahasiswaController::class, 'create'])->name('mahasiswa.create');
  Route::post('/mahasiswa', [MahasiswaController::class, 'store'])->name('mahasiswa.store');
  Route::post('/mahasiswa/{id}/reset-password', [MahasiswaController::class, 'resetPassword'])->name('mahasiswa.reset-password');
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

  // Manajemen Mata Kuliah
  Route::get('/mata-kuliah', [MataKuliahController::class, 'index'])->name('mata-kuliah.index');
  Route::post('/mata-kuliah', [MataKuliahController::class, 'store'])->name('mata-kuliah.store');
  Route::put('/mata-kuliah/{mataKuliah}', [MataKuliahController::class, 'update'])->name('mata-kuliah.update');
  Route::delete('/mata-kuliah/{mataKuliah}', [MataKuliahController::class, 'destroy'])->name('mata-kuliah.destroy');

  // Manajemen Publikasi
  Route::get('/publikasi', [PublikasiController::class, 'index'])->name('publikasi.index');
  Route::get('/publikasi/create', [PublikasiController::class, 'create'])->name('publikasi.create');
  Route::post('/publikasi', [PublikasiController::class, 'store'])->name('publikasi.store');
  Route::get('/publikasi/{id}', [PublikasiController::class, 'show'])->name('publikasi.show');
  Route::get('/publikasi/{id}/edit', [PublikasiController::class, 'edit'])->name('publikasi.edit');
  Route::put('/publikasi/{id}', [PublikasiController::class, 'update'])->name('publikasi.update');
  Route::delete('/publikasi/{id}', [PublikasiController::class, 'destroy'])->name('publikasi.destroy');

  // Manajemen Periode Akademik
  Route::prefix('periode')->name('periode.')->group(function () {
    Route::get('/', [PeriodeAkademikController::class, 'index'])->name('index');
    Route::get('/create', [PeriodeAkademikController::class, 'create'])->name('create');
    Route::post('/', [PeriodeAkademikController::class, 'store'])->name('store');
    Route::get('/{periodeAkademik}/edit', [PeriodeAkademikController::class, 'edit'])->name('edit');
    Route::put('/{periodeAkademik}', [PeriodeAkademikController::class, 'update'])->name('update');
    Route::delete('/{periodeAkademik}', [PeriodeAkademikController::class, 'destroy'])->name('destroy');
    Route::patch('/{periodeAkademik}/activate', [PeriodeAkademikController::class, 'activate'])->name('activate');
    Route::patch('/{periodeAkademik}/complete', [PeriodeAkademikController::class, 'complete'])->name('complete');
  });

});
