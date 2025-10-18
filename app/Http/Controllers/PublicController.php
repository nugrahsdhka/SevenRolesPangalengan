<?php

namespace App\Http\Controllers;

use App\Models\DataGizi;
use App\Models\DataPencegahan;
use App\Models\DataPenanggulangan;
use App\Models\DataDesa;
use Illuminate\Http\Request;

class PublicController extends Controller
{
    public function home()
    {
        // LOGIKA BARU: Ambil data yang statusnya 'published'
        $publishedData = [
            'gizi' => DataGizi::where('status', 'published')->latest()->get(),
            'pencegahan' => DataPencegahan::where('status', 'published')->latest()->get(), // <-- Bagian ini yang sebelumnya error
            'penanggulangan' => DataPenanggulangan::where('status', 'published')->latest()->get(),
            'desa' => DataDesa::where('status', 'published')->latest()->get(),
        ];

        return view('public_view.home.welcome', compact('publishedData'));
    }

    public function dataStunting() 
    {
        return view('public_view.dataStunting.index');
    }

    public function dataWilayah() 
    {
        return view('public_view.dataWilayah.index');
    }

    public function analisisHotspot() 
    {
        return view('public_view.analisisHotspot.index');
    }

    public function peta() 
    {
        return view('public_view.peta.index');
    }

}