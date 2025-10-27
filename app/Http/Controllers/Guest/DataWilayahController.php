<?php

namespace App\Http\Controllers\Guest; // <--- NAMESPACE DIPERBARUI

use App\Http\Controllers\Controller; // <--- DITAMBAHKAN
use Illuminate\Http\Request;
use App\Models\DataGizi; 
use Illuminate\Support\Facades\DB; 

class DataWilayahController extends Controller // <-- EXTENDS DIPERBARUI
{
    public function index(Request $request)
    {
        $perPage = 15;

        // Query ini mirip dengan dataStunting, karena datanya sama
        $dataWilayah = DataGizi::query()
            ->select(
                'desa_kelurahan',
                DB::raw('COUNT(*) as total_anak'),
                DB::raw('SUM(CASE WHEN zs_tb_u < -2 THEN 1 ELSE 0 END) as total_kasus_stunting'),
                DB::raw('SUM(zs_tb_u) as sum_zs_tbu')
            )
            ->where('status', 'published') // <-- Filter utama untuk publik
            ->groupBy('desa_kelurahan')
            ->orderBy('desa_kelurahan', 'asc')
            ->paginate($perPage);

        // Data ini dikirim ke view 'public_view.dataWilayah.index'
        return view('public_view.dataWilayah.index', compact('dataWilayah'));
    }
}
