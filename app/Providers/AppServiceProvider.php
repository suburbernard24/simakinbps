<?php

namespace App\Providers;

use Illuminate\Support\Facades\View;
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
        // Share app settings & active announcements to all views.
        // Wrapped in try-catch so it doesn't break if the tables don't exist yet.
        try {
            View::share('appSettings', cache()->remember('app_settings', 300, function () {
                return \App\Models\AppSetting::pluck('value', 'key')->toArray();
            }));

            View::share('pengumumanAktif', cache()->remember('pengumuman_aktif', 60, function () {
                return \App\Models\Pengumuman::aktif()
                    ->orderByDesc('created_at')
                    ->get();
            }));
        } catch (\Throwable $e) {
            View::share('appSettings', []);
            View::share('pengumumanAktif', collect());
        }
    }
}
