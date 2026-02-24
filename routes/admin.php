<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\UjianController;
use App\Http\Controllers\PdfController;

Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
  Route::get("/dashboard", function () {
    return view("admin.dashboard");
  })->name("dashboard");

  Route::get('/ujian/{jenis}', [UjianController::class, 'index'])->name('ujian.verifikasi');

  Route::get('/ujian/{jenis}/{id}/verifikasi', [UjianController::class, 'detailVerifikasi'])->name('ujian.verifikasi.detail');
  Route::post('/ujian/{jenis}/{id}/verifikasi', [UjianController::class, 'prosesVerifikasi'])->name('ujian.verifikasi.proses');

  Route::get('/ujian/{jenis}/{id}/undangan', [UjianController::class, 'showUndangan'])->name('ujian.undangan');
  Route::post('/ujian/{jenis}/{id}/undangan', [UjianController::class, 'storeUndangan'])->name('ujian.undangan.store');

});
