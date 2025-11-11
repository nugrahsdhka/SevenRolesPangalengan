<?php

namespace App\Http\Controllers\Guest;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\DataGizi; // ⬅️ Import DataGizi
use App\Models\Faskes;  // ⬅️ Import Faskes
use Illuminate\Support\Facades\DB; // ⬅️ Import DB Facade

class HomeController extends Controller
{
    /**
     * Menampilkan halaman utama (welcome page) dengan data ringkasan.
     */
    public function index()
    {
        // 1. Total Faskes (dari PetaController)
        $total_faskes = Faskes::where('is_active', true)->count();

        // 2. Total Desa
        $total_desa = DataGizi::where('status', 'published')
                            ->distinct('desa_kelurahan')
                            ->count('desa_kelurahan');

        // 3. Statistik Agregat Stunting (Header Stats)
        $stats = DataGizi::where('status', 'published')
            ->whereNotNull('zs_tb_u') // Hanya hitung yang ZS-nya valid
            ->select(
                DB::raw('COUNT(*) as total_anak'),
                DB::raw('SUM(CASE WHEN zs_tb_u < -2 THEN 1 ELSE 0 END) as total_stunting'),
                DB::raw('SUM(CASE WHEN zs_tb_u < -3 THEN 1 ELSE 0 END) as count_sangat_pendek'),
                DB::raw('SUM(CASE WHEN zs_tb_u >= -3 AND zs_tb_u < -2 THEN 1 ELSE 0 END) as count_pendek'),
                DB::raw('SUM(CASE WHEN zs_tb_u >= -2 AND zs_tb_u <= 2 THEN 1 ELSE 0 END) as count_normal'),
                DB::raw('SUM(CASE WHEN zs_tb_u > 2 THEN 1 ELSE 0 END) as count_tinggi')
            )
            ->first();

        // 4. Proses Angka (Header)
        $total_anak = $stats->total_anak ?? 0;
        $total_stunting = $stats->total_stunting ?? 0;
        $rata_rata_kasus = ($total_anak > 0) ? ($total_stunting / $total_anak) * 100 : 0;

        // Data untuk Counts (dipakai di legend chart header)
        $distribusi_counts = [
            'sangat_pendek' => (int)($stats->count_sangat_pendek ?? 0),
            'pendek'        => (int)($stats->count_pendek ?? 0),
            'normal'        => (int)($stats->count_normal ?? 0),
            'tinggi'        => (int)($stats->count_tinggi ?? 0),
        ];

        // Data untuk Persentase (dipakai di bar chart header)
        $distribusi = [
            'sangat_pendek' => ($total_anak > 0) ? (($distribusi_counts['sangat_pendek'] / $total_anak) * 100) : 0,
            'pendek'        => ($total_anak > 0) ? (($distribusi_counts['pendek'] / $total_anak) * 100) : 0,
            'normal'        => ($total_anak > 0) ? (($distribusi_counts['normal'] / $total_anak) * 100) : 0,
            'tinggi'        => ($total_anak > 0) ? (($distribusi_counts['tinggi'] / $total_anak) * 100) : 0,
        ];

        // 5. ===== DATA BARU UNTUK MAIN CONTENT CARDS =====

        // 5.1. Data Hotspot (Desa Paling Rawan)
        $desaTerawan = DataGizi::where('status', 'published')
            ->groupBy('desa_kelurahan')
            ->having(DB::raw('COUNT(*)'), '>', 0) // Pastikan ada data
            ->select(
                'desa_kelurahan',
                DB::raw('SUM(zs_tb_u) / COUNT(*) as avg_zs_tbu')
            )
            ->whereNotNull('zs_tb_u')
            ->orderBy('avg_zs_tbu', 'asc') // Urutkan dari ZS terendah
            ->first(); // Ambil 1

        // 5.2. Data Wilayah (Rata-rata Z-Score Keseluruhan)
        // Pastikan nama kolom di DB Anda adalah zs_bbu, zs_tbu, zs_bbtb
        $avgZScores = DataGizi::where('status', 'published')
            ->select(
                DB::raw('AVG(zs_bb_u) as avg_bbu'), // Menggunakan zs_bb_u
                DB::raw('AVG(zs_tb_u) as avg_tbu'), // Menggunakan zs_tb_u
                DB::raw('AVG(zs_bb_tb) as avg_bbtb') // Menggunakan zs_bb_tb
            )
            ->first();
            
        // 5.3. Data Faskes (total_faskes sudah ada dari atas)
        
        // 5.4. Data Stunting (total_stunting dan total_anak sudah ada dari atas)

        // 6. Kirim semua data ke view
        return view('public_view.home.welcome', compact(
            'total_faskes',
            'total_desa',
            'rata_rata_kasus',
            'total_anak',
            'distribusi',
            'distribusi_counts',
            'desaTerawan',      // <-- Data baru untuk card Hotspot
            'avgZScores',       // <-- Data baru untuk card Wilayah
            'total_stunting'    // <-- Data baru untuk card Stunting
        ));
    }
}