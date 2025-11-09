<?php

namespace App\Http\Controllers\Guest;

use App\Http\Controllers\Controller;
use App\Models\DataGizi;
use Illuminate\Http\Request;
use App\Models\Faskes;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    public function index()
    {
        // 1. Ambil semua data statistik GLOBAL dalam satu query
        $stats = DataGizi::where('status', 'published')
            ->select(
                DB::raw('COUNT(*) as total_anak'),
                DB::raw('SUM(CASE WHEN zs_tb_u < -2 THEN 1 ELSE 0 END) as total_kasus_stunting'),
                DB::raw('COUNT(DISTINCT desa_kelurahan) as total_desa'),
                DB::raw('SUM(CASE WHEN zs_tb_u < -3 THEN 1 ELSE 0 END) as sangat_pendek_count'),
                DB::raw('SUM(CASE WHEN zs_tb_u >= -3 AND zs_tb_u < -2 THEN 1 ELSE 0 END) as pendek_count'),
                DB::raw('SUM(CASE WHEN zs_tb_u >= -2 AND zs_tb_u <= 2 THEN 1 ELSE 0 END) as normal_count'),
                DB::raw('SUM(CASE WHEN zs_tb_u > 2 THEN 1 ELSE 0 END) as tinggi_count')
            )
            ->first();

        // 2. Proses Statistik Dasar (GLOBAL)
        $total_anak = $stats->total_anak ?? 0;
        $total_desa = $stats->total_desa ?? 0;
        $total_kasus_stunting = $stats->total_kasus_stunting ?? 0;
        $rata_rata_kasus = ($total_anak > 0) ? ($total_kasus_stunting / $total_anak) * 100 : 0;

        // 3. Proses Data Distribusi (GLOBAL)
        $distribusi = [
            'sangat_pendek' => ($total_anak > 0) ? ($stats->sangat_pendek_count / $total_anak) * 100 : 0,
            'pendek'        => ($total_anak > 0) ? ($stats->pendek_count / $total_anak) * 100 : 0,
            'normal'        => ($total_anak > 0) ? ($stats->normal_count / $total_anak) * 100 : 0,
            'tinggi'        => ($total_anak > 0) ? ($stats->tinggi_count / $total_anak) * 100 : 0,
        ];

        $distribusi_counts = [
            'sangat_pendek' => $stats->sangat_pendek_count ?? 0,
            'pendek'        => $stats->pendek_count ?? 0,
            'normal'        => $stats->normal_count ?? 0,
            'tinggi'        => $stats->tinggi_count ?? 0,
        ];

        // 4. Spoiler Data Stunting
        $spoilerDataStunting = DataGizi::where('status', 'published')
            ->select(
                'desa_kelurahan',
                DB::raw('SUM(zs_tb_u) / COUNT(*) as avg_zs_tbu'),
                DB::raw('SUM(CASE WHEN zs_tb_u < -2 THEN 1 ELSE 0 END) as total_kasus_stunting'),
                DB::raw('COUNT(*) as total_anak')
            )
            ->groupBy('desa_kelurahan')
            ->having(DB::raw('COUNT(*)'), '>', 0)
            ->havingNotNull('avg_zs_tbu')
            ->orderBy('avg_zs_tbu', 'asc')
            ->limit(3)
            ->get();

        // 5. Spoiler Data Wilayah
        $spoilerDataWilayah = DataGizi::where('status', 'published')
            ->select(
                'desa_kelurahan',
                DB::raw('MAX(created_at) as update_terbaru'),
                DB::raw('COUNT(*) as total_anak_di_desa'),
                DB::raw('AVG(berat) as avg_berat'),
                DB::raw('AVG(tinggi) as avg_tinggi')
            )
            ->groupBy('desa_kelurahan')
            ->orderBy('update_terbaru', 'desc')
            ->limit(3)
            ->get();

        // 6. Ambil data FASKES dari database
        $faskesList = Faskes::where('is_active', true)
            ->select('id', 'nama_faskes', 'jenis', 'alamat', 'desa_kelurahan', 'lat', 'lng')
            ->orderBy('nama_faskes')
            ->get();

        // Hitung total faskes
        $total_faskes = $faskesList->count();

        // 7. Kirim semua data ke view
        return view('public_view.home.welcome', compact(
            'total_desa',
            'rata_rata_kasus',
            'distribusi',
            'distribusi_counts',
            'total_anak',
            'spoilerDataStunting',
            'spoilerDataWilayah',
            'faskesList', // <--- ini dari file JSON, siap dipakai di Blade / JS
            'total_faskes'
        ));
    }
}
