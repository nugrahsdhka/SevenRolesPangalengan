<?php

namespace App\Http\Controllers\Divisi;

use App\Http\Controllers\Controller;
use App\Models\DataPencegahan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

// ✅ TAMBAHKAN USE STATEMENT INI
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\DataPencegahanImport;
use Illuminate\Support\Str;

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
    
    public function edit(DataPencegahan $data_pencegahan)
    {
        $currentUserRoleName = Auth::user()->roles->first()->name;
        if (!$data_pencegahan->user->hasRole($currentUserRoleName)) {
            abort(403, 'AKSES DITOLAK: Anda hanya bisa mengelola data dari divisi Anda sendiri.');
        }
        return view('divisi.pencegahan.edit', ['data' => $data_pencegahan]);
    }
    
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
    
    public function destroy(DataPencegahan $data_pencegahan)
    {
        $currentUserRoleName = Auth::user()->roles->first()->name;
        if (!$data_pencegahan->user->hasRole($currentUserRoleName)) {
            abort(403, 'AKSES DITOLAK: Anda hanya bisa mengelola data dari divisi Anda sendiri.');
        }
        $data_pencegahan->delete();
        return redirect()->route('pencegahan.index')->with('success', 'Data Pencegahan berhasil dihapus.');
    }

    // ✅ METHOD IMPORT BARU
    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls',
        ]);

        try {
            Excel::import(new DataPencegahanImport(), $request->file('file'));

            return redirect()->route('pencegahan.index')->with('success', 'Data Pencegahan berhasil di-import dari Excel.');

        } catch (\Maatwebsite\Excel\Validators\ValidationException $e) {
             $failures = $e->failures();
             $errorMessage = "Gagal import pada baris " . $failures[0]->row() . ". "
                           . "Error: " . $failures[0]->errors()[0]
                           . " (Nilai: '" . ($failures[0]->values()[$failures[0]->attribute()] ?? '') . "')";
             return redirect()->route('pencegahan.index')->with('error', $errorMessage);
        } catch (\Exception $e) {
            return redirect()->route('pencegahan.index')->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
}