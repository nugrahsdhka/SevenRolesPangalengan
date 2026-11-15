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
     * Halaman sama untuk semua email (tidak membocorkan status akun).
     */
    public function show(Request $request)
    {
        $request->validate(['email' => ['required', 'email']]);

        $email = (string) $request->query('email');

        $user = User::where('email', $email)->whereNull('email_verified_at')->first();

        $expired = $user
            ? (! $user->verification_token_expires_at
                || now()->greaterThan($user->verification_token_expires_at))
            : false;

        return view('auth.verify-token', [
            'email'   => $email,
            'expired' => $expired,
        ]);
    }

    /**
     * Proses verifikasi token.
     * Semua kegagalan (akun tidak ada, sudah verifikasi, token salah, kadaluarsa)
     * menghasilkan pesan yang sama.
     */
    public function verify(Request $request)
    {
        $request->validate([
            'email' => ['required', 'email'],
            'token' => ['required', 'digits:6'],
        ]);

        $user = User::where('email', $request->email)->whereNull('email_verified_at')->first();

        $valid = $user
            && $user->verification_token
            && $user->verification_token_expires_at
            && now()->lessThanOrEqualTo($user->verification_token_expires_at)
            && hash_equals($user->verification_token, User::hashVerificationToken($request->token));

        if (! $valid) {
            throw ValidationException::withMessages([
                'token' => 'Token tidak valid atau sudah kadaluarsa. Silakan periksa kembali atau kirim ulang token.',
            ]);
        }

        $user->email_verified_at = now();
        $user->verification_token = null;
        $user->verification_token_expires_at = null;
        $user->save();

        Auth::login($user);
        $request->session()->regenerate();

        return redirect()->route('dashboard')->with('status', 'Email berhasil diverifikasi. Selamat datang!');
    }

    /**
     * Kirim ulang token verifikasi.
     * Respons selalu sama; email hanya dikirim untuk akun yang belum terverifikasi.
     */
    public function resend(Request $request)
    {
        $request->validate([
            'email' => ['required', 'email'],
        ]);

        $user = User::where('email', $request->email)->whereNull('email_verified_at')->first();

        if ($user) {
            $token = str_pad((string) random_int(0, 999999), 6, '0', STR_PAD_LEFT);

            $user->verification_token = User::hashVerificationToken($token);
            $user->verification_token_expires_at = now()->addMinutes(3);
            $user->save();

            Mail::to($user->email)->send(new VerificationTokenMail($user, $token));
        }

        return redirect()
            ->route('verification.token.show', ['email' => $request->email])
            ->with('status', 'Jika email terdaftar dan belum terverifikasi, token verifikasi baru telah dikirim.');
    }
}
