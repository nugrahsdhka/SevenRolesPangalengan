<?php

namespace App\Http\Controllers;

use App\Models\DataDesa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DivisiDesaController extends Controller
{
    public function index()
    {
        $data = DataDesa::where('user_id', Auth::id())->latest()->paginate(10);
        return view('divisi.desa.index', compact('data'));
    }

    public function create()
    {
        return view('divisi.desa.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'jumlah_penduduk' => 'required|integer|min:0',
            'luas_wilayah' => 'required|integer|min:0',
            'potensi_desa' => 'required|string',
        ]);

        $data = $request->all();
        $data['user_id'] = Auth::id();

        DataDesa::create($data);

        return redirect()->route('desa.index')->with('success', 'Data Desa berhasil ditambahkan.');
    }
}