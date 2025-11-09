<?php

namespace App\Http\Controllers\Guest;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class PetaController extends Controller
{
    public function index()
    {
        // File dummy faskes di public/maps/faskes_pangalengan.json
        $path = public_path('maps/faskes_pangalengan.json');

        if (file_exists($path)) {
            $json = file_get_contents($path);
        } else {
            $json = '[]';
        }

        $faskesJson = $json;

        return view('public_view.peta.index', compact('faskesJson'));
    }
}
