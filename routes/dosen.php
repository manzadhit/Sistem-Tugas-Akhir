<?php

use App\Http\Controllers\Dosen\MahasiswaBimbingan;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'role:dosen'])->prefix('dosen')->name('dosen.')->group(function () {
  Route::get("/dashboard", function () {
    return view("dosen.dashboard");
  })->name("dashboard");

  Route::get('/bimbingan', [MahasiswaBimbingan::class, 'index'])->name('bimbingan.index');
});
