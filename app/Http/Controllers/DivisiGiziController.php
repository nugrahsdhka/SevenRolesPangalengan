<?php

namespace App\Http\Controllers;

use App\Models\DataGizi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class DivisiGiziController extends Controller
{
    /**
     * Menampilkan daftar data.
     */
    public function index()
    {
        $data = DataGizi::where('user_id', Auth::id())->latest('periode')->paginate(10);
        return view('divisi.gizi.index', compact('data'));
    }

    /**
     * Menampilkan form untuk membuat data baru.
     */
    public function create()
    {
        return view('divisi.gizi.create');
    }

    /**
     * Menyimpan data baru ke database.
     */
    public function store(Request $request)
    {
        // Normalisasi periode sebelum validasi
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
     * Menampilkan form untuk mengedit data.
     */
    public function edit(DataGizi $data_gizi)
    {
        // Keamanan: Pastikan user hanya bisa mengedit datanya sendiri
        if ($data_gizi->user_id !== Auth::id()) {
            abort(403, 'AKSES DITOLAK');
        }

        return view('divisi.gizi.edit', ['data' => $data_gizi]);
    }

    /**
     * Memperbarui data di database.
     */
    public function update(Request $request, DataGizi $data_gizi)
    {
        // Keamanan
        if ($data_gizi->user_id !== Auth::id()) {
            abort(403, 'AKSES DITOLAK');
        }
        
        // Normalisasi periode sebelum validasi
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
     * Menghapus data dari database.
     */
    public function destroy(DataGizi $data_gizi)
    {
        // Keamanan
        if ($data_gizi->user_id !== Auth::id()) {
            abort(403, 'AKSES DITOLAK');
        }

        $data_gizi->delete();
        return redirect()->route('gizi.index')->with('success', 'Data Gizi berhasil dihapus.');
    }
}