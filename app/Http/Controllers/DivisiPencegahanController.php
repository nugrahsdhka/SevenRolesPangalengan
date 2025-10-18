<?php

namespace App\Http\Controllers;

use App\Models\DataPencegahan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DivisiPencegahanController extends Controller
{
    public function index()
    {
        $data = DataPencegahan::where('user_id', Auth::id())->latest('periode')->paginate(10);
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
        if ($data_pencegahan->user_id !== Auth::id()) {
            abort(403, 'AKSES DITOLAK');
        }
        return view('divisi.pencegahan.edit', ['data' => $data_pencegahan]);
    }

    public function update(Request $request, DataPencegahan $data_pencegahan)
    {
        if ($data_pencegahan->user_id !== Auth::id()) {
            abort(403, 'AKSES DITOLAK');
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
        if ($data_pencegahan->user_id !== Auth::id()) {
            abort(403, 'AKSES DITOLAK');
        }
        $data_pencegahan->delete();
        return redirect()->route('pencegahan.index')->with('success', 'Data Pencegahan berhasil dihapus.');
    }
}