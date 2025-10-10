<?php

namespace App\Http\Controllers;

use App\Models\DataPencegahan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DivisiPencegahanController extends Controller
{
    public function index()
    {
        $data = DataPencegahan::where('user_id', Auth::id())->latest()->paginate(10);
        return view('divisi.pencegahan.index', compact('data'));
    }

    public function create()
    {
        return view('divisi.pencegahan.create');
    }

    public function store(Request $request)
    {
        $request->validate([
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
}