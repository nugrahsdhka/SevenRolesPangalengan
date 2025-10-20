<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Auth\Events\Registered;
use Spatie\Permission\Models\Role;
use Illuminate\Validation\Rules;

class UserController extends Controller
{
    /**
     * ✅ PERBAIKAN: Tampilkan user yang aktif maupun yang nonaktif (trashed)
     */
    public function index()
    {
        $roles = Role::with(['users' => function ($query) {
            $query->withTrashed(); // Ambil juga user yang sudah di-soft-delete
        }])->get();
        
        return view('admin.users.index', compact('roles'));
    }

    public function create()
    {
        $roles = Role::where('name', '!=', 'Guest')->pluck('name', 'name');
        return view('admin.users.create', compact('roles'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:'.User::class],
            'role' => ['required', 'string', 'exists:roles,name'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        $user->assignRole($request->role);
        event(new Registered($user));

        return redirect()->route('admin.users.index')->with('success', 'User berhasil dibuat dan email verifikasi telah dikirim.');
    }

    /**
     * ✅ PERBAIKAN: Gunakan $id dan cari user secara manual
     */
    public function destroy($id)
    {
        $user = User::findOrFail($id); // Temukan user yang masih aktif
        if ($user->id === auth()->id()) {
            return back()->with('error', 'Anda tidak dapat menonaktifkan akun Anda sendiri.');
        }
        
        $user->delete(); // Ini akan menjalankan Soft Delete
        return back()->with('success', 'User berhasil dinonaktifkan.');
    }

    /**
     * ✅ METHOD BARU: Mengaktifkan (restore) user
     */
    public function restore($id)
    {
        // Temukan user di "sampah" (trashed)
        $user = User::withTrashed()->findOrFail($id);
        $user->restore(); // Perintah untuk mengaktifkan kembali

        return back()->with('success', 'User berhasil diaktifkan kembali.');
    }

    /**
     * ✅ PERBAIKAN: Gunakan $id dan cari user secara manual
     */
    public function updatePassword(Request $request, $id)
    {
        $user = User::findOrFail($id); // Temukan user yang masih aktif
        $request->validate([
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);
        
        $user->update([
            'password' => Hash::make($request->password)
        ]);

        return back()->with('success', 'Password untuk user ' . $user->name . ' berhasil diperbarui.');
    }
}