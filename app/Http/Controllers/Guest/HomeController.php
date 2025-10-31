<?php

namespace App\Http\Controllers\Guest; // Sesuaikan dengan namespace Anda

use App\Http\Controllers\Controller;
use App\Models\DataGizi; // Pastikan model DataGizi di-import
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB; // Pastikan DB di-import

class HomeController extends Controller
{
    public function index()
    {
        // 1. Ambil semua data statistik GLOBAL dalam satu query
        $stats = DataGizi::where('status', 'published')
            ->select(
                // Data untuk Kartu Statistik
                DB::raw('COUNT(*) as total_anak'),
                DB::raw('SUM(CASE WHEN zs_tb_u < -2 THEN 1 ELSE 0 END) as total_kasus_stunting'),
                DB::raw('COUNT(DISTINCT desa_kelurahan) as total_desa'),
                
                // Data untuk Distribution Bar (berdasarkan Z-Score TB/U)
                DB::raw('SUM(CASE WHEN zs_tb_u < -3 THEN 1 ELSE 0 END) as sangat_pendek_count'), // Merah
                DB::raw('SUM(CASE WHEN zs_tb_u >= -3 AND zs_tb_u < -2 THEN 1 ELSE 0 END) as pendek_count'), // Oranye
                DB::raw('SUM(CASE WHEN zs_tb_u >= -2 AND zs_tb_u <= 2 THEN 1 ELSE 0 END) as normal_count'), // Hijau
                DB::raw('SUM(CASE WHEN zs_tb_u > 2 THEN 1 ELSE 0 END) as tinggi_count') // Biru
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
       
        // 4. Ambil Spoiler Data Stunting (Tidak berubah)
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

        // 5. Ambil Spoiler Data Wilayah (3 Desa dengan update terbaru)
        $spoilerDataWilayah = DataGizi::where('status', 'published')
            ->select(
                'desa_kelurahan',
                DB::raw('MAX(created_at) as update_terbaru'),
                DB::raw('COUNT(*) as total_anak_di_desa'),
                DB::raw('AVG(berat) as avg_berat'), // <-- TAMBAHKAN INI
                DB::raw('AVG(tinggi) as avg_tinggi') // <-- TAMBAHKAN INI
            )
            ->groupBy('desa_kelurahan')
            ->orderBy('update_terbaru', 'desc') // Urutkan dari yang terbaru
            ->limit(3)                        // Ambil 3 teratas
            ->get();


        // 6. Kirim semua data ke view
        return view('public_view.home.welcome', compact( // Pastikan nama view ini benar
            'total_desa',
            'rata_rata_kasus',
            'distribusi',
            'distribusi_counts',
            'total_anak',
            'spoilerDataStunting',
            'spoilerDataWilayah' // Data ini sekarang punya avg_berat & avg_tinggi
        ));
    }
}