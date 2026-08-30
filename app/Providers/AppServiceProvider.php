<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Gate; // ← tambahkan baris ini

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // biarkan isi yang sudah ada, jangan diubah
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // kalau di sini sudah ada kode lain, JANGAN dihapus —
        // cukup tambahkan blok Gate::before di bawah/atasnya

        Gate::before(function ($user, $ability) {
            return $user->hasRole('super_admin') ? true : null;
        });
    }
}
