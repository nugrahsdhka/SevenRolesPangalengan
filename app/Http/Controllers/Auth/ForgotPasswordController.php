<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;

class ForgotPasswordController extends Controller
{
    /**
     * Tampilkan form "Lupa Password".
     * Route: GET /forgot-password (password.request)
     */
    public function create()
    {
        return view('auth.forgot-password');
    }

    /**
     * Proses kirim email berisi link reset password.
     * Route: POST /forgot-password (password.email)
     */
    public function store(Request $request)
    {
        $request->validate([
            'email' => ['required', 'email'],
        ]);

        // Kirim link reset password ke email
        $status = Password::sendResetLink(
            $request->only('email')
        );

        // Jika berhasil, Laravel mengembalikan status 'passwords.sent'
        // Kalau gagal (email tidak ditemukan, dsb) status lain
        return $status === Password::RESET_LINK_SENT || $status === Password::RESET_THROTTLED
            ? back()->with('status', __($status))
            : back()->withErrors(['email' => __($status)]);
    }
}
