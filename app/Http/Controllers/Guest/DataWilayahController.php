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

        // 1. Ambil nilai 'search' dari request.
        $search = $request->query('search');

        // Mulai query
        $query = DataGizi::query()
            ->select(
                'desa_kelurahan',
                // Ambil tanggal 'created_at' pertama sebagai 'periode' untuk group tersebut
                DB::raw('MIN(created_at) as periode'), // <-- REVISI
                DB::raw('YEAR(created_at) as tahun'),
                DB::raw('MONTH(created_at) as bulan'),

                // ===== TAMBAHAN BARU =====
                DB::raw('AVG(tinggi) as avg_tb'), // Rata-rata Tinggi Badan
                DB::raw('AVG(berat) as avg_bb'), // Rata-rata Berat Badan
                // =========================

                DB::raw('COUNT(*) as total_anak'),
                DB::raw('SUM(CASE WHEN zs_tb_u < -2 THEN 1 ELSE 0 END) as total_kasus_stunting'),
                DB::raw('SUM(zs_tb_u) as sum_zs_tbu'),
                DB::raw('SUM(zs_bb_u) as sum_zs_bbu'),
                DB::raw('SUM(zs_bb_tb) as sum_zs_bbtb')
            )
            ->where('status', 'published');

        // 2. Terapkan filter pencarian JIKA $search tidak kosong
        if ($search) {
            // Kita filter data SEBELUM di-group by
            $query->where('desa_kelurahan', 'LIKE', '%' . $search . '%');
        }

        // Lanjutkan query dengan grouping, ordering, dan paginasi
        $dataWilayah = $query->groupBy('desa_kelurahan', 'tahun', 'bulan')
            ->orderBy('desa_kelurahan', 'asc')
            ->orderBy('tahun', 'desc')
            ->orderBy('bulan', 'desc')
            ->paginate($perPage);

        // 3. Kirim 'dataWilayah' DAN 'search' ke view.
        return view('public.dataWilayah.index', compact('dataWilayah', 'search'));
    }
}