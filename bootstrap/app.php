<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Http\Middleware\SetLocale; // ✅ tambah ini

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        api: __DIR__ . '/../routes/api.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {

        // ✅ Terapkan locale user di setiap request web
        $middleware->web(append: [
            SetLocale::class,
        ]);

        // ✅ INI BAGIAN PENTING UNTUK MEMPERBAIKI REDIRECT LOOP
        $middleware->redirectUsersTo(function (Request $request) {

            // Ambil data user yang sedang login
            $user = Auth::user();

            if ($user) {
                // Jika Admin/Super Admin -> Arahkan ke Dashboard Admin
                if (in_array($user->role, ['admin', 'super_admin'])) {
                    return route('admin.dashboard');
                }

                // Jika User Biasa -> Arahkan ke Dashboard User
                return route('user.dashboard');
            }

            // Jika entah kenapa user tidak ditemukan, kembalikan ke home
            return '/';
        });
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
