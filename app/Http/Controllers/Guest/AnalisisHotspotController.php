<?php

namespace App\Http\Controllers\Guest;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AnalisisHotspotController extends Controller
{
    public function index() {
        return view('public_view.analisisHotspot.index');
    }
}
