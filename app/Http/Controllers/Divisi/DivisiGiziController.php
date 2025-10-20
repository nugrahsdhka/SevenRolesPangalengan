<?php

namespace App\Http\Controllers\Divisi;

use App\Http\Controllers\Controller;
use App\Models\DataGizi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class DivisiGiziController extends Controller
{
    /**
     * Menampilkan data dari semua user dengan role yang sama.
     */
    public function index()
    {
        $currentUser = Auth::user();
        $userRoleName = $currentUser->roles->first()->name;

        $data = DataGizi::whereHas('user.roles', function ($query) use ($userRoleName) {
            $query->where('name', $userRoleName);
        })
        ->with('user')
        ->latest('periode')
        ->paginate(10);

        return view('divisi.gizi.index', compact('data'));
    }

    public function create()
    {
        return view('divisi.gizi.create');
    }

    public function store(Request $request)
    {
        $request->merge(['periode' => $request->periode . '-01']);
        $request->validate([
            'periode' => 'required|date',
            'judul' => 'required|string|max:255',
            'deskripsi' => 'required|string',
        ]);
        $data = $request->all();
        $data['user_id'] = Auth::id();
        DataGizi::create($data);
        return redirect()->route('gizi.index')->with('success', 'Data Gizi berhasil ditambahkan.');
    }

    /**
     * ✅ PERBAIKAN: Memeriksa berdasarkan Role, bukan ID User
     */
    public function edit(DataGizi $data_gizi)
    {
        $currentUserRoleName = Auth::user()->roles->first()->name;
        if (!$data_gizi->user->hasRole($currentUserRoleName)) {
            abort(403, 'AKSES DITOLAK: Anda hanya bisa mengelola data dari divisi Anda sendiri.');
        }

        return view('divisi.gizi.edit', ['data' => $data_gizi]);
    }

    /**
     * ✅ PERBAIKAN: Memeriksa berdasarkan Role, bukan ID User
     */
    public function update(Request $request, DataGizi $data_gizi)
    {
        $currentUserRoleName = Auth::user()->roles->first()->name;
        if (!$data_gizi->user->hasRole($currentUserRoleName)) {
            abort(403, 'AKSES DITOLAK: Anda hanya bisa mengelola data dari divisi Anda sendiri.');
        }
        
        $request->merge(['periode' => $request->periode . '-01']);
        $request->validate([
            'periode' => 'required|date',
            'judul' => 'required|string|max:255',
            'deskripsi' => 'required|string',
        ]);
        $data_gizi->update($request->all());
        return redirect()->route('gizi.index')->with('success', 'Data Gizi berhasil diperbarui.');
    }

    /**
     * ✅ PERBAIKAN: Memeriksa berdasarkan Role, bukan ID User
     */
    public function destroy(DataGizi $data_gizi)
    {
        $currentUserRoleName = Auth::user()->roles->first()->name;
        if (!$data_gizi->user->hasRole($currentUserRoleName)) {
            abort(403, 'AKSES DITOLAK: Anda hanya bisa mengelola data dari divisi Anda sendiri.');
        }

        $data_gizi->delete();
        return redirect()->route('gizi.index')->with('success', 'Data Gizi berhasil dihapus.');
    }
}