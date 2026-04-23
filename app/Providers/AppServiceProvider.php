<?php

namespace App\Providers;

use App\Models\KajurSubmission;
use App\Models\PermintaanPembimbing;
use App\Models\Submission;
use App\Models\Ujian;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        view()->composer('partials.header', function ($view) {
            $user = auth()->user();

            $view->with([
                'notifications' => $user
                    ? $user->notifications()->latest()->limit(3)->get()
                    : collect(),
                'unreadNotificationsCount' => $user
                    ? $user->unreadNotifications()->count()
                    : 0,
            ]);
        });

        view()->composer('admin.sidebar', function ($view) {
            $view->with([
                'countSyarat' => Ujian::whereIn('status', ['menunggu_verifikasi_syarat', 'menunggu_undangan'])->count(),
                'countHasil'  => Ujian::where('status', 'menunggu_verifikasi_hasil')->count(),
            ]);
        });

        view()->composer('dosen.sidebar', function ($view) {
            $dosenId = auth()->user()?->profileDosen?->id;

            $view->with([
                'countPendingBimbingan' => $dosenId
                    ? Submission::where('status', 'pending')
                        ->whereHas('dosenPembimbing', fn($q) => $q->where('dosen_id', $dosenId)->where('status_aktif', true))
                        ->whereHas('tugasAkhir.mahasiswa', fn($q) => $q->where('status_akademik', 'aktif'))
                        ->count()
                    : 0,
            ]);
        });

        view()->composer('kajur.sidebar', function ($view) {
            $dosenId = auth()->user()?->profileDosen?->id;

            $view->with([
                'countPermintaanPembimbing' => PermintaanPembimbing::where('status', 'pending')
                    ->where('status_verifikasi_bukti', '!=', 'ditolak')
                    ->count(),

                'countPermintaanPenguji' => KajurSubmission::where('tahapan', 'proposal')
                    ->whereIn('status', ['pending', 'acc'])
                    ->whereDoesntHave('tugasAkhir.mahasiswa.dosenPenguji')
                    ->count(),

                'countPersetujuanKajur' => KajurSubmission::whereIn('tahapan', ['hasil', 'skripsi'])
                    ->where('status', 'pending')
                    ->count(),

                'countPendingBimbinganKajur' => $dosenId
                    ? Submission::where('status', 'pending')
                        ->whereHas('dosenPembimbing', fn($q) => $q->where('dosen_id', $dosenId)->where('status_aktif', true))
                        ->whereHas('tugasAkhir.mahasiswa', fn($q) => $q->where('status_akademik', 'aktif'))
                        ->count()
                    : 0,
            ]);
        });
    }
}
