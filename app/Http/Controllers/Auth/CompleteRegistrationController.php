<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Mail\VerificationTokenMail;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\Rules;

class CompleteRegistrationController extends Controller
{
    /**
     * Tampilkan form lengkapi registrasi.
     *
     * Form ditampilkan sama untuk email apa pun (terdaftar atau tidak, sudah
     * diverifikasi atau belum) dan TIDAK menampilkan nama/role akun, supaya
     * halaman ini tidak bisa dipakai untuk menebak data pengguna.
     */
    public function show(Request $request)
    {
        $request->validate(['email' => ['required', 'email']]);

        return view('auth.complete-registration', [
            'email' => (string) $request->query('email'),
        ]);
    }

    /**
     * Proses simpan nama & password, lalu kirim token.
     *
     * Respons selalu sama, apa pun status emailnya. Token hanya dikirim bila
     * akun ada dan belum terverifikasi.
     */
    public function store(Request $request)
    {
        $request->validate([
            'email'    => ['required', 'email'],
            'name'     => ['required', 'string', 'max:255'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $user = User::where('email', $request->email)
            ->whereNull('email_verified_at')
            ->first();

        if ($user) {
            $token = str_pad((string) random_int(0, 999999), 6, '0', STR_PAD_LEFT);

            $user->name = $request->name;
            $user->password = Hash::make($request->password);
            $user->verification_token = User::hashVerificationToken($token);
            $user->verification_token_expires_at = now()->addMinutes(3);
            $user->save();

            Mail::to($user->email)->send(new VerificationTokenMail($user, $token));
        }

        return redirect()
            ->route('verification.token.show', ['email' => $request->email])
            ->with('status', 'Jika email terdaftar dan belum terverifikasi, token verifikasi telah dikirim ke email tersebut.');
    }
}
