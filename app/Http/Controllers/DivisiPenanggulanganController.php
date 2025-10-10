<?php

namespace App\Http\Controllers;

use App\Models\DataPenanggulangan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DivisiPenanggulanganController extends Controller
{
    public function index()
    {
        $data = DataPenanggulangan::where('user_id', Auth::id())->latest()->paginate(10);
        return view('divisi.penanggulangan.index', compact('data'));
    }

    public function create()
    {
        return view('divisi.penanggulangan.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'jenis_bencana' => 'required|string|max:255',
            'jumlah_korban' => 'required|integer|min:0',
            'tindakan' => 'required|string',
        ]);

        $data = $request->all();
        $data['user_id'] = Auth::id();

        DataPenanggulangan::create($data);

        return redirect()->route('penanggulangan.index')->with('success', 'Data Penanggulangan berhasil ditambahkan.');
    }
}