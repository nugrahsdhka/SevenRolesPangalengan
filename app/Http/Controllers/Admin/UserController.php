<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use Illuminate\Validation\Rules;
use Illuminate\Support\Str;

class UserController extends Controller
{
    /**
     * Tampilkan semua user (aktif + nonaktif/soft delete)
     */
    public function index()
    {
        $users = User::withTrashed()
            ->with('roles')
            ->orderBy('name')
            ->get();

        $roles = Role::pluck('name', 'id');

        return view('admin.users.index', compact('users', 'roles'));
    }

    public function create()
    {
        // Ambil semua role kecuali "Guest"
        $roles = Role::where('name', '!=', 'Guest')->pluck('name', 'name');

        return view('admin.users.create', compact('roles'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'  => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:' . User::class],
            'role'  => ['required', 'string', 'exists:roles,name'],
        ]);

        // Password dummy random (tidak akan dipakai, nanti user set sendiri)
        $randomPassword = Str::random(32);

        $user = User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => Hash::make($randomPassword),
        ]);

        $user->assignRole($request->role);

        return redirect()
            ->route('admin.users.index')
            ->with('success', 'User berhasil dibuat. User akan melengkapi registrasi saat pertama kali login.');
    }

    public function destroy($id)
    {
        $user = User::findOrFail($id);

        if ($user->id === auth()->id()) {
            return back()->with('error', 'Anda tidak dapat menonaktifkan akun Anda sendiri.');
        }

        $user->delete();

        return back()->with('success', 'User berhasil dinonaktifkan.');
    }

    public function restore($id)
    {
        $user = User::withTrashed()->findOrFail($id);

        $user->restore();

        return back()->with('success', 'User berhasil diaktifkan kembali.');
    }

    public function updatePassword(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $request->validate([
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $user->update([
            'password' => Hash::make($request->password),
        ]);

        return back()->with('success', 'Password untuk user ' . $user->name . ' berhasil diperbarui.');
    }
}
