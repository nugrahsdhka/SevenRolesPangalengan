<?php

namespace App\Http\Controllers;

use App\Models\DataGizi;
use App\Models\DataPencegahan;
use App\Models\DataPenanggulangan;
use App\Models\DataDesa;
use Illuminate\Http\Request;

class PublicController extends Controller
{
    public function index()
    {
        // LOGIKA BARU: Ambil data yang statusnya 'published'
        $publishedData = [
            'gizi' => DataGizi::where('status', 'published')->latest()->get(),
            'pencegahan' => DataPencegahan::where('status', 'published')->latest()->get(), // <-- Bagian ini yang sebelumnya error
            'penanggulangan' => DataPenanggulangan::where('status', 'published')->latest()->get(),
            'desa' => DataDesa::where('status', 'published')->latest()->get(),
        ];

        return view('welcome', compact('publishedData'));
    }
}