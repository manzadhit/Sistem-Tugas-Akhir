<?php

use App\Http\Controllers\NotificationController;
use App\Http\Controllers\PrivateFileController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;


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
        "sekjur" => redirect()->route('dosen.dashboard'),
        "kajur" => redirect()->route('kajur.dashboard'),
        "admin" => redirect()->route('admin.dashboard'),
        default => abort(403),
    };
})->name('dashboard');

Route::middleware('auth')->prefix('notifications')->name('notifications.')->group(function () {
    Route::get('/', [NotificationController::class, 'index'])->name('index');
    Route::post('/mark-all-read', [NotificationController::class, 'markAllRead'])->name('markAllRead');
});

Route::middleware('auth')
    ->prefix('files/{type}/{id}')
    ->where('type', 'submission-file|kajur-submission-file|dokumen-ujian|undangan-ujian|permintaan-pembimbing')
    ->whereNumber('id')
    ->name('files.')
    ->group(function () {
        Route::get('/view', [PrivateFileController::class, 'view'])->name('view');
        Route::get('/download', [PrivateFileController::class, 'download'])->name('download');
    });


require __DIR__ . '/auth.php';
require __DIR__ . '/mahasiswa.php';
require __DIR__ . '/dosen.php';
require __DIR__ . '/admin.php';
require __DIR__ . '/kajur.php';
