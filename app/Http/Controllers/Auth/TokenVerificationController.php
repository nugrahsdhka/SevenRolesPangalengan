<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Mail\VerificationTokenMail;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\ValidationException;

class TokenVerificationController extends Controller
{
    /**
     * Tampilkan form verifikasi token.
     */
    public function show(Request $request)
    {
        $email = $request->query('email');

        if (!$email) {
            return redirect()->route('login')->withErrors([
                'email' => 'Email tidak ditemukan. Silakan login kembali.',
            ]);
        }

        $user = User::where('email', $email)->first();

        if (!$user) {
            return redirect()->route('login')->withErrors([
                'email' => 'Akun tidak ditemukan.',
            ]);
        }

        if ($user->hasVerifiedEmail()) {
            return redirect()->route('login')->with('status', 'Akun Anda sudah terverifikasi. Silakan login.');
        }

        $expired = $user->verification_token_expires_at
            ? now()->greaterThan($user->verification_token_expires_at)
            : true;

        return view('auth.verify-token', [
            'user'    => $user,
            'email'   => $email,
            'expired' => $expired,
        ]);
    }

    /**
     * Proses verifikasi token.
     */
    public function verify(Request $request)
    {
        $request->validate([
            'email' => ['required', 'email', 'exists:users,email'],
            'token' => ['required', 'string'],
        ]);

        $user = User::where('email', $request->email)->firstOrFail();

        if ($user->hasVerifiedEmail()) {
            return redirect()->route('login')->with('status', 'Akun Anda sudah terverifikasi. Silakan login.');
        }

        if (
            !$user->verification_token ||
            !$user->verification_token_expires_at ||
            now()->greaterThan($user->verification_token_expires_at)
        ) {
            return back()->withErrors([
                'token' => 'Token sudah kadaluarsa. Silakan kirim ulang token verifikasi.',
            ])->with('expired', true);
        }

        if ($request->token !== $user->verification_token) {
            throw ValidationException::withMessages([
                'token' => 'Token verifikasi tidak sesuai.',
            ]);
        }

        // Token benar & belum expired → verifikasi email
        $user->email_verified_at = now();
        $user->verification_token = null;
        $user->verification_token_expires_at = null;
        $user->save();

        // Login-kan user
        Auth::login($user);
        $request->session()->regenerate();

        return redirect()->route('dashboard')->with('status', 'Email berhasil diverifikasi. Selamat datang!');
    }

    /**
     * Kirim ulang token verifikasi.
     */
    public function resend(Request $request)
    {
        $request->validate([
            'email' => ['required', 'email', 'exists:users,email'],
        ]);

        $user = User::where('email', $request->email)->firstOrFail();

        if ($user->hasVerifiedEmail()) {
            return redirect()->route('login')->with('status', 'Akun Anda sudah terverifikasi. Silakan login.');
        }

        $token = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);

        $user->verification_token = $token;
        $user->verification_token_expires_at = now()->addMinutes(3);
        $user->save();

        Mail::to($user->email)->send(new VerificationTokenMail($user, $token));

        return redirect()
            ->route('verification.token.show', ['email' => $user->email])
            ->with('status', 'Token verifikasi baru telah dikirim ke email Anda.');
    }
}
