<?php

namespace App\Http\Controllers\Guest;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DataWilayahController extends Controller
{
    public function index() {
        return view('public_view.dataWilayah.index');
    }
}
