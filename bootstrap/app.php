<?php

use App\Http\Middleware\EnsureBimbinganSequence;
use App\Http\Middleware\EnsureMahasiswaAktif;
use App\Http\Middleware\EnsureMahasiswaHasPembimbing;
use App\Http\Middleware\EnsureRole;
use App\Http\Middleware\EnsureUjianSequence;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->alias([
            'role' => EnsureRole::class,
            'mahasiswa.aktif' => EnsureMahasiswaAktif::class,
            'has.pembimbing' => EnsureMahasiswaHasPembimbing::class,
            'ujian.sequence' => EnsureUjianSequence::class,
            'bimbingan.sequence' => EnsureBimbinganSequence::class
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
