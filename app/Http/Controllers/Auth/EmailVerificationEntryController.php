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
     * - akun pending dengan token aktif → ke halaman input token
     * - selain itu (termasuk email tak dikenal / sudah terverifikasi)
     *   → ke halaman lengkapi registrasi (respons seragam)
     */
    public function store(Request $request)
    {
        // Validasi email
        $data = $request->validate([
            'email' => ['required', 'string', 'email'],
        ]);

        // Email tidak terdaftar & email yang sudah terverifikasi diperlakukan sama
        // dengan akun "belum punya token" (→ form lengkapi registrasi), supaya
        // status akun tidak bisa ditebak lewat halaman ini.
        $user = User::where('email', $data['email'])
            ->whereNull('email_verified_at')
            ->first();

        // Akun pending dengan token yang masih aktif → langsung ke input token
        if (
            $user &&
            $user->verification_token &&
            $user->verification_token_expires_at &&
            now()->lessThan($user->verification_token_expires_at)
        ) {
            return redirect()
                ->route('verification.token.show', ['email' => $data['email']])
                ->with('status', 'Silakan masukkan token verifikasi yang telah dikirim ke email Anda.');
        }

        // Kalau belum punya token / token kadaluarsa → ke halaman lengkapi registrasi
        return redirect()
            ->route('register.complete.show', ['email' => $data['email']])
            ->with('status', 'Silakan lengkapi registrasi dan lakukan verifikasi email.');
    }
}
