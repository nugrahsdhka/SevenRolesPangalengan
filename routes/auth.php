<?php

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\CompleteRegistrationController;
use App\Http\Controllers\Auth\TokenVerificationController;
use App\Http\Controllers\Auth\EmailVerificationNotificationController;
use App\Http\Controllers\Auth\VerifyEmailController;
use Illuminate\Support\Facades\Route;

// ====================== GUEST (BELUM LOGIN) ======================
Route::middleware('guest')->group(function () {
    // FORM LOGIN
    Route::get('/kec-pangalengan-login-access', [AuthenticatedSessionController::class, 'create'])
        ->name('login');

    // PROSES LOGIN
    Route::post('/kec-pangalengan-login-access', [AuthenticatedSessionController::class, 'store']);

    // LENGKAPI REGISTRASI (nama + password)
    Route::get('/register/complete', [CompleteRegistrationController::class, 'show'])
        ->name('register.complete.show');
    Route::post('/register/complete', [CompleteRegistrationController::class, 'store'])
        ->name('register.complete.store');

    // VERIFIKASI TOKEN
    Route::get('/verify-token', [TokenVerificationController::class, 'show'])
        ->name('verification.token.show');
    Route::post('/verify-token', [TokenVerificationController::class, 'verify'])
        ->name('verification.token.verify');
    Route::post('/verify-token/resend', [TokenVerificationController::class, 'resend'])
        ->name('verification.token.resend');
});

// ====================== SUDAH LOGIN ======================
Route::middleware('auth')->group(function () {
    // (Route default Laravel untuk email verification lewat link masih boleh dibiarkan,
    // tapi sekarang kita pakai mekanisme token di atas.)
    Route::get('/verify-email', function () {
        return view('auth.verify-email');
    })->middleware('throttle:6,1')->name('verification.notice');

    Route::get('/verify-email/{id}/{hash}', VerifyEmailController::class)
        ->middleware(['signed', 'throttle:6,1'])
        ->name('verification.verify');

    Route::post('/email/verification-notification', [EmailVerificationNotificationController::class, 'store'])
        ->middleware('throttle:6,1')
        ->name('verification.send');

    // LOGOUT
    Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])
        ->name('logout');
});
