<?php

namespace App\Http\Controllers\Divisi;

use App\Http\Controllers\Controller;
use App\Models\DataPencegahan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DivisiPencegahanController extends Controller
{
    public function index()
    {
        $currentUser = Auth::user();
        $userRoleName = $currentUser->roles->first()->name;
        $data = DataPencegahan::whereHas('user.roles', function ($query) use ($userRoleName) {
            $query->where('name', $userRoleName);
        })
        ->with('user')
        ->latest('periode')
        ->paginate(10);
        return view('divisi.pencegahan.index', compact('data'));
    }

    public function create()
    {
        return view('divisi.pencegahan.create');
    }

    public function store(Request $request)
    {
        $request->merge(['periode' => $request->periode . '-01']);
        $request->validate([
            'periode' => 'required|date',
            'judul_kegiatan' => 'required|string|max:255',
            'lokasi' => 'required|string|max:255',
            'tanggal_kegiatan' => 'required|date',
            'laporan_singkat' => 'required|string',
        ]);
        $data = $request->all();
        $data['user_id'] = Auth::id();
        DataPencegahan::create($data);
        return redirect()->route('pencegahan.index')->with('success', 'Data Pencegahan berhasil ditambahkan.');
    }

    /**
     * ✅ PERBAIKAN: Memeriksa berdasarkan Role, bukan ID User
     */
    public function edit(DataPencegahan $data_pencegahan)
    {
        $currentUserRoleName = Auth::user()->roles->first()->name;
        if (!$data_pencegahan->user->hasRole($currentUserRoleName)) {
            abort(403, 'AKSES DITOLAK: Anda hanya bisa mengelola data dari divisi Anda sendiri.');
        }
        return view('divisi.pencegahan.edit', ['data' => $data_pencegahan]);
    }

    /**
     * ✅ PERBAIKAN: Memeriksa berdasarkan Role, bukan ID User
     */
    public function update(Request $request, DataPencegahan $data_pencegahan)
    {
        $currentUserRoleName = Auth::user()->roles->first()->name;
        if (!$data_pencegahan->user->hasRole($currentUserRoleName)) {
            abort(403, 'AKSES DITOLAK: Anda hanya bisa mengelola data dari divisi Anda sendiri.');
        }
        $request->merge(['periode' => $request->periode . '-01']);
        $request->validate([
            'periode' => 'required|date',
            'judul_kegiatan' => 'required|string|max:255',
            'lokasi' => 'required|string|max:255',
            'tanggal_kegiatan' => 'required|date',
            'laporan_singkat' => 'required|string',
        ]);
        $data_pencegahan->update($request->all());
        return redirect()->route('pencegahan.index')->with('success', 'Data Pencegahan berhasil diperbarui.');
    }

    /**
     * ✅ PERBAIKAN: Memeriksa berdasarkan Role, bukan ID User
     */
    public function destroy(DataPencegahan $data_pencegahan)
    {
        $currentUserRoleName = Auth::user()->roles->first()->name;
        if (!$data_pencegahan->user->hasRole($currentUserRoleName)) {
            abort(403, 'AKSES DITOLAK: Anda hanya bisa mengelola data dari divisi Anda sendiri.');
        }
        $data_pencegahan->delete();
        return redirect()->route('pencegahan.index')->with('success', 'Data Pencegahan berhasil dihapus.');
    }
}