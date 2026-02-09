<?php

use App\Http\Controllers\Kajur\DashboardController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'role:kajur'])->prefix('kajur')->name('kajur.')->group(function () {
  Route::get("/dashboard", [DashboardController::class, 'index'])->name("dashboard");
});
