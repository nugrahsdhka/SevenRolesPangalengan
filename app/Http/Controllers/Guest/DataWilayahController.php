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
        $selectedPeriod = $request->input('period');     // bisa: null / "" / "2025-01"

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

        // Filter periode
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
                'period' => $hasPeriodParam ? $request->input('period') : $selectedPeriod,
            ]);

        return view('public.dataWilayah.index', compact(
            'dataWilayah',
            'search',
            'availablePeriods',
            'selectedPeriod',
        ));
    }

    public function detailGizi(Request $request)
    {
        $desa = $request->query('desa');

        if (!$desa) {
            abort(404, 'Desa tidak ditemukan.');
        }

        // Periode (YYYY-MM atau null/"")
        $periodYm = $request->query('period');
        if ($periodYm === '') {
            $periodYm = null;
        }

        // Filter tambahan
        $filterPosyandu = $request->query('posyandu');
        $filterRt       = $request->query('rt');
        $filterRw       = $request->query('rw');

        // Base query untuk desa + periode
        $baseQuery = DataGizi::where('status', 'published')
            ->whereNotNull('period')
            ->where('desa_kelurahan', $desa);

        if (!empty($periodYm)) {
            $baseQuery->whereDate('period', '=', $periodYm . '-01');
        }

        // ============== OPTIONS FILTER ==============

        $posyanduOptions = (clone $baseQuery)
            ->select('posyandu')
            ->whereNotNull('posyandu')
            ->where('posyandu', '!=', '')
            ->distinct()
            ->orderBy('posyandu')
            ->pluck('posyandu');

        $rtOptions = (clone $baseQuery)
            ->select('rt')
            ->whereNotNull('rt')
            ->where('rt', '!=', '')
            ->distinct()
            ->orderBy('rt')
            ->pluck('rt');

        $rwOptions = (clone $baseQuery)
            ->select('rw')
            ->whereNotNull('rw')
            ->where('rw', '!=', '')
            ->distinct()
            ->orderBy('rw')
            ->pluck('rw');

        // ============== DATA ANAK (TERFILTER) ==============
        $dataQuery = clone $baseQuery;

        if (!empty($filterPosyandu)) {
            $dataQuery->where('posyandu', $filterPosyandu);
        }
        if (!empty($filterRt)) {
            $dataQuery->where('rt', $filterRt);
        }
        if (!empty($filterRw)) {
            $dataQuery->where('rw', $filterRw);
        }

        $dataAnak = $dataQuery
            ->select(
                'nama',
                'posyandu',
                'rt',
                'rw',
                'tgl_lahir',
                'berat',
                'tinggi',
                'bb_u',
                'zs_bb_u',
                'status_bb_u',
                'tb_u',
                'zs_tb_u',
                'status_tb_u',
                'bb_tb',
                'zs_bb_tb',
                'status_bb_tb'
            )
            ->orderBy('nama')
            ->paginate(100)
            ->appends([
                'desa'     => $desa,
                'period'   => $periodYm,
                'posyandu' => $filterPosyandu,
                'rt'       => $filterRt,
                'rw'       => $filterRw,
            ]);

        return view('public.dataWilayah.detail', [
            'desa'            => $desa,
            'periodYm'        => $periodYm,
            'dataAnak'        => $dataAnak,
            'posyanduOptions' => $posyanduOptions,
            'rtOptions'       => $rtOptions,
            'rwOptions'       => $rwOptions,
            'filterPosyandu'  => $filterPosyandu,
            'filterRt'        => $filterRt,
            'filterRw'        => $filterRw,
        ]);
    }
}
