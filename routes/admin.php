<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\UjianController;

Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
  Route::get("/dashboard", function () {
    return view("admin.dashboard");
  })->name("dashboard");

  Route::get('/ujian/verifikasi/{jenis}', [UjianController::class, 'index'])->name('ujian.verifikasi');
  Route::get('/ujian/verifikasi/{jenis}/{id}', [UjianController::class, 'detailVerifikasi'])->name('ujian.verifikasi.detail');
  Route::post('/ujian/verifikasi/{jenis}/{id}', [UjianController::class, 'prosesVerifikasi'])->name('ujian.verifikasi.proses');
});
