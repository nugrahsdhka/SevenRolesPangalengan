<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request; // Pastikan baris ini ada

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        // Alias untuk Spatie Role Middleware
        $middleware->alias([
            'role' => \Spatie\Permission\Middleware\RoleMiddleware::class,
            'permission' => \Spatie\Permission\Middleware\PermissionMiddleware::class,
        ]);

        // ✅ PERBAIKAN: Mengubah tujuan pengalihan untuk pengguna yang belum login
        // Blok kode ini akan dijalankan setiap kali middleware 'auth' gagal.
        $middleware->redirectGuestsTo(function (Request $request) {
            // Jika request adalah API, jangan redirect.
            if ($request->expectsJson()) {
                return null;
            }

            // Untuk semua request web lainnya, hentikan proses dan tampilkan halaman 403.
            abort(403, 'Akses Ditolak. Anda harus login untuk mengakses halaman ini.');
        });
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();