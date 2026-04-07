<?php

namespace App\Providers;

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
    }
}
