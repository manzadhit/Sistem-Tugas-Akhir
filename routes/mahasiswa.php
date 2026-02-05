<?php

use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'role:mahasiswa'])->prefix('mahasiswa')->name('mahasiswa.')->group(function () {
  Route::get("/dashboard", function () {
    return view("mahasiswa.dashboard");
  })->name("dashboard");
});
