<?php

namespace App\Http\Controllers\Guest;

use App\Http\Controllers\Controller;
use App\Models\Faskes; // ⬅️ IMPORT MODEL FASKES
use Illuminate\Http\Request;

class PetaController extends Controller
{
    public function index()
    {
        // Ambil data faskes dari database (hanya yang aktif)
        $faskes = Faskes::where('is_active', true)
            ->select('id', 'nama_faskes', 'jenis', 'alamat', 'desa_kelurahan', 'lat', 'lng')
            ->orderBy('nama_faskes')
            ->get();

        // Kirim ke view
        return view('public.peta.index', compact('faskes'));
    }
}
