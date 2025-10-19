<?php

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\EmailVerificationNotificationController;
use App\Http\Controllers\Auth\EmailVerificationPromptController;
use App\Http\Controllers\Auth\VerifyEmailController;
use Illuminate\Support\Facades\Route;

Route::middleware('guest')->group(function () {
    // Rute untuk menampilkan halaman login
    Route::get('kec-pangalengan-login-access', [AuthenticatedSessionController::class, 'create'])
        ->name('login');

    // Rute untuk memproses data login
    Route::post('kec-pangalengan-login-access', [AuthenticatedSessionController::class, 'store']);
});

Route::middleware('auth')->group(function () {
    // Rute untuk menampilkan halaman "verifikasi email Anda"
    Route::get('verify-email', EmailVerificationPromptController::class)
        ->name('verification.notice');

    // Rute yang menangani link verifikasi yang diklik di email
    Route::get('verify-email/{id}/{hash}', VerifyEmailController::class)
        ->middleware(['signed', 'throttle:6,1'])
        ->name('verification.verify');

    // Rute untuk mengirim ulang email verifikasi
    Route::post('email/verification-notification', [EmailVerificationNotificationController::class, 'store'])
        ->middleware('throttle:6,1')
        ->name('verification.send');

    // Rute untuk logout
    Route::post('logout', [AuthenticatedSessionController::class, 'destroy'])
        ->name('logout');
});