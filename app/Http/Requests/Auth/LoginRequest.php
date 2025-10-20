<?php

namespace App\Http\Requests\Auth;

use Illuminate\Auth\Events\Lockout;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use App\Models\User; // ✅ 1. Tambahkan model User
use Illuminate\Support\Facades\Hash; // ✅ 2. Tambahkan Hash

class LoginRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\Rule|array|string>
     */
    public function rules(): array
    {
        return [
            'email' => ['required', 'string', 'email'],
            'password' => ['required', 'string'],
        ];
    }

    /**
     * Attempt to authenticate the request's credentials.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function authenticate(): void
    {
        $this->ensureIsNotRateLimited();

        // ✅ --- PERBAIKAN LOGIKA DIMULAI DI SINI ---
        
        // 1. Cari user berdasarkan email, TERMASUK yang sudah nonaktif (soft-deleted)
        $user = User::withTrashed()->where('email', $this->input('email'))->first();

        // 2. Cek jika user tidak ada ATAU password yang dimasukkan salah
        if (! $user || ! Hash::check($this->input('password'), $user->password)) {
            RateLimiter::hit($this->throttleKey());

            throw ValidationException::withMessages([
                'email' => trans('auth.failed'), // Ini adalah pesan "These credentials do not match..."
            ]);
        }

        // 3. Cek jika user ada, password benar, TAPI akunnya dinonaktifkan
        if ($user->trashed()) {
            RateLimiter::hit($this->throttleKey());

            throw ValidationException::withMessages([
                'email' => 'Akun ini telah dinonaktifkan. Silakan hubungi administrator.', // ✅ Pesan kustom Anda
            ]);
        }

        // 4. Jika lolos semua, loginkan user
        Auth::login($user, $this->boolean('remember'));
        
        // --- PERBAIKAN LOGIKA SELESAI ---

        RateLimiter::clear($this->throttleKey());
    }

    /**
     * Ensure the login request is not rate limited.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function ensureIsNotRateLimited(): void
    {
        if (! RateLimiter::tooManyAttempts($this->throttleKey(), 5)) {
            return;
        }

        event(new Lockout($this));

        $seconds = RateLimiter::availableIn($this->throttleKey());

        throw ValidationException::withMessages([
            'email' => trans('auth.throttle', [
                'seconds' => $seconds,
                'minutes' => ceil($seconds / 60),
            ]),
        ]);
    }

    /**
     * Get the rate limiting throttle key for the request.
     */
    public function throttleKey(): string
    {
        return Str::transliterate(Str::lower($this->input('email')).'|'.$this->ip());
    }
}