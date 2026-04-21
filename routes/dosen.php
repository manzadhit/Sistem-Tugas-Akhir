<?php

use App\Http\Controllers\Dosen\DashboardController;
use App\Http\Controllers\Dosen\InputNilaiController;
use App\Http\Controllers\Dosen\JadwalController;
use App\Http\Controllers\Dosen\MahasiswaBimbinganController;
use App\Http\Controllers\Dosen\ProfileController as DosenProfileController;
use App\Http\Controllers\Dosen\PublikasiController;
use App\Http\Controllers\Dosen\PengujianController;
use App\Http\Controllers\Dosen\UndanganController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'password.changed', 'role:dosen,kajur,sekjur'])->prefix('dosen')->name('dosen.')->group(function () {
  Route::get("/dashboard", [DashboardController::class, 'index'])->name("dashboard");

  Route::get('/pengujian', [PengujianController::class, 'index'])->name('pengujian.index');

  Route::get('/profile', [DosenProfileController::class, 'edit'])->name('profile.edit');
  Route::put('/profile', [DosenProfileController::class, 'update'])->name('profile.update');

  Route::get('/bimbingan', [MahasiswaBimbinganController::class, 'index'])->name('bimbingan.index');
  Route::get('/bimbingan/mahasiswa', [MahasiswaBimbinganController::class, 'mahasiswaList'])->name('bimbingan.mahasiswa');
  Route::get('/bimbingan/mahasiswa-lulus', [MahasiswaBimbinganController::class, 'mahasiswaLulusList'])->name('bimbingan.mahasiswa-lulus');
  Route::get('/bimbingan/mahasiswa/{dosenPembimbing}/riwayat', [MahasiswaBimbinganController::class, 'riwayatBimbingan'])->name('bimbingan.riwayat');
  Route::get('/bimbingan/{submission}/detail', [MahasiswaBimbinganController::class, 'getDetail'])->name('bimbingan.detail');
  Route::put('/bimbingan/{submission}/review', [MahasiswaBimbinganController::class, 'review'])->name('bimbingan.review');

  Route::get('/undangan', [UndanganController::class, 'index'])->name('undangan.index');
  Route::get('/jadwal', [JadwalController::class, 'index'])->name('jadwal.index');
  Route::get('/input-nilai', [InputNilaiController::class, 'index'])->name('nilai.index');
  Route::post('/input-nilai/{dosenPenguji}', [InputNilaiController::class, 'store'])->name('nilai.store');

  Route::prefix('publikasi')->name('publikasi.')->controller(PublikasiController::class)->group(function () {
    Route::get('/', 'index')->name('index');
    Route::get('/create', 'create')->name('create');
    Route::post('/', 'store')->name('store');
    Route::post('/import', 'import')->name('import');
    Route::get('/{publikasi}', 'show')->name('show');
    Route::get('/{publikasi}/edit', 'edit')->name('edit');
    Route::put('/{publikasi}', 'update')->name('update');
    Route::delete('/{publikasi}', 'destroy')->name('destroy');
  });
});
