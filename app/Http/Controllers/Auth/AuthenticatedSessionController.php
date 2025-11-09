<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class AuthenticatedSessionController extends Controller
{
    /**
     * Tampilkan form login.
     */
    public function create()
    {
        return view('auth.login');
    }

    /**
     * Proses login.
     */
    public function store(Request $request)
    {
        // Validasi input
        $credentials = $request->validate([
            'email'    => ['required', 'string', 'email'],
            'password' => ['nullable', 'string'],
        ]);

        // Cari user berdasarkan email
        $user = User::where('email', $credentials['email'])->first();

        if (!$user) {
            throw ValidationException::withMessages([
                'email' => __('Email atau password tidak sesuai.'),
            ]);
        }

        // Jika email BELUM terverifikasi
        if (!$user->hasVerifiedEmail()) {
            // Kalau sudah ada token & belum kadaluarsa → arahkan ke halaman verifikasi token
            if (
                $user->verification_token &&
                $user->verification_token_expires_at &&
                now()->lessThan($user->verification_token_expires_at)
            ) {
                return redirect()
                    ->route('verification.token.show', ['email' => $user->email])
                    ->with('status', 'Silakan masukkan token verifikasi yang telah dikirim ke email Anda.');
            }

            // Kalau belum punya token → arahkan ke halaman lengkapi registrasi
            return redirect()
                ->route('register.complete.show', ['email' => $user->email]);
        }

        // Kalau sudah terverifikasi → cek password
        if (!Auth::attempt($credentials, $request->boolean('remember'))) {
            throw ValidationException::withMessages([
                'email' => __('Email atau password tidak sesuai.'),
            ]);
        }

        // Regenerasi session (security)
        $request->session()->regenerate();

        // Redirect ke dashboard (yang sudah di-handle di web.php)
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
