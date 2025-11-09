<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Mail\VerificationTokenMail;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\Rules;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Str;

class CompleteRegistrationController extends Controller
{
    /**
     * Tampilkan form lengkapi registrasi.
     */
    public function show(Request $request)
    {
        $email = $request->query('email');

        if (!$email) {
            return redirect()->route('login')->withErrors([
                'email' => 'Email tidak ditemukan. Silakan login kembali.',
            ]);
        }

        $user = User::where('email', $email)
            ->with('roles') // ⬅️ load roles juga
            ->first();

        if (!$user) {
            return redirect()->route('login')->withErrors([
                'email' => 'Akun tidak ditemukan.',
            ]);
        }

        if ($user->hasVerifiedEmail()) {
            return redirect()->route('login')->with('status', 'Akun Anda sudah terverifikasi. Silakan login.');
        }

        return view('auth.complete-registration', compact('user'));
    }


    /**
     * Proses simpan nama & password, lalu kirim token.
     */
    public function store(Request $request)
    {
        $request->validate([
            'email'                 => ['required', 'email', 'exists:users,email'],
            'name'                  => ['required', 'string', 'max:255'],
            'password'              => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $user = User::where('email', $request->email)->firstOrFail();

        if ($user->hasVerifiedEmail()) {
            return redirect()->route('login')->with('status', 'Akun Anda sudah terverifikasi. Silakan login.');
        }

        // Update nama & password
        $user->name = $request->name;
        $user->password = Hash::make($request->password);

        // Generate token 6 digit
        $token = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);

        $user->verification_token = $token;
        $user->verification_token_expires_at = now()->addMinutes(3);
        $user->save();

        // Kirim email berisi token
        Mail::to($user->email)->send(new VerificationTokenMail($user, $token));

        return redirect()
            ->route('verification.token.show', ['email' => $user->email])
            ->with('status', 'Token verifikasi telah dikirim ke email Anda.');
    }
}
