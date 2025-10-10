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

// Guest Route (Halaman Publik)
Route::get('/', [PublicController::class, 'index'])->name('welcome');

// Dashboard umum setelah login
Route::get('/dashboard', function () {
    /** @var \App\Models\User $user */
    $user = auth()->user();

    // Menggunakan "match" untuk mengarahkan user berdasarkan role setelah login
    return redirect()->route(match (true) {
        // ✅ PERBAIKAN: Tambahkan kondisi untuk IT Admin di sini
        $user->hasRole('IT Admin') => 'admin.published.index',

        $user->hasRole('Camat') => 'camat.review',
        $user->hasRole('Divisi Gizi') => 'gizi.index',
        $user->hasRole('Divisi Pencegahan') => 'pencegahan.index',
        $user->hasRole('Divisi Penanggulangan') => 'penanggulangan.index',
        $user->hasRole('Divisi Desa') => 'desa.index',
        
        // Arahkan ke dashboard biasa jika tidak ada role yang cocok
        default => 'dashboard.view'
    });
})->middleware(['auth', 'verified'])->name('dashboard');

// Grup Rute yang memerlukan login
Route::middleware('auth')->group(function () {
    // Rute untuk menampilkan view dashboard biasa (untuk kasus default)
    Route::get('/dashboard-view', fn() => view('dashboard'))->name('dashboard.view');
    
    // Rute Profile
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Grup untuk Divisi Gizi
    Route::middleware(['role:Divisi Gizi'])->prefix('divisi-gizi')->name('gizi.')->group(function () {
        Route::get('/', [DivisiGiziController::class, 'index'])->name('index');
        Route::get('/create', [DivisiGiziController::class, 'create'])->name('create');
        Route::post('/', [DivisiGiziController::class, 'store'])->name('store');
    });

    // Grup untuk Divisi Pencegahan
    Route::middleware(['role:Divisi Pencegahan'])->prefix('divisi-pencegahan')->name('pencegahan.')->group(function () {
        Route::get('/', [DivisiPencegahanController::class, 'index'])->name('index');
        Route::get('/create', [DivisiPencegahanController::class, 'create'])->name('create');
        Route::post('/', [DivisiPencegahanController::class, 'store'])->name('store');
    });

    // Grup untuk Divisi Penanggulangan
    Route::middleware(['role:Divisi Penanggulangan'])->prefix('divisi-penanggulangan')->name('penanggulangan.')->group(function () {
        Route::get('/', [DivisiPenanggulanganController::class, 'index'])->name('index');
        Route::get('/create', [DivisiPenanggulanganController::class, 'create'])->name('create');
        Route::post('/', [DivisiPenanggulanganController::class, 'store'])->name('store');
    });
    
    // Grup untuk Divisi Desa
    Route::middleware(['role:Divisi Desa'])->prefix('divisi-desa')->name('desa.')->group(function () {
        Route::get('/', [DivisiDesaController::class, 'index'])->name('index');
        Route::get('/create', [DivisiDesaController::class, 'create'])->name('create');
        Route::post('/', [DivisiDesaController::class, 'store'])->name('store');
    });

    // Grup untuk Camat
    Route::middleware(['role:Camat'])->prefix('camat')->name('camat.')->group(function () {
        Route::get('/review', [CamatController::class, 'index'])->name('review');
        // UBAH route 'approve' menjadi 'status' yang lebih fleksibel
        Route::post('/status', [CamatController::class, 'updateStatus'])->name('status');
        // TAMBAHKAN route baru untuk menghapus data
        Route::delete('/delete', [CamatController::class, 'destroy'])->name('destroy');
    });

    // Grup untuk IT Admin
    Route::middleware(['role:IT Admin'])->prefix('admin')->name('admin.')->group(function () {
        Route::get('/published-data', [AdminController::class, 'publishedData'])->name('published.index');
        // 👇 TAMBAHKAN RUTE INI UNTUK AKSI PUBLISH/PULL 👇
        Route::post('/publish-data', [AdminController::class, 'publish'])->name('publish');
    });
});

require __DIR__.'/auth.php';