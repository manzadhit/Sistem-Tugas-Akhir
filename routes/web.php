<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Route;

Route::middleware("auth")->get('/', function () {
    $user = auth()->user();

    if(!$user) {
        return redirect()->route("login");
    }

    return redirect()->route("dashboard");
});

Route::middleware('auth')->get("/dashboard", function () {
    return match (auth()->user()->role) {
        "mahasiswa" => redirect()->route('mahasiswa.dashboard'),
        "dosen" => redirect()->route('dosen.dashboard'),
        "kajur" => redirect()->route('kajur.dashboard'),
        "admin" => redirect()->route('admin.dashboard'),
        default => redirect('/'),
    };
})->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
require __DIR__.'/mahasiswa.php';
require __DIR__.'/dosen.php';
require __DIR__.'/admin.php';
require __DIR__.'/kajur.php';
