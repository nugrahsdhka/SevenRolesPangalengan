<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\CamatController;
use App\Http\Controllers\PublicController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DivisiDesaController;
use App\Http\Controllers\DivisiGiziController;
use App\Http\Controllers\DivisiPencegahanController;
use App\Http\Controllers\DivisiPenanggulanganController;

// Guest Route
Route::get('/', [PublicController::class, 'home'])->name('welcome');
Route::get('/data-stunting', [PublicController::class, 'dataStunting'])->name('data-stunting');
Route::get('/data-wilayah', [PublicController::class, 'dataWilayah'])->name('data-wilayah');
Route::get('/analisis-hotspot', [PublicController::class, 'analisisHotspot'])->name('analisis-hotspot');
Route::get('/peta-faskes', [PublicController::class, 'peta'])->name('peta-faskes');

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
Route::middleware('auth')->group(function () {
    Route::get('/dashboard-view', fn() => view('dashboard'))->name('dashboard.view');
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Divisi Gizi
    Route::middleware(['role:Divisi Gizi'])->prefix('divisi-gizi')->name('gizi.')->group(function () {
        Route::resource('/', DivisiGiziController::class)->parameters(['' => 'data_gizi']);
    });

    // ✅ PERBAIKAN: Gunakan Route::resource untuk Divisi Pencegahan
    Route::middleware(['role:Divisi Pencegahan'])->prefix('divisi-pencegahan')->name('pencegahan.')->group(function () {
        Route::resource('/', DivisiPencegahanController::class)->parameters(['' => 'data_pencegahan']);
    });

    // ✅ PERBAIKAN: Gunakan Route::resource untuk Divisi Penanggulangan
    Route::middleware(['role:Divisi Penanggulangan'])->prefix('divisi-penanggulangan')->name('penanggulangan.')->group(function () {
        Route::resource('/', DivisiPenanggulanganController::class)->parameters(['' => 'data_penanggulangan']);
    });
    
    // Divisi Desa
    Route::middleware(['role:Divisi Desa'])->prefix('divisi-desa')->name('desa.')->group(function () {
        Route::resource('/', DivisiDesaController::class)->parameters(['' => 'data_desa']);
    });

    // Camat
    Route::middleware(['role:Camat'])->prefix('camat')->name('camat.')->group(function () {
        Route::get('/review', [CamatController::class, 'index'])->name('review');
        Route::post('/status', [CamatController::class, 'updateStatus'])->name('status');
        Route::delete('/delete', [CamatController::class, 'destroy'])->name('destroy');
    });

    // IT Admin
    Route::middleware(['role:IT Admin'])->prefix('admin')->name('admin.')->group(function () {
        Route::get('/published-data', [AdminController::class, 'publishedData'])->name('published.index');
        Route::post('/publish-data', [AdminController::class, 'publish'])->name('publish');
    });
});

require __DIR__.'/auth.php';