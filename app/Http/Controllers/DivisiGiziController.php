<?php
namespace App\Http\Controllers;
use App\Models\DataGizi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DivisiGiziController extends Controller {
    public function index() {
        $data = DataGizi::where('user_id', Auth::id())->latest()->paginate(10);
        return view('divisi.gizi.index', compact('data'));
    }
    
    public function create() { 
        return view('divisi.gizi.create'); 
    }

    public function store(Request $request) {
        $request->validate([
            'periode' => 'required|date', 
            'judul' => 'required|string|max:255', 
            'deskripsi' => 'required|string'
        ]);

        $data = $request->all();
        $data['user_id'] = Auth::id();
        DataGizi::create($data);
        return redirect()->route('gizi.index')->with('success', 'Data Gizi berhasil ditambahkan.');
    }
}