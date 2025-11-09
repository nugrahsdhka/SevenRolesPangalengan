<?php

namespace App\Http\Controllers\Guest;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\DataGizi;

class AnalisisHotspotController extends Controller
{
    public function index()
    {
        return view('public_view.analisisHotspot.index');
    }

    /**
     * API untuk data hotspot peta.
     * Logika perhitungan SAMA dengan DataStunting (pakai avg_zs_tbu).
     */
    public function getChartDataHotspot(Request $request)
    {
        $rows = DataGizi::query()
            ->select(
                'desa_kelurahan',
                DB::raw('COUNT(*) as total_anak'),
                DB::raw('SUM(CASE WHEN zs_tb_u < -2 THEN 1 ELSE 0 END) as total_kasus_stunting'),
                DB::raw('MAX(created_at) as periode'),
                DB::raw('SUM(zs_tb_u) / COUNT(*) as avg_zs_tbu')
            )
            ->where('status', 'published')
            ->groupBy('desa_kelurahan')
            ->having(DB::raw('COUNT(*)'), '>', 0)
            ->havingNotNull('avg_zs_tbu')
            ->orderBy('desa_kelurahan', 'asc')
            ->get();

        $data = [];

        foreach ($rows as $r) {
            $avg = (float) $r->avg_zs_tbu;

            // Kategori SAMA persis dengan halaman Data Stunting
            if (is_null($avg) || $r->total_anak == 0) {
                $kategori = 'N/A';
            } elseif ($avg < -3) {
                $kategori = 'Sangat Pendek';
            } elseif ($avg < -2) {
                $kategori = 'Pendek';
            } elseif ($avg <= 2) {
                $kategori = 'Normal';
            } else {
                $kategori = 'Tinggi';
            }

            // 🔑 KEY dibikin UPPERCASE supaya gampang dicocokkan dengan GeoJSON
            $key = strtoupper(trim($r->desa_kelurahan));

            $data[$key] = [
                'avg_zs_tbu'           => round($avg, 2),
                'kategori'             => $kategori,
                'total_anak'           => (int) $r->total_anak,
                'total_kasus_stunting' => (int) $r->total_kasus_stunting,
                'periode'              => $r->periode,
            ];
        }

        return response()->json($data);
    }
}
