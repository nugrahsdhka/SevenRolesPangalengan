<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class EmailVerificationEntryController extends Controller
{
    /**
     * Tampilkan form untuk masuk ke alur verifikasi
     * (user cukup isi email saja).
     */
    public function create()
    {
        return view('auth.verify-email-entry');
    }

    /**
     * Proses email untuk menentukan ke mana user diarahkan:
     * - kalau sudah terverifikasi → arahkan ke login dengan pesan
     * - kalau belum & ada token aktif → ke halaman input token
     * - kalau belum & belum ada token → ke halaman lengkapi registrasi
     */
    public function store(Request $request)
    {
        // Validasi email
        $data = $request->validate([
            'email' => ['required', 'string', 'email'],
        ]);

        $user = User::where('email', $data['email'])->first();

        if (!$user) {
            return back()->withErrors([
                'email' => 'Akun dengan email tersebut tidak ditemukan.',
            ])->withInput();
        }

        // Kalau sudah terverifikasi → langsung suruh login
        if ($user->hasVerifiedEmail()) {
            return redirect()
                ->route('login')
                ->with('status', 'Email Anda sudah terverifikasi. Silakan login.');
        }

        // Kalau BELUM terverifikasi:
        // cek apakah sudah punya token yang masih aktif
        if (
            $user->verification_token &&
            $user->verification_token_expires_at &&
            now()->lessThan($user->verification_token_expires_at)
        ) {
            return redirect()
                ->route('verification.token.show', ['email' => $user->email])
                ->with('status', 'Silakan masukkan token verifikasi yang telah dikirim ke email Anda.');
        }

        // Kalau belum punya token / token kadaluarsa → ke halaman lengkapi registrasi
        return redirect()
            ->route('register.complete.show', ['email' => $user->email])
            ->with('status', 'Silakan lengkapi registrasi dan lakukan verifikasi email.');
    }
}
