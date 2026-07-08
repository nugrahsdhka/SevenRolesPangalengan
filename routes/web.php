<?php

use Illuminate\Support\Facades\Route;

// Auth
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Camat\CamatController;
use App\Http\Controllers\Divisi\DivisiDesaController;
use App\Http\Controllers\Divisi\DivisiGiziController;
use App\Http\Controllers\Divisi\DivisiPencegahanController;
use App\Http\Controllers\Divisi\DivisiPenanggulanganController;
use App\Http\Controllers\Profile\ProfileController;
use App\Http\Controllers\Admin\FaskesController;

// Guest
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\Guest\HomeController;
use App\Http\Controllers\Guest\PetaController;
use App\Http\Controllers\Guest\DataWilayahController;
use App\Http\Controllers\Guest\DataStuntingController;
use App\Http\Controllers\Guest\AnalisisHotspotController;
use App\Http\Controllers\Auth\EmailVerificationEntryController;

// Guest Route
// Home
Route::get('/', [HomeController::class, 'index'])->name('index');

// Data Stunting
Route::get('/data-stunting', [DataStuntingController::class, 'index'])->name('data-stunting');
Route::get('/data-stunting/chart-zs', [DataStuntingController::class, 'getChartDataZs'])
    ->name('data-stunting.chart-zs');

// Data Wilayah
Route::get('/data-wilayah', [DataWilayahController::class, 'index'])->name('data-wilayah');
Route::get('/data-wilayah/detail', [DataWilayahController::class, 'detailGizi'])->name('data-wilayah-detail-gizi');

// Analisis Hotspot
Route::get('/analisis-hotspot', [AnalisisHotspotController::class, 'index'])->name('analisis-hotspot');
Route::get('/analisis-hotspot/chart-data', [AnalisisHotspotController::class, 'getChartDataHotspot'])->name('analisis-hotspot.chart-data');
Route::get('/api/hotspot', [AnalisisHotspotController::class, 'getChartDataHotspot'])->name('api.hotspot');

// Peta
Route::get('/peta-faskes', [PetaController::class, 'index'])->name('peta-faskes');

// Forgot / Reset Password
Route::middleware('guest')->group(function () {
    // Form minta link reset
    Route::get('/forgot-password', [ForgotPasswordController::class, 'create'])
        ->name('password.request');

    // Kirim email link reset
    Route::post('/forgot-password', [ForgotPasswordController::class, 'store'])
        ->name('password.email');

    // Form reset password (dari link email)
    Route::get('/reset-password/{token}', [ResetPasswordController::class, 'create'])
        ->name('password.reset');

    // Proses reset password
    Route::post('/reset-password', [ResetPasswordController::class, 'store'])
        ->name('password.store');

    Route::get('/verify-email-entry', [EmailVerificationEntryController::class, 'create'])
        ->name('verification.entry.show');

    Route::post('/verify-email-entry', [EmailVerificationEntryController::class, 'store'])
        ->name('verification.entry.store');
});


// Dashboard Redirect Logic
Route::get('/dashboard', function () {
    $user = auth()->user();
    return redirect()->route(match (true) {
        $user->hasRole('IT Admin') => 'admin.published.index',
        $user->hasRole('Camat') => 'camat.review',
        $user->hasRole('Divisi Gizi') => 'gizi.index',
        $user->hasRole('Divisi Pencegahan') => 'pencegahan.index',
        $user->hasRole('Divisi Penanggulangan') => 'penanggulangan.index',
        $user->hasRole('Divisi Desa') => 'desa.index',
        default => 'dashboard.view'
    });
})->middleware(['auth', 'verified'])->name('dashboard');

// Authenticated Routes
Route::middleware('auth', 'verified')->group(function () {
    // Divisi Gizi
    Route::middleware(['role:Divisi Gizi'])->prefix('divisi-gizi')->name('gizi.')->group(function () {
        Route::post('/aggregated-delete', [DivisiGiziController::class, 'destroyAggregated'])->name('destroyAggregated');

        Route::get('/gizi/desa/{desa}', [DivisiGiziController::class, 'detail'])->name('detail');
        Route::post('/import', [DivisiGiziController::class, 'import'])->name('import');

        Route::get('/gizi/import/{job}/progress', [DivisiGiziController::class, 'importProgress'])
            ->name('gizi.import.progress');

        Route::get('/gizi/import-status/{job}', [DivisiGiziController::class, 'importStatus'])
            ->name('gizi.import.status');
            
        Route::delete('/divisi/gizi/aggregat', [DivisiGiziController::class, 'destroyAggregat'])
            ->name('gizi.destroyAggregat');

        Route::get('export', [App\Http\Controllers\Divisi\DivisiGiziController::class, 'export'])
            ->name('export');

        Route::resource('/', DivisiGiziController::class)->parameters(['' => 'data_gizi']);
    });

    // Divisi Pencegahan
    Route::middleware(['role:Divisi Pencegahan'])->prefix('divisi-pencegahan')->name('pencegahan.')->group(function () {
        Route::resource('/', DivisiPencegahanController::class)->parameters(['' => 'data_pencegahan']);

        Route::post('/import', [DivisiPencegahanController::class, 'import'])->name('import');
    });

    // Divisi Penanggulangan
    Route::middleware(['role:Divisi Penanggulangan'])->prefix('divisi-penanggulangan')->name('penanggulangan.')->group(function () {
        Route::resource('/', DivisiPenanggulanganController::class)->parameters(['' => 'data_penanggulangan']);
        
        Route::post('/import', [DivisiPenanggulanganController::class, 'import'])->name('import');
    });
    
    // Divisi Desa
    Route::middleware(['role:Divisi Desa'])->prefix('divisi-desa')->name('desa.')->group(function () {
        Route::resource('/', DivisiDesaController::class)->parameters(['' => 'data_desa']);
        
        Route::post('/import', [DivisiDesaController::class, 'import'])->name('import');
    });

    // Camat
    Route::middleware(['role:Camat'])->prefix('camat')->name('camat.')->group(function () {
        Route::get('/review', [CamatController::class, 'index'])->name('review');
        
        // TAMBAHKAN ROUTE INI
        Route::get('/review/gizi/{desa_kelurahan}', [CamatController::class, 'showGiziDetail'])
            ->name('gizi.detail');

        Route::post('/status', [CamatController::class, 'updateStatus'])->name('status');
        Route::delete('/delete', [CamatController::class, 'destroy'])->name('destroy');
        Route::post('/bulk-status', [CamatController::class, 'bulkUpdateStatus'])->name('bulk_status');
    });

    // IT Admin
    Route::middleware(['role:IT Admin'])->prefix('admin')->name('admin.')->group(function () {
        Route::get('/published-data', [AdminController::class, 'publishedData'])->name('published.index');
        Route::post('/publish-data', [AdminController::class, 'publish'])->name('publish');

        Route::get('/published-data/gizi/{desa_kelurahan}', [AdminController::class, 'showGiziDetail'])->name('gizi.detail');

        Route::patch('faskes/{faskes}/toggle-active', [FaskesController::class, 'toggleActive'])
            ->name('faskes.toggle-active');

        Route::resource('faskes', FaskesController::class)
            ->parameters(['faskes' => 'faskes'])
            ->names('faskes');

        Route::get('/users/create', [UserController::class, 'create'])->name('users.create');
        Route::post('/users', [UserController::class, 'store'])->name('users.store');
        Route::get('/users', [UserController::class, 'index'])->name('users.index');

        Route::delete('/users/{id}', [UserController::class, 'destroy'])->name('users.destroy');
        Route::patch('/users/{id}/password', [UserController::class, 'updatePassword'])->name('users.password.update');
        Route::patch('/users/{id}/restore', [UserController::class, 'restore'])->name('users.restore'); // ✅ RUTE BARU

        Route::post('/bulk-publish', [AdminController::class, 'bulkPublish'])->name('bulk_publish');
        Route::delete('/delete', [AdminController::class, 'destroy'])->name('destroy');
    });

    Route::get('/profile', [ProfileController::class, 'index'])->name('profile.index'); // Halaman Tampilan
    Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit'); // Halaman Edit Form
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
});

Route::get('/mem-test', function () {
    return ini_get('memory_limit');
});


require __DIR__.'/auth.php';