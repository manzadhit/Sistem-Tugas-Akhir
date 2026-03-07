<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;
use App\Http\Controllers\NotificationController;


Route::middleware("auth")->get('/', function () {
    $user = Auth::user();

    if (!$user) {
        return redirect()->route("login");
    }

    return redirect()->route("dashboard");
});

Route::middleware('auth')->get("/dashboard", function () {
    $user = Auth::user();

    return match ($user->role) {
        "mahasiswa" => $user->profileMahasiswa?->dosenPembimbing()->exists() ? redirect()->route('mahasiswa.dashboard') : redirect()->route('mahasiswa.permintaan-pembimbing.create'),
        "dosen" => redirect()->route('dosen.dashboard'),
        "kajur" => redirect()->route('kajur.dashboard'),
        "admin" => redirect()->route('admin.dashboard'),
        default => abort(403),
    };
})->name('dashboard');

Route::get('/download', function (Request $request) {
    $path = $request->query('path');
    abort_if(!$path || !Storage::exists($path), 404);
    return Storage::download($path);
})->name('storage.download')->middleware('auth');

Route::middleware('auth')->prefix('notifications')->name('notifications.')->group(function () {
    Route::get('/', [NotificationController::class, 'index'])->name('index');
    Route::post('/mark-all-read', [NotificationController::class, 'markAllRead'])->name('markAllRead');
});

require __DIR__ . '/auth.php';
require __DIR__ . '/mahasiswa.php';
require __DIR__ . '/dosen.php';
require __DIR__ . '/admin.php';
require __DIR__ . '/kajur.php';
