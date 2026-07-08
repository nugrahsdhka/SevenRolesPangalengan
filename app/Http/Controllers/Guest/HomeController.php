<?php

namespace App\Http\Controllers\Guest;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\DataGizi;
use App\Models\Faskes;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    /**
     * Menampilkan halaman utama (welcome page) dengan data ringkasan.
     */
    public function index(Request $request)
    {
        // ========== 0. Siapkan daftar period & period terpilih ==========
        $periodBaseQuery = DataGizi::where('status', 'published')
            ->whereNotNull('period');

        // Ambil semua period dalam format YYYY-MM, diurutkan dari terbaru
        $availablePeriods = (clone $periodBaseQuery)
            ->selectRaw("DATE_FORMAT(period, '%Y-%m') as period_ym")
            ->distinct()
            ->orderByDesc('period_ym')
            ->pluck('period_ym');

        // Ambil period dari query string (?period=YYYY-MM)
        $selectedPeriod = $request->query('period');

        // Jika tidak ada di query, default ke period terbaru (kalau ada)
        if (!$selectedPeriod && $availablePeriods->isNotEmpty()) {
            $selectedPeriod = $availablePeriods->first();
        }

        // Query dasar DataGizi yang sudah ter-filter status & (opsional) period
        $dataGiziBaseQuery = DataGizi::where('status', 'published');

        if ($selectedPeriod) {
            $dataGiziBaseQuery->whereRaw("DATE_FORMAT(period, '%Y-%m') = ?", [$selectedPeriod]);
        }

        // 1. Total Faskes (tidak tergantung period)
        $total_faskes = Faskes::where('is_active', true)->count();

        // 2. Total Desa (berdasarkan data gizi yang terfilter period)
        $total_desa = (clone $dataGiziBaseQuery)
            ->distinct('desa_kelurahan')
            ->count('desa_kelurahan');

        // 3. Statistik Agregat Stunting (Header Stats) - TERFILTER period
        $stats = (clone $dataGiziBaseQuery)
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

        $total_anak     = $stats->total_anak ?? 0;
        $total_stunting  = $stats->total_stunting ?? 0; 

        // 4. Proses Angka (Header)
        $prevalensiPerDesa = (clone $dataGiziBaseQuery)
            ->whereNotNull('zs_tb_u')
            ->selectRaw('SUM(CASE WHEN zs_tb_u < -2 THEN 1 ELSE 0 END) * 100.0 / NULLIF(COUNT(*), 0) as p')
            ->groupBy('desa_kelurahan')
            ->get();

        // Ambil rata-rata dari prevalensi tiap desa
        $rata_rata_kasus = $prevalensiPerDesa->avg('p') ?? 0;
        $rata_rata_kasus = round($rata_rata_kasus, 2);

        // Data untuk Counts (dipakai di legend chart header)
        $distribusi_counts = [
            'sangat_pendek' => (int) ($stats->count_sangat_pendek ?? 0),
            'pendek'        => (int) ($stats->count_pendek ?? 0),
            'normal'        => (int) ($stats->count_normal ?? 0),
            'tinggi'        => (int) ($stats->count_tinggi ?? 0),
        ];

        // Data untuk Persentase (dipakai di bar chart header)
        $distribusi = [
            'sangat_pendek' => ($total_anak > 0) ? (($distribusi_counts['sangat_pendek'] / $total_anak) * 100) : 0,
            'pendek'        => ($total_anak > 0) ? (($distribusi_counts['pendek'] / $total_anak) * 100) : 0,
            'normal'        => ($total_anak > 0) ? (($distribusi_counts['normal'] / $total_anak) * 100) : 0,
            'tinggi'        => ($total_anak > 0) ? (($distribusi_counts['tinggi'] / $total_anak) * 100) : 0,
        ];

        // 5. ===== DATA BARU UNTUK MAIN CONTENT CARDS (TERFILTER PERIODE) =====

        // 5.1. Data Hotspot (Desa Paling Rawan)
        $desaTerawan = (clone $dataGiziBaseQuery)
            ->whereNotNull('zs_tb_u')
            ->groupBy('desa_kelurahan')
            ->having(DB::raw('COUNT(*)'), '>', 0)
            ->select(
                'desa_kelurahan',
                DB::raw('SUM(zs_tb_u) / COUNT(*) as avg_zs_tbu')
            )
            ->orderBy('avg_zs_tbu', 'asc') // dari yang rata-rata ZS TB/U paling rendah
            ->first();

        // 5.2. Data Wilayah (Rata-rata Z-Score Keseluruhan)
        $avgZScores = (clone $dataGiziBaseQuery)
            ->select(
                DB::raw('AVG(zs_bb_u) as avg_bbu'),
                DB::raw('AVG(zs_tb_u) as avg_tbu'),
                DB::raw('AVG(zs_bb_tb) as avg_bbtb')
            )
            ->first();

        // 6. Kirim semua data ke view
        return view('public.home.index', compact(
            'total_faskes',
            'total_desa',
            'rata_rata_kasus',
            'total_anak',
            'distribusi',
            'distribusi_counts',
            'desaTerawan',
            'avgZScores',
            'total_stunting',
            'availablePeriods',
            'selectedPeriod'
        ));
    }
}
