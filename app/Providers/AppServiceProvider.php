<?php

namespace App\Providers;

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Str;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        Paginator::defaultView('pagination.red');

        $this->configureRateLimiting();
    }

    /**
     * Rate limiter untuk alur registrasi / verifikasi token.
     *
     * Kunci per-email mencegah brute-force token 6 digit dari banyak IP,
     * kunci per-IP mencegah satu IP menyisir banyak email.
     */
    protected function configureRateLimiting(): void
    {
        $emailKey = fn (Request $r) => Str::lower((string) $r->input('email', $r->query('email', '')));

        // Percobaan memasukkan token: 5x / 3 menit per email, 20x / menit per IP.
        RateLimiter::for('verify-token', fn (Request $r) => [
            Limit::perMinutes(3, 5)->by('verify-email:' . $emailKey($r)),
            Limit::perMinute(20)->by('verify-ip:' . $r->ip()),
        ]);

        // Pengiriman email token (lengkapi registrasi + kirim ulang): 3x / 10 menit per email.
        RateLimiter::for('token-send', fn (Request $r) => [
            Limit::perMinutes(10, 3)->by('send-email:' . $emailKey($r)),
            Limit::perMinute(10)->by('send-ip:' . $r->ip()),
        ]);
    }
}
