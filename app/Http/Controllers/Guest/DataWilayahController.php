<?php

namespace App\Http\Controllers\Guest;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\DataGizi;
use Illuminate\Support\Facades\DB;

class DataWilayahController extends Controller
{
    public function index(Request $request)
    {
        $perPage = 15;

        // Pencarian desa
        $search = $request->query('search');

        // ================== PERIODE: daftar & default ==================
        $availablePeriods = DataGizi::where('status', 'published')
            ->whereNotNull('period')
            ->select(DB::raw("DATE_FORMAT(period, '%Y-%m') as ym"))
            ->distinct()
            ->orderBy('ym', 'desc')
            ->pluck('ym');

        $hasPeriodParam = $request->has('period');       // key ?period ada atau tidak
        $selectedPeriod = $request->input('period');     // nilainya bisa: null / "" / "2025-01"

        if (!$hasPeriodParam && $availablePeriods->isNotEmpty()) {
            // Pertama kali buka halaman → pakai periode terbaru
            $selectedPeriod = $availablePeriods->first();
        } elseif ($hasPeriodParam && $selectedPeriod === '') {
            // User pilih "Semua Periode"
            $selectedPeriod = null;
        }

        // ================== QUERY AGREGASI PER DESA ==================
        $query = DataGizi::query()
            ->select(
                'desa_kelurahan',

                // Periode diambil dari kolom period (bukan created_at)
                DB::raw('MAX(period) as periode'),
                DB::raw('YEAR(period) as tahun'),
                DB::raw('MONTH(period) as bulan'),

                // Rata-rata tinggi & berat
                DB::raw('AVG(tinggi) as avg_tb'),
                DB::raw('AVG(berat) as avg_bb'),

                // Agregasi anak & Z-score
                DB::raw('COUNT(*) as total_anak'),
                DB::raw('SUM(CASE WHEN zs_tb_u < -2 THEN 1 ELSE 0 END) as total_kasus_stunting'),
                DB::raw('SUM(zs_tb_u) as sum_zs_tbu'),
                DB::raw('SUM(zs_bb_u) as sum_zs_bbu'),
                DB::raw('SUM(zs_bb_tb) as sum_zs_bbtb')
            )
            ->where('status', 'published')
            ->whereNotNull('period');

        // Filter periode (kalau ada selectedPeriod yang aktif)
        if (!empty($selectedPeriod)) {
            $query->whereDate('period', '=', $selectedPeriod . '-01');
        }

        // Filter pencarian desa
        if ($search) {
            $query->where('desa_kelurahan', 'LIKE', '%' . $search . '%');
        }

        // Grouping & sorting
        $dataWilayah = $query
            ->groupBy('desa_kelurahan', 'tahun', 'bulan')
            ->orderBy('desa_kelurahan', 'asc')
            ->orderBy('tahun', 'desc')
            ->orderBy('bulan', 'desc')
            ->paginate($perPage)
            ->appends([
                'search' => $search,
                'period' => $request->has('period') ? $request->input('period') : $selectedPeriod,
            ]);

        return view('public.dataWilayah.index', compact(
            'dataWilayah',
            'search',
            'availablePeriods',
            'selectedPeriod',
        ));
    }
}
