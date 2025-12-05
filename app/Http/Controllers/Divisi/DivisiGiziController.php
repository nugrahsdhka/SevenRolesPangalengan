<?php

namespace App\Http\Controllers\Divisi;

use App\Http\Controllers\Controller;
use App\Imports\DataGiziFileImport;
use App\Models\DataGizi;
use App\Models\User; // Pastikan Model User di-import
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache; // Tambahkan Cache Facade
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Facades\Excel;

class DivisiGiziController extends Controller
{
    /**
     * Mendapatkan list periode yang tersedia untuk Role tertentu.
     * Menggunakan Cache agar tidak scan database terus menerus.
     */
    private function getAvailablePeriodsForRole(string $roleName)
    {
        $cacheKey = 'gizi_periods_' . $roleName;

        // Cache hasil query selama 60 menit (3600 detik)
        return Cache::remember($cacheKey, 3600, function () use ($roleName) {
            
            // 1. Ambil ID User yang punya role ini (Optimasi replace whereHas)
            // Ini jauh lebih ringan daripada join table besar
            $userIds = User::role($roleName)->pluck('id');

            return DataGizi::whereIn('user_id', $userIds)
                ->selectRaw('DATE_FORMAT(period, "%Y-%m-01") as p')
                ->distinct()
                ->orderBy('p', 'desc')
                ->pluck('p')
                ->map(fn($d) => date('Y-m-01', strtotime($d)))
                ->toArray();
        });
    }

    private function resolveCurrentPeriod(Request $request, string $roleName): string
    {
        $requested = $request->query('period') ?? $request->input('period');
        $sessionKey = 'gizi_period_' . $roleName;

        // Ambil dari cache method di atas
        $available = $this->getAvailablePeriodsForRole($roleName);

        if ($requested) {
            $periodYm = preg_match('/^\d{4}-\d{2}$/', $requested) 
                ? $requested 
                : date('Y-m', strtotime($requested));
        } elseif (session()->has($sessionKey)) {
            $periodYm = session($sessionKey);
        } elseif (!empty($available)) {
            $periodYm = date('Y-m', strtotime($available[0]));
        } else {
            $periodYm = now()->format('Y-m');
        }

        session([$sessionKey => $periodYm]);

        return $periodYm . '-01';
    }

    public function index(Request $request)
    {
        $currentUser   = Auth::user();
        // Asumsi user pasti punya role, ambil yang pertama
        $userRoleName  = $currentUser->roles->first()->name ?? 'Gizi'; 

        $view = $request->get('view', 'data');

        // Initialize variables
        $desaList     = collect();
        $selectedDesa = null;
        $dataDetail   = collect();
        $detailStat   = null;

        // 1. Ambil Periode (Cached)
        $availablePeriods = $this->getAvailablePeriodsForRole($userRoleName);
        $currentPeriod    = $this->resolveCurrentPeriod($request, $userRoleName);
        $periodYm         = date('Y-m', strtotime($currentPeriod));

        // 2. Resolve User IDs untuk Role ini (Cached 60 menit)
        // Teknik ini menggantikan 'whereHas' yang berat pada table DataGizi
        $userIds = Cache::remember('users_ids_role_' . $userRoleName, 3600, function() use ($userRoleName) {
            return User::role($userRoleName)->pluck('id')->toArray();
        });

        $status = $request->get('status', 'pending');
        if (!in_array($status, ['pending', 'approved', 'published'])) {
            $status = 'pending';
        }

        // Base Query: Memanfaatkan Index 'period' dan 'user_id'
        $base = DataGizi::whereIn('user_id', $userIds)
                        ->whereDate('period', $currentPeriod);

        // 3. Status Counts (Cached 5 menit untuk dashboard yang snappy)
        $cacheKeyStats = "gizi_stats_{$userRoleName}_{$currentPeriod}";
        $statusCounts = Cache::remember($cacheKeyStats, 300, function () use ($base) {
            return $base->clone()
                ->select('status', DB::raw('COUNT(DISTINCT desa_kelurahan) as desa_count'))
                ->groupBy('status')
                ->pluck('desa_count', 'status')
                ->toArray();
        });

        // 4. Aggregated Data (Dashboard Utama)
        // Query ini sekarang akan memanfaatkan index ['period', 'desa_kelurahan']
        $aggregatedData = $base->clone()
            ->where('status', $status)
            ->select(
                'desa_kelurahan',
                'status',
                DB::raw('COUNT(*) as total_anak'),
                DB::raw('MAX(created_at) as tanggal_input_terbaru'), // Hati-hati Strict Mode, tapi MAX aman
                DB::raw('SUM(CASE WHEN zs_tb_u < -2 THEN 1 ELSE 0 END) as total_kasus_stunting'),
                // Hapus agregasi SUM yang tidak perlu ditampilkan di tabel depan untuk meringankan beban
                // Jika view butuh, biarkan. Jika tidak, hapus sum_berat dll.
                DB::raw('SUM(berat) as sum_berat'),
                DB::raw('SUM(tinggi) as sum_tinggi'),
                DB::raw('SUM(zs_bb_u) as sum_zs_bbu'),
                DB::raw('SUM(zs_tb_u) as sum_zs_tbu'),
                DB::raw('SUM(zs_bb_tb) as sum_zs_bbtb')
            )
            ->groupBy('desa_kelurahan', 'status')
            ->orderBy('tanggal_input_terbaru', 'desc')
            ->paginate(50)
            ->onEachSide(1);

        // 5. Logic Detail View
        if ($view === 'detail') {
            // Mengambil list desa distinct untuk dropdown filter
            // Menggunakan pluck langsung lebih efisien daripada get()
            $desaList = $base->clone()
                ->distinct()
                ->orderBy('desa_kelurahan')
                ->pluck('desa_kelurahan');

            $selectedDesa = $request->get('desa') ?? $desaList->first();

            if ($selectedDesa) {
                // Pastikan desa di-uppercase & trim
                $normalizedDesa = strtoupper(trim($selectedDesa));

                $detailBase = $base->clone()
                    ->where('desa_kelurahan', $normalizedDesa);

                $dataDetail = $detailBase->clone()
                    ->orderBy('created_at', 'desc') // Pastikan ada index created_at jika sorting ini lambat
                    ->paginate(50)
                    ->onEachSide(1)
                    ->appends([
                        'view'   => 'detail',
                        'desa'   => $selectedDesa,
                        'period' => $periodYm,
                    ]);

                $detailStat = $detailBase->clone()
                    ->selectRaw('
                        COUNT(*) as total_anak,
                        SUM(CASE WHEN zs_tb_u < -2 THEN 1 ELSE 0 END) as total_stunting,
                        SUM(CASE WHEN zs_bb_u < -2 THEN 1 ELSE 0 END) as total_gizi_kurang,
                        SUM(CASE WHEN zs_bb_tb < -2 THEN 1 ELSE 0 END) as total_kurus,
                        AVG(zs_tb_u) as avg_zs_tbu,
                        AVG(zs_bb_u) as avg_zs_bbu,
                        AVG(zs_bb_tb) as avg_zs_bbtb,
                        AVG(berat) as avg_berat,
                        AVG(tinggi) as avg_tinggi
                    ')
                    ->first();
            }
        }

        return view('divisi.gizi.index', compact(
            'currentUser',
            'aggregatedData',
            'currentPeriod',
            'availablePeriods',
            'status',
            'statusCounts',
            'view',
            'desaList',
            'selectedDesa',
            'dataDetail',
            'detailStat',
            'periodYm'
        ));
    }

    public function create(Request $request)
    {
        $request->validate([
            'period' => ['required', 'regex:/^\d{4}-\d{2}(-\d{2})?$/'],
        ]);

        $period = preg_match('/^\d{4}-\d{2}$/', $request->period)
            ? ($request->period . '-01')
            : $request->period;

        return view('divisi.gizi.create', ['period' => date('Y-m-01', strtotime($period))]);
    }

    public function store(Request $request)
    {
        // Validasi diperbarui sesuai panjang kolom DB yang baru (150)
        $request->validate([
            'period' => ['required','regex:/^\d{4}-\d{2}(-\d{2})?$/'],
            'desa_kelurahan' => 'required|string|max:150', 
            'berat' => 'nullable|numeric',
            'tinggi' => 'nullable|numeric',
        ]);

        $period = date('Y-m-01', strtotime($request->period));
        $periodParam = date('Y-m', strtotime($period));
        $desa = strtoupper(trim($request->desa_kelurahan));
        
        $currentUser = Auth::user();
        $userRoleName = $currentUser->roles->first()->name;

        // Cek Lock Entry
        // Optimasi: gunakan user_id langsung jika logicnya adalah "User ini" atau "Role ini"
        // Kita gunakan pendekatan User ID untuk konsistensi dengan index
        $userIds = User::role($userRoleName)->pluck('id');

        $isEntryLocked = DataGizi::where('desa_kelurahan', $desa)
            ->whereDate('period', $period)
            ->whereIn('status', ['approved', 'published']) // Status locked
            ->whereIn('user_id', $userIds)
            ->exists();

        if ($isEntryLocked) {
            return back()->with('error', 'Data desa ini sudah di-Approve/Published.');
        }

        $data = $request->all();
        $data['user_id'] = $currentUser->id;
        $data['desa_kelurahan'] = $desa; // Simpan versi normalized

        DataGizi::create($data);

        // Hapus Cache agar data baru muncul di dashboard
        $this->clearDashboardCache($userRoleName, $period);

        return redirect()->route('gizi.index', ['period' => $periodParam])
            ->with('success', 'Data berhasil ditambahkan.');
    }

    public function update(Request $request, DataGizi $data_gizi)
    {
        $request->validate([
            'desa_kelurahan' => 'required|string|max:150',
            'berat' => 'nullable|numeric',
            'tinggi' => 'nullable|numeric',
        ]);

        // Capture data lama untuk clear cache yang tepat
        $oldPeriod = $data_gizi->period;
        $userRoleName = Auth::user()->roles->first()->name;

        $data_gizi->update($request->all());

        // Clear cache
        $this->clearDashboardCache($userRoleName, $oldPeriod);
        if($oldPeriod != $request->period) {
             // Jika periode berubah, clear juga cache periode baru (jika ada)
             $this->clearDashboardCache($userRoleName, $request->period ?? $oldPeriod);
        }

        return back()->with('success', 'Data berhasil diperbarui.');
    }

    public function import(Request $request)
    {
        $request->validate([
            'period'   => ['required', 'regex:/^\d{4}-\d{2}(-\d{2})?$/'],
            'files'    => ['required', 'array'],
            'files.*'  => 'file|mimes:xlsx,xls,csv|max:10240',
        ]);

        $period = preg_match('/^\d{4}-\d{2}$/', $request->period)
            ? ($request->period . '-01')
            : $request->period;

        $periodYm = date('Y-m', strtotime($period));
        $userRoleName = Auth::user()->roles->first()->name;

        try {
            foreach ($request->file('files') as $file) {
                // Pastikan DataGiziFileImport juga sudah dioptimasi (Nanti di Prioritas 2)
                Excel::import(new DataGiziFileImport($period, Auth::id()), $file);
            }

            // PENTING: Hapus Cache setelah import massal
            // Supaya dropdown periode dan hitungan dashboard ter-refresh
            Cache::forget('gizi_periods_' . $userRoleName);
            $this->clearDashboardCache($userRoleName, $period);

            return redirect()->route('gizi.index', ['period' => $periodYm])
                ->with('success', 'Import berhasil.');
        }
        catch (\Maatwebsite\Excel\Validators\ValidationException $e) {
            $f = $e->failures()[0];
            return back()->with('error', "Kesalahan validasi: {$f->errors()[0]} (baris {$f->row()}, kolom {$f->attribute()})");
        }
        catch (\Exception $e) {
            Log::error("IMPORT ERROR: ".$e->getMessage());
            return back()->with('error', "Terjadi kesalahan import: " . $e->getMessage());
        }
    }

    /**
     * Helper untuk menghapus cache dashboard spesifik
     */
    private function clearDashboardCache($roleName, $periodRaw)
    {
        // Format period ke Y-m-d untuk akurasi (sesuai resolveCurrentPeriod)
        // Namun di cache key index kita pakai hasil resolve.
        // Untuk aman, kita clear cache statusCounts yang spesifik.
        
        // Kita perlu period yang sudah di format Y-m-01
        $periodResolved = date('Y-m-01', strtotime($periodRaw));
        
        Cache::forget("gizi_stats_{$roleName}_{$periodResolved}");
        
        // Opsional: Clear cache periode jika ada kemungkinan periode baru bertambah
        Cache::forget('gizi_periods_' . $roleName);
    }
}