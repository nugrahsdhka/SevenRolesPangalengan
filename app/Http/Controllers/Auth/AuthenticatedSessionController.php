<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Lockout;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class AuthenticatedSessionController extends Controller
{
    private const MAX_ATTEMPTS = 5;

    /**
     * Tampilkan form login.
     */
    public function create()
    {
        return view('auth.login');
    }

    /**
     * Proses login.
     *
     * - Dibatasi 5 percobaan gagal per kombinasi email + IP.
     * - Pesan gagal selalu sama (email tidak ada / password salah / akun belum
     *   diverifikasi), supaya status akun tidak bisa ditebak dari luar.
     * - Status "belum terverifikasi" baru diberitahukan SETELAH password terbukti benar.
     */
    public function store(Request $request)
    {
        $credentials = $request->validate([
            'email'    => ['required', 'string', 'email'],
            'password' => ['required', 'string'],
        ]);

        $key = Str::transliterate(Str::lower($credentials['email']) . '|' . $request->ip());

        if (RateLimiter::tooManyAttempts($key, self::MAX_ATTEMPTS)) {
            event(new Lockout($request));

            $seconds = RateLimiter::availableIn($key);

            throw ValidationException::withMessages([
                'email' => trans('auth.throttle', [
                    'seconds' => $seconds,
                    'minutes' => ceil($seconds / 60),
                ]),
            ]);
        }

        $user = User::where('email', $credentials['email'])->first();

        if ($user) {
            $passwordOk = Hash::check($credentials['password'], $user->password);
        } else {
            // Samakan waktu respons dengan kasus user ada (cegah timing attack).
            Hash::make($credentials['password']);
            $passwordOk = false;
        }

        if (! $passwordOk) {
            RateLimiter::hit($key);

            throw ValidationException::withMessages([
                'email' => __('Email atau password tidak sesuai. Jika akun Anda baru dibuat oleh admin, gunakan menu "Verifikasi email".'),
            ]);
        }

        // Password benar tapi email belum diverifikasi → arahkan ke alur verifikasi.
        if (! $user->hasVerifiedEmail()) {
            return redirect()
                ->route('verification.entry.show')
                ->withErrors([
                    'email' => 'Email Anda belum terverifikasi. Silakan lakukan verifikasi melalui menu "Verifikasi email".',
                ])
                ->withInput(['email' => $credentials['email']]);
        }

        Auth::login($user, $request->boolean('remember'));
        RateLimiter::clear($key);

        $request->session()->regenerate();

        return redirect()->intended(route('dashboard'));
    }

    /**
     * Logout.
     */
    public function destroy(Request $request)
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}
