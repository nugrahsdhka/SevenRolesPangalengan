<?php

namespace App\Http\Controllers\Profile;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Illuminate\Validation\Rule;
use App\Models\User;

class ProfileController extends Controller
{
    /**
     * Menampilkan halaman index profil (read-only).
     */
    public function index(Request $request)
    {
        $user = $request->user();
        $roleName = $user->roles->isNotEmpty() ? $user->roles->first()->name : 'Tidak ada role';
        $dashboardRoute = match (true) {
            $user->hasRole('IT Admin') => 'admin.published.index',
            $user->hasRole('Camat') => 'camat.review',
            $user->hasRole('Divisi Gizi') => 'gizi.index',
            $user->hasRole('Divisi Pencegahan') => 'pencegahan.index',
            $user->hasRole('Divisi Penanggulangan') => 'penanggulangan.index',
            $user->hasRole('Divisi Desa') => 'desa.index',
            default => 'dashboard'
        };

        return view('profile.index', [
            'user' => $user,
            'roleName' => $roleName,
            'dashboardRoute' => $dashboardRoute,
        ]);
    }

    /**
     * Menampilkan halaman edit profil (form).
     */
    public function edit(Request $request)
    {
        $user = $request->user();
        $roleName = $user->roles->isNotEmpty() ? $user->roles->first()->name : 'Tidak ada role';

        return view('profile.edit', [
            'user' => $user,
            'roleName' => $roleName,
        ]);
    }

    /**
     * ✅ METHOD BARU: Mengupdate informasi profil (nama) dan password (opsional).
     */
    public function update(Request $request)
    {
        $user = $request->user();
        $updateMessages = []; // Untuk melacak apa yang diupdate

        // --- Validasi dan Update Nama ---
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
        ]);

        // Cek apakah nama berubah sebelum menyimpan
        if ($user->name !== $request->name) {
            $user->name = $request->name;
            $updateMessages[] = 'Nama berhasil diperbarui.';
        }

        // --- Validasi dan Update Password (Hanya jika diisi) ---
        if ($request->filled('current_password') || $request->filled('new_password')) {
            $request->validateWithBag('updatePassword', [
                // Password saat ini wajib diisi JIKA password baru diisi
                'current_password' => ['required', 'string', 'current_password'],
                // Password baru wajib diisi JIKA password saat ini diisi, harus dikonfirmasi
                'new_password' => ['required_with:current_password', 'nullable', 'string', Password::defaults(), 'confirmed'],
            ]);

            // Jika validasi password lolos dan password baru ada isinya
            if ($request->filled('new_password')) {
                $user->password = Hash::make($request->new_password);
                $updateMessages[] = 'Password berhasil diperbarui.';
            }
        }

        // Simpan perubahan jika ada
        if ($user->isDirty()) { // isDirty() mengecek apakah ada field yang berubah
            $user->save();
        }

        // Tentukan pesan feedback
        if (empty($updateMessages)) {
            $statusMessage = 'Tidak ada perubahan yang disimpan.';
            $statusType = 'no-changes'; // Tipe status baru untuk notifikasi
        } else {
            $statusMessage = implode(' ', $updateMessages);
            $statusType = 'profile-updated'; // Tipe status umum
        }

        return redirect()->route('profile.edit')->with('status', $statusType)->with('statusMessage', $statusMessage);
    }

    // Hapus method updateInformation() dan updatePassword() yang lama
    // public function updateInformation(Request $request) { ... }
    // public function updatePassword(Request $request) { ... }
}