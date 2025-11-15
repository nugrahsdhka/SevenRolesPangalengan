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
        $perPage     = 15;
        $search      = $request->input('search');
        $tingkat_zs  = $request->input('tingkat_zs');
        $currentView = $request->input('view', 'table');

        // --- Ambil daftar periode unik (Y-m) yang ada di DB, urut dari terbaru ---
        $availablePeriods = DataGizi::where('status', 'published')
            ->whereNotNull('period')
            ->select(DB::raw("DATE_FORMAT(period, '%Y-%m') as ym"))
            ->distinct()
            ->orderBy('ym', 'desc')
            ->pluck('ym');

        // --- Logika default periode ---
        $hasPeriodParam = $request->has('period');       // true kalau key 'period' ada di query (meski kosong)
        $selectedPeriod = $request->input('period');     // bisa null, '', atau '2025-01'

        if (!$hasPeriodParam && $availablePeriods->isNotEmpty()) {
            // Pertama kali buka halaman: pakai periode terbaru sebagai default
            $selectedPeriod = $availablePeriods->first();
        } elseif ($hasPeriodParam && $selectedPeriod === '') {
            // User pilih "Semua Periode"
            $selectedPeriod = null;
        }

        // --- Query agregasi data stunting per desa ---
        $query = DataGizi::query()
            ->select(
                'desa_kelurahan',
                DB::raw('COUNT(*) as total_anak'),
                DB::raw('SUM(CASE WHEN zs_tb_u < -2 THEN 1 ELSE 0 END) as total_kasus_stunting'),
                DB::raw('MAX(period) as periode'),
                DB::raw('AVG(zs_tb_u) as avg_zs_tbu')
            )
            ->where('status', 'published');

        // Filter periode (kalau ada selectedPeriod aktif)
        if (!empty($selectedPeriod)) {
            // di DB: period disimpan sebagai tanggal 1 setiap bulan (Y-m-d)
            $query->whereDate('period', '=', $selectedPeriod . '-01');
        }

        // Filter pencarian desa
        if (!empty($search)) {
            $query->where('desa_kelurahan', 'like', '%' . $search . '%');
        }

        // Group per desa
        $query->groupBy('desa_kelurahan')
              ->havingRaw('COUNT(*) > 0');

        // Filter status Z-Score pakai HAVING
        if (!empty($tingkat_zs)) {
            if ($tingkat_zs === 'sangat_pendek') {
                $query->having('avg_zs_tbu', '<', -3);
            } elseif ($tingkat_zs === 'pendek') {
                $query->having('avg_zs_tbu', '>=', -3)
                      ->having('avg_zs_tbu', '<', -2);
            } elseif ($tingkat_zs === 'normal') {
                $query->having('avg_zs_tbu', '>=', -2)
                      ->having('avg_zs_tbu', '<=', 2);
            } elseif ($tingkat_zs === 'tinggi') {
                $query->having('avg_zs_tbu', '>', 2);
            } elseif ($tingkat_zs === 'na') {
                $query->havingRaw('avg_zs_tbu IS NULL');
            }
        }

        // Pagination + keep query string
        $dataStunting = $query->orderBy('desa_kelurahan', 'asc')
            ->paginate($perPage)
            ->appends([
                'search'      => $search,
                'tingkat_zs'  => $tingkat_zs,
                'view'        => $currentView,
                'period'      => $request->has('period') ? $request->input('period') : $selectedPeriod,
            ]);

        return view('public.dataStunting.index', compact(
            'dataStunting',
            'search',
            'tingkat_zs',
            'currentView',
            'availablePeriods',
            'selectedPeriod',
        ));
    }

    // ✅ METHOD BARU UNTUK AMBIL DATA CHART ZS
    public function getChartDataZs(Request $request)
    {
        $search      = $request->input('search');
        $tingkat_zs  = $request->input('tingkat_zs');
        $periodParam = $request->input('period'); // bisa null / "" / "2025-01"

        $query = DataGizi::query()
            ->select(
                'desa_kelurahan',
                DB::raw('AVG(zs_tb_u) as avg_zs_tbu')
            )
            ->where('status', 'published');

        // 🔹 Filter periode (kalau dikirim dari URL)
        // - period = null  → tidak difilter (semua periode)
        // - period = ""    → tidak difilter (Semua Periode dipilih)
        // - period = "2025-01" → filter period = '2025-01-01'
        if ($periodParam !== null && $periodParam !== '') {
            $query->whereDate('period', '=', $periodParam . '-01');
        }

        // 🔹 Filter nama desa (search)
        if (!empty($search)) {
            $query->where('desa_kelurahan', 'like', '%' . $search . '%');
        }

        // 🔹 Group by desa
        $query->groupBy('desa_kelurahan')
            ->havingRaw('COUNT(*) > 0')
            ->havingRaw('avg_zs_tbu IS NOT NULL');

        // 🔹 Filter status Z-Score (sesuai klasifikasi)
        if (!empty($tingkat_zs)) {
            if ($tingkat_zs === 'sangat_pendek') {
                $query->having('avg_zs_tbu', '<', -3);
            } elseif ($tingkat_zs === 'pendek') {
                $query->having('avg_zs_tbu', '>=', -3)
                    ->having('avg_zs_tbu', '<', -2);
            } elseif ($tingkat_zs === 'normal') {
                $query->having('avg_zs_tbu', '>=', -2)
                    ->having('avg_zs_tbu', '<=', 2);
            } elseif ($tingkat_zs === 'tinggi') {
                $query->having('avg_zs_tbu', '>', 2);
            } elseif ($tingkat_zs === 'na') {
                $query->havingRaw('avg_zs_tbu IS NULL');
            }
        }

        $results = $query->orderBy('avg_zs_tbu', 'asc')->get();

        $labels = $results->pluck('desa_kelurahan');
        $values = $results->pluck('avg_zs_tbu')->map(function ($value) {
            return round((float) $value, 2);
        });

        return response()->json([
            'labels' => $labels,
            'values' => $values,
        ]);
    }

}
