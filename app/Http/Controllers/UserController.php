<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Auth\Events\Registered;
use Spatie\Permission\Models\Role;
use Illuminate\Validation\Rules;

class UserController extends Controller
{
    /**
     * Menampilkan form untuk membuat user baru.
     */
    public function create()
    {
        // Ambil semua role KECUALI 'Guest' (jika ada)
        $roles = Role::where('name', '!=', 'Guest')->pluck('name', 'name');
        return view('admin.users.create', compact('roles'));
    }

    /**
     * Menyimpan user baru dan mengirimkan email verifikasi.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:'.User::class],
            'role' => ['required', 'string', 'exists:roles,name'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()], // ✅ Tambahkan validasi password
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password), // ✅ Gunakan password dari admin
        ]);

        // Berikan role
        $user->assignRole($request->role);

        // ✅ Kirim email verifikasi
        event(new Registered($user));

        // ✅ Redirect ke halaman admin dengan pesan sukses
        return redirect()->route('admin.published.index')->with('success', 'User berhasil dibuat dan email verifikasi telah dikirim.');
    }
}