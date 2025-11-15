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
        // Ambil semua periode yang PUBLISHED
        $availablePeriods = DataGizi::query()
            ->where('status', 'published')
            ->selectRaw('DATE_FORMAT(period, "%Y-%m-01") as period_start')
            ->distinct()
            ->orderByDesc('period_start')
            ->pluck('period_start'); // collection: 'Y-m-d'

        return view('public.analisisHotspot.index', compact('availablePeriods'));
    }

    public function getChartDataHotspot(Request $request)
    {
        $period = $request->query('period'); // YYYY-MM

        $query = DataGizi::query()
            ->select(
                'desa_kelurahan',
                DB::raw('COUNT(*) as total_anak'),
                DB::raw('SUM(CASE WHEN zs_tb_u < -2 THEN 1 ELSE 0 END) as total_kasus_stunting'),
                DB::raw('SUM(zs_tb_u) / COUNT(*) as avg_zs_tbu')
            )
            ->where('status', 'published');

        if ($period) {
            $query->whereYear('period', substr($period, 0, 4))
                ->whereMonth('period', substr($period, 5, 2));
        }

        $rows = $query->groupBy('desa_kelurahan')->get();

        $data = [];

        foreach ($rows as $r) {
            if ($r->total_anak == 0) continue;

            $avg = (float) $r->avg_zs_tbu;

            if ($avg < -3)       $kategori = 'Sangat Pendek';
            elseif ($avg < -2)  $kategori = 'Pendek';
            elseif ($avg <= 2)  $kategori = 'Normal';
            else                $kategori = 'Tinggi';

            $key = strtoupper(trim($r->desa_kelurahan));

            $data[$key] = [
                'avg_zs_tbu'           => round($avg, 2),
                'kategori'             => $kategori,
                'total_anak'           => (int) $r->total_anak,
                'total_kasus_stunting' => (int) $r->total_kasus_stunting,
            ];
        }

        return response()->json($data);
    }
}
