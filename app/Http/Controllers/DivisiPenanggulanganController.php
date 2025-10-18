<?php

namespace App\Http\Controllers;

use App\Models\DataPenanggulangan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DivisiPenanggulanganController extends Controller
{
    public function index()
    {
        $data = DataPenanggulangan::where('user_id', Auth::id())->latest('periode')->paginate(10);
        return view('divisi.penanggulangan.index', compact('data'));
    }

    public function create()
    {
        return view('divisi.penanggulangan.create');
    }

    public function store(Request $request)
    {
        $request->merge(['periode' => $request->periode . '-01']);
        $request->validate([
            'periode' => 'required|date',
            'jenis_bencana' => 'required|string|max:255',
            'jumlah_korban' => 'required|integer|min:0',
            'tindakan' => 'required|string',
        ]);
        $data = $request->all();
        $data['user_id'] = Auth::id();
        DataPenanggulangan::create($data);
        return redirect()->route('penanggulangan.index')->with('success', 'Data Penanggulangan berhasil ditambahkan.');
    }

    public function edit(DataPenanggulangan $data_penanggulangan)
    {
        if ($data_penanggulangan->user_id !== Auth::id()) {
            abort(403, 'AKSES DITOLAK');
        }
        return view('divisi.penanggulangan.edit', ['data' => $data_penanggulangan]);
    }

    public function update(Request $request, DataPenanggulangan $data_penanggulangan)
    {
        if ($data_penanggulangan->user_id !== Auth::id()) {
            abort(403, 'AKSES DITOLAK');
        }
        $request->merge(['periode' => $request->periode . '-01']);
        $request->validate([
            'periode' => 'required|date',
            'jenis_bencana' => 'required|string|max:255',
            'jumlah_korban' => 'required|integer|min:0',
            'tindakan' => 'required|string',
        ]);
        $data_penanggulangan->update($request->all());
        return redirect()->route('penanggulangan.index')->with('success', 'Data Penanggulangan berhasil diperbarui.');
    }

    public function destroy(DataPenanggulangan $data_penanggulangan)
    {
        if ($data_penanggulangan->user_id !== Auth::id()) {
            abort(403, 'AKSES DITOLAK');
        }
        $data_penanggulangan->delete();
        return redirect()->route('penanggulangan.index')->with('success', 'Data Penanggulangan berhasil dihapus.');
    }
}