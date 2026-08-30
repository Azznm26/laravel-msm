<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

/**
 * Middleware ini meng-update kolom `last_seen` milik user yang sedang login
 * di setiap request yang lewat middleware group 'web' (atau grup manapun
 * tempat middleware ini didaftarkan).
 *
 * Dengan ini, fitur "User Online" / "Sedang Aktif" di dashboard admin
 * (yang membaca User::where('last_seen', '>=', now()->subMinutes(5)))
 * akan benar-benar terisi selama user aktif membuka halaman.
 *
 * CARA DAFTAR (Laravel 11/12 — bootstrap/app.php):
 *
 *   ->withMiddleware(function (Middleware $middleware) {
 *       $middleware->appendToGroup('web', \App\Http\Middleware\UpdateLastSeen::class);
 *   })
 *
 * CATATAN: update dibatasi maksimal sekali per menit per user (bukan di
 * setiap request) supaya tidak membebani database dengan write berulang.
 */
class UpdateLastSeen
{
    public function handle(Request $request, Closure $next)
    {
        if ($request->user()) {
            $user = $request->user();

            // Hanya update jika sudah lebih dari 60 detik sejak update terakhir,
            // supaya tidak menulis ke DB di setiap single request/poll.
            if (! $user->last_seen || now()->diffInSeconds($user->last_seen) >= 60) {
                $user->timestamps = false; // jangan sentuh updated_at
                $user->forceFill(['last_seen' => now()])->save();
            }
        }

        return $next($request);
    }
}
