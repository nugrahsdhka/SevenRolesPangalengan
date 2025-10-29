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
        // 1. Ambil semua data statistik dalam satu query
        $stats = DataGizi::where('status', 'published')
            ->select(
                // Data untuk Kartu Statistik
                DB::raw('COUNT(*) as total_anak'),
                DB::raw('SUM(CASE WHEN zs_tb_u < -2 THEN 1 ELSE 0 END) as total_kasus_stunting'),
                DB::raw('COUNT(DISTINCT desa_kelurahan) as total_desa'),
                
                // ⚠️ ASUMSI: Ganti 'nama_faskes' jika nama kolom Anda berbeda
                // DB::raw('COUNT(DISTINCT nama_faskes) as total_faskes'), 
                
                // Data untuk Distribution Bar (berdasarkan Z-Score TB/U)
                DB::raw('SUM(CASE WHEN zs_tb_u < -3 THEN 1 ELSE 0 END) as sangat_pendek_count'), // Merah
                DB::raw('SUM(CASE WHEN zs_tb_u >= -3 AND zs_tb_u < -2 THEN 1 ELSE 0 END) as pendek_count'), // Oranye
                DB::raw('SUM(CASE WHEN zs_tb_u >= -2 AND zs_tb_u <= 2 THEN 1 ELSE 0 END) as normal_count'), // Hijau
                DB::raw('SUM(CASE WHEN zs_tb_u > 2 THEN 1 ELSE 0 END) as tinggi_count') // Biru
            )
            ->first();

        // 2. Proses Statistik Dasar
        $total_anak = $stats->total_anak ?? 0; // <-- KITA PERLU VARIABEL INI
        $total_desa = $stats->total_desa ?? 0;
        // $total_faskes = $stats->total_faskes ?? 0;
        $total_kasus_stunting = $stats->total_kasus_stunting ?? 0;

        // Hitung Rata-rata Prevalensi Stunting
        $rata_rata_kasus = ($total_anak > 0) ? ($total_kasus_stunting / $total_anak) * 100 : 0;

        // 3. Proses Data Distribusi (Persentase)
        $distribusi = [
            'sangat_pendek' => ($total_anak > 0) ? ($stats->sangat_pendek_count / $total_anak) * 100 : 0,
            'pendek'        => ($total_anak > 0) ? ($stats->pendek_count / $total_anak) * 100 : 0,
            'normal'        => ($total_anak > 0) ? ($stats->normal_count / $total_anak) * 100 : 0,
            'tinggi'        => ($total_anak > 0) ? ($stats->tinggi_count / $total_anak) * 100 : 0,
        ];

        // Jumlah Anak per Kategori
        $distribusi_counts = [
            'sangat_pendek' => $stats->sangat_pendek_count ?? 0,
            'pendek'        => $stats->pendek_count ?? 0,
            'normal'        => $stats->normal_count ?? 0,
            'tinggi'        => $stats->tinggi_count ?? 0,
        ];

        // 4. Kirim data ke view
        return view('public_view.home.welcome', compact(
            'total_desa',
            // 'total_faskes',
            'rata_rata_kasus',
            'distribusi',
            'distribusi_counts',
            'total_anak' // <-- TAMBAHKAN INI
        ));
    }
}