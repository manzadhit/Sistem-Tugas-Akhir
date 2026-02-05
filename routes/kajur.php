<?php

use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'role:kajur'])->prefix('kajur')->name('kajur.')->group(function () {
  Route::get("/dashboard", function () {
    return view("kajur.dashboard");
  })->name("dashboard");
});
