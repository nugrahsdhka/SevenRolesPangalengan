<?php

namespace App\Http\Controllers\Guest;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\DataGizi;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;

class DataStuntingController extends Controller
{
    public function index(Request $request)
    {
        $perPage = 15;
        $search = $request->input('search');
        $tingkat_zs = $request->input('tingkat_zs');
        $currentView = $request->input('view', 'table');

        // 1. Mulai query dasar TANPA group by
        $query = DataGizi::query()
            ->select(
                'desa_kelurahan',
                DB::raw('COUNT(*) as total_anak'),
                DB::raw('SUM(CASE WHEN zs_tb_u < -2 THEN 1 ELSE 0 END) as total_kasus_stunting'),
                DB::raw('MAX(created_at) as periode'),
                DB::raw('SUM(zs_tb_u) / COUNT(*) as avg_zs_tbu')
            )
            ->where('status', 'published');

        // 2. Terapkan filter 'where' (Pencarian) SEBELUM group by
        if ($search) {
            $query->where('desa_kelurahan', 'like', '%' . $search . '%');
        }

        // 3. SEKARANG baru terapkan group by
        $query->groupBy('desa_kelurahan')
              ->having(DB::raw('COUNT(*)'), '>', 0);

        // 4. Terapkan filter 'having' (setelah group by)
        if ($tingkat_zs) {
            if ($tingkat_zs == 'sangat_pendek') { $query->having('avg_zs_tbu', '<', -3); }
            elseif ($tingkat_zs == 'pendek') { $query->having('avg_zs_tbu', '>=', -3)->having('avg_zs_tbu', '<', -2); }
            elseif ($tingkat_zs == 'normal') { $query->having('avg_zs_tbu', '>=', -2)->having('avg_zs_tbu', '<=', 2); }
            elseif ($tingkat_zs == 'tinggi') { $query->having('avg_zs_tbu', '>', 2); }
        }

        // 5. Selesaikan query
        $dataStunting = $query->orderBy('desa_kelurahan', 'asc')
            ->paginate($perPage)
            ->appends($request->only(['search', 'tingkat_zs', 'view']));

        return view('public_view.dataStunting.index', compact(
            'dataStunting',
            'search',
            'tingkat_zs',
            'currentView'
        ));
    }

    // ✅ METHOD BARU UNTUK AMBIL DATA CHART ZS
    public function getChartDataZs(Request $request)
    {
        $search = $request->input('search');
        $tingkat_zs = $request->input('tingkat_zs');

        // 1. Mulai query dasar TANPA group by
        $query = DataGizi::query()
            ->select(
                'desa_kelurahan',
                DB::raw('SUM(zs_tb_u) / COUNT(*) as avg_zs_tbu')
            )
            ->where('status', 'published');

        // 2. Terapkan filter 'where' (Pencarian) SEBELUM group by
        if ($search) {
            $query->where('desa_kelurahan', 'like', '%' . $search . '%');
        }

        // 3. SEKARANG baru terapkan group by
        $query->groupBy('desa_kelurahan')
              ->having(DB::raw('COUNT(*)'), '>', 0)
              ->havingNotNull('avg_zs_tbu');

        // 4. Terapkan filter 'having' (setelah group by)
        if ($tingkat_zs) {
            if ($tingkat_zs == 'sangat_pendek') { $query->having('avg_zs_tbu', '<', -3); }
            elseif ($tingkat_zs == 'pendek') { $query->having('avg_zs_tbu', '>=', -3)->having('avg_zs_tbu', '<', -2); }
            elseif ($tingkat_zs == 'normal') { $query->having('avg_zs_tbu', '>=', -2)->having('avg_zs_tbu', '<=', 2); }
            elseif ($tingkat_zs == 'tinggi') { $query->having('avg_zs_tbu', '>', 2); }
        }

        // 5. Selesaikan query
        $results = $query->orderBy('avg_zs_tbu', 'asc')
            ->get();

        $labels = $results->pluck('desa_kelurahan');
        $values = $results->pluck('avg_zs_tbu')->map(function ($value) {
            return round($value, 2);
        });

        return response()->json([
            'labels' => $labels,
            'values' => $values,
        ]);
    }
}