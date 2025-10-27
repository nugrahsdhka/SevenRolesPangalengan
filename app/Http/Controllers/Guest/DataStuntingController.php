<?php

namespace App\Http\Controllers\Guest;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\DataGizi;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon; // <-- Tambahkan ini

class DataStuntingController extends Controller
{
    public function index(Request $request)
    {
        $perPage = 15;
        $search = $request->input('search');
        $tingkat_zs = $request->input('tingkat_zs');
        // Ambil view dari URL atau default 'table'
        $currentView = $request->input('view', 'table');

        $query = DataGizi::query()
            ->select(
                'desa_kelurahan',
                DB::raw('COUNT(*) as total_anak'),
                DB::raw('SUM(CASE WHEN zs_tb_u < -2 THEN 1 ELSE 0 END) as total_kasus_stunting'),
                DB::raw('MAX(created_at) as periode'),
                DB::raw('SUM(zs_tb_u) / COUNT(*) as avg_zs_tbu')
            )
            ->where('status', 'published')
            ->groupBy('desa_kelurahan')
            ->having(DB::raw('COUNT(*)'), '>', 0);

        if ($search) {
            $query->where('desa_kelurahan', 'like', '%' . $search . '%');
        }

        if ($tingkat_zs) {
            if ($tingkat_zs == 'sangat_pendek') { $query->having('avg_zs_tbu', '<', -3); }
            elseif ($tingkat_zs == 'pendek') { $query->having('avg_zs_tbu', '>=', -3)->having('avg_zs_tbu', '<', -2); }
            elseif ($tingkat_zs == 'normal') { $query->having('avg_zs_tbu', '>=', -2)->having('avg_zs_tbu', '<=', 2); }
            elseif ($tingkat_zs == 'tinggi') { $query->having('avg_zs_tbu', '>', 2); }
            // Filter 'na' bisa diabaikan atau ditambahkan jika perlu (misal havingRaw('avg_zs_tbu IS NULL'))
        }

        $dataStunting = $query->orderBy('desa_kelurahan', 'asc')
                               ->paginate($perPage)
                               // Sertakan view saat ini di paginasi tabel
                               ->appends($request->only(['search', 'tingkat_zs', 'view']));

        // Kirim semua variabel filter DAN view saat ini
        return view('public_view.dataStunting.index', compact(
            'dataStunting',
            'search',
            'tingkat_zs',
            'currentView' // <-- Kirim ini ke view
        ));
    }

    // ✅ METHOD BARU UNTUK AMBIL DATA CHART ZS
    public function getChartDataZs(Request $request)
    {
        $search = $request->input('search');
        $tingkat_zs = $request->input('tingkat_zs');

        $query = DataGizi::query()
            ->select(
                'desa_kelurahan',
                // Pastikan avg_zs_tbu dihitung dengan benar
                DB::raw('SUM(zs_tb_u) / COUNT(*) as avg_zs_tbu')
            )
            ->where('status', 'published')
            ->groupBy('desa_kelurahan')
            ->having(DB::raw('COUNT(*)'), '>', 0) // Pastikan ada data anak
            ->havingNotNull('avg_zs_tbu'); // Hanya ambil yang ZS-nya bisa dihitung

        if ($search) {
            $query->where('desa_kelurahan', 'like', '%' . $search . '%');
        }

        // Terapkan filter Z-Score
         if ($tingkat_zs) {
             if ($tingkat_zs == 'sangat_pendek') { $query->having('avg_zs_tbu', '<', -3); }
             elseif ($tingkat_zs == 'pendek') { $query->having('avg_zs_tbu', '>=', -3)->having('avg_zs_tbu', '<', -2); }
             elseif ($tingkat_zs == 'normal') { $query->having('avg_zs_tbu', '>=', -2)->having('avg_zs_tbu', '<=', 2); }
             elseif ($tingkat_zs == 'tinggi') { $query->having('avg_zs_tbu', '>', 2); }
             // Jika ada value 'na', perlu penanganan khusus (mungkin query yang berbeda)
         }


        // Urutkan berdasarkan Z-Score TERENDAH (paling stunting)
        $results = $query->orderBy('avg_zs_tbu', 'asc')
                         // Ambil semua data yang cocok (tidak perlu paginasi untuk chart)
                         ->get();

        // Format data untuk Chart.js
        $labels = $results->pluck('desa_kelurahan');
        $values = $results->pluck('avg_zs_tbu')->map(function ($value) {
            // Format ke 2 desimal jika perlu
            return round($value, 2);
        });

        return response()->json([
            'labels' => $labels,
            'values' => $values,
        ]);
    }
}