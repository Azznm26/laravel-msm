<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminAccessMiddleware
{
    /**
     * Handle an incoming request.
     *
     * Gerbang utama /admin. TIDAK mengecek nama role tertentu.
     * Siapa pun yang punya MINIMAL SATU permission apa pun boleh masuk
     * ke area /admin — pembatasan lebih detail (menu mana yang boleh
     * diakses) dilakukan per-route lewat middleware `can:` di web.php,
     * dan permission itu sendiri diatur bebas lewat halaman
     * Manage Roles / Manage Permissions / Assign Role.
     */
    public function handle(Request $request, Closure $next)
    {
        if (!Auth::check()) {
            return redirect('/login')->with('error', 'Silakan login terlebih dahulu.');
        }

        $user = Auth::user();

        // Gate::before di AuthServiceProvider sudah menangani superadmin,
        // jadi di sini tinggal cek permission biasa untuk role lain.
        if ($user->getAllPermissions()->isEmpty()) {
            return redirect('/')->with('error', 'Anda tidak memiliki akses ke halaman admin.');
        }

        return $next($request);
    }
}
