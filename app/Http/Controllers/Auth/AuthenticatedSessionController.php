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
        // Validasi input: sekarang password WAJIB
        $credentials = $request->validate([
            'email'    => ['required', 'string', 'email'],
            'password' => ['required', 'string'],
        ]);

        // Cari user
        $user = User::where('email', $credentials['email'])->first();

        if (!$user) {
            throw ValidationException::withMessages([
                'email' => __('Email atau password tidak sesuai.'),
            ]);
        }

        // Kalau email BELUM terverifikasi → arahkan ke halaman verifikasi email
        if (!$user->hasVerifiedEmail()) {
            return redirect()
                ->route('verification.entry.show')
                ->withErrors([
                    'email' => 'Email Anda belum terverifikasi. Silakan lakukan verifikasi melalui menu "Verifikasi email".',
                ])
                ->withInput([
                    'email' => $credentials['email'],
                ]);
        }

        // Kalau sudah terverifikasi → cek password
        if (!Auth::attempt($credentials, $request->boolean('remember'))) {
            throw ValidationException::withMessages([
                'email' => __('Email atau password tidak sesuai.'),
            ]);
        }

        // Regenerasi session (security)
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
