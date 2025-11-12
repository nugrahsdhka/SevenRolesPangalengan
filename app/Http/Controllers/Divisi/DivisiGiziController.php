<?php

namespace App\Http\Controllers\Divisi;

use App\Http\Controllers\Controller;
use App\Models\DataGizi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;

// Excel
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\DataGiziImport;

class DivisiGiziController extends Controller
{
    /**
     * Helper: daftar periode tersedia untuk role user (desc)
     */
    private function getAvailablePeriodsForRole(string $roleName)
    {
        return DataGizi::whereHas('user.roles', fn($q) => $q->where('name', $roleName))
            ->selectRaw('DATE_FORMAT(period, "%Y-%m-01") as p')
            ->distinct()
            ->orderBy('p', 'desc')
            ->pluck('p')
            ->map(fn($d) => date('Y-m-01', strtotime($d)))
            ->toArray();
    }

    /**
     * Helper: resolve current period (request ? latest)
     */
    private function resolveCurrentPeriod(Request $request, string $roleName): ?string
    {
        $requested = $request->query('period') ?? $request->input('period');
        if ($requested) {
            // normalisasi ke Y-m-01
            $req = preg_match('/^\d{4}-\d{2}$/', $requested) ? ($requested . '-01') : $requested;
            return date('Y-m-01', strtotime($req));
        }

        $list = $this->getAvailablePeriodsForRole($roleName);
        return $list[0] ?? null; // latest or null
    }

    public function index(Request $request)
    {
        $currentUser = Auth::user();
        $userRoleName = $currentUser->roles->first()->name;

        // --- Period (asumsi kamu sudah punya $currentPeriod & $availablePeriods) ---
        $periodYm = $request->get('period'); // 'YYYY-MM'
        
        // Resolve current period: from request, or latest available, or current month
        $availablePeriods = $this->getAvailablePeriodsForRole($userRoleName);
        $currentPeriod = $this->resolveCurrentPeriod($request, $userRoleName);
        
        if (!$currentPeriod) {
            $currentPeriod = $periodYm ? ($periodYm.'-01') : now()->startOfMonth()->toDateString();
            if (!in_array($currentPeriod, $availablePeriods)) {
                $availablePeriods = array_merge([$currentPeriod], $availablePeriods);
                rsort($availablePeriods);
            }
        }

        // --- Status tab ---
        $status = $request->get('status', 'pending');
        if (!in_array($status, ['pending','approved','published'])) {
            $status = 'pending';
        }

        $base = DataGizi::whereHas('user.roles', function ($q) use ($userRoleName) {
                            $q->where('name', $userRoleName);
                        })
                        ->whereDate('period', $currentPeriod);

        // Badge count per status (distinct desa pada periode ini)
        $statusCounts = $base->clone()
            ->select('status', DB::raw('COUNT(DISTINCT desa_kelurahan) as desa_count'))
            ->groupBy('status')
            ->pluck('desa_count', 'status')
            ->toArray();

        // ===================================================================
        // KODE YANG DIPERBARUI: Query Agregasi
        // ===================================================================
        $aggregatedData = $base->clone()
            ->where('status', $status)
            ->select(
                'desa_kelurahan',
                'status',
                DB::raw('COUNT(*) as total_anak'),
                DB::raw('MAX(created_at) as tanggal_input_terbaru'),

                // Stunting (TB/U) - Anda masih butuh ini untuk kolom "Kasus Stunting" jika ada
                DB::raw('SUM(CASE WHEN zs_tb_u < -2 THEN 1 ELSE 0 END) as total_kasus_stunting'),
                
                // Data baru untuk rata-rata (SUM)
                DB::raw('SUM(berat) as sum_berat'),
                DB::raw('SUM(tinggi) as sum_tinggi'),
                DB::raw('SUM(zs_bb_u) as sum_zs_bbu'),
                DB::raw('SUM(zs_tb_u) as sum_zs_tbu'),
                DB::raw('SUM(zs_bb_tb) as sum_zs_bbtb')
            )
            ->groupBy('desa_kelurahan', 'status')
            ->orderBy('tanggal_input_terbaru', 'desc')
            ->paginate(15);
        // ===================================================================
        // AKHIR KODE YANG DIPERBARUI
        // ===================================================================

        // Kumpulkan daftar periode yang tersedia (DI-UPDATE untuk selalu menyertakan $availablePeriods dari helper)
        // $availablePeriods sudah di-fetch di atas

        return view('divisi.gizi.index', compact(
            'aggregatedData',
            'currentPeriod',
            'availablePeriods',
            'status',          // <— buat tab aktif
            'statusCounts'     // <— buat badge
        ));
    }

    public function create(Request $request)
    {
        // Wajib periode
        $request->validate([
            'period' => ['required', 'regex:/^\d{4}-\d{2}(-\d{2})?$/'],
        ]);
        $period = preg_match('/^\d{4}-\d{2}$/', $request->period) ? ($request->period . '-01') : $request->period;
        return view('divisi.gizi.create', ['period' => date('Y-m-01', strtotime($period))]);
    }

    public function store(Request $request)
    {
        // Wajib periode + field lain
        $request->validate([
            'period' => ['required','regex:/^\d{4}-\d{2}(-\d{2})?$/'],
            'desa_kelurahan' => 'required|string|max:100',
            'berat' => 'required|numeric|min:0|max:999.99',
            'tinggi' => 'required|numeric|min:0|max:999.99',
            'status_bb_u' => 'nullable|string|max:50',
            'zs_bb_u' => 'nullable|numeric|min:-99.99|max:99.99',
            'status_tb_u' => 'nullable|string|max:50',
            'zs_tb_u' => 'nullable|numeric|min:-99.99|max:99.99',
            'status_bb_tb' => 'nullable|string|max:50',
            'zs_bb_tb' => 'nullable|numeric|min:-99.99|max:99.99',
        ]);

        $period = date('Y-m-01', strtotime($request->period));
        $periodParam = date('Y-m', strtotime($period));
        $desa = strtoupper(trim($request->desa_kelurahan));
        $userRoleName = Auth::user()->roles->first()->name;

        // ===================================================================
        // CEK BARU: Blokir 'create' jika desa/periode ini sudah 'locked'
        // ===================================================================
        $isEntryLocked = DataGizi::where('desa_kelurahan', $desa)
            ->whereDate('period', $period)
            ->whereIn('status', ['approved', 'published'])
            ->whereHas('user.roles', fn($q) => $q->where('name', $userRoleName))
            ->exists();

        if ($isEntryLocked) {
            return redirect()->route('gizi.create', ['period' => $periodParam])
                ->withInput()
                ->with('error', "Gagal: Data untuk desa {$desa} pada periode " . date('F Y', strtotime($period)) . " sudah di-Approve atau di-Publish. Data baru tidak dapat ditambahkan.");
        }
        // ===================================================================
        // END CEK BARU
        // ===================================================================

        $data = $request->all();
        $data['user_id'] = Auth::id();
        // normalisasi period akan ditangani mutator
        DataGizi::create($data);

        return redirect()->route('gizi.index', ['period' => $periodParam])
            ->with('success', 'Data Gizi berhasil ditambahkan.');
    }

    public function edit(Request $request, DataGizi $data_gizi)
    {
        $currentUserRoleName = Auth::user()->roles->first()->name;
        if (!$data_gizi->user->hasRole($currentUserRoleName)) {
            abort(403, 'AKSES DITOLAK: Anda hanya bisa mengelola data dari divisi Anda sendiri.');
        }

        // Cek tambahan: jangan izinkan edit jika status sudah locked
        if (in_array($data_gizi->status, ['approved', 'published'])) {
             return redirect()->back()
                ->with('error', 'Data yang sudah di-Approve atau di-Publish tidak dapat di-edit.');
        }

        return view('divisi.gizi.edit', ['data' => $data_gizi]);
    }

    public function update(Request $request, DataGizi $data_gizi)
    {
        $currentUserRoleName = Auth::user()->roles->first()->name;
        if (!$data_gizi->user->hasRole($currentUserRoleName)) {
            abort(403, 'AKSES DITOLAK: Anda hanya bisa mengelola data dari divisi Anda sendiri.');
        }

        // Cek tambahan: jangan izinkan update jika status sudah locked
        if (in_array($data_gizi->status, ['approved', 'published'])) {
             return redirect()->route('gizi.detail', [
                    'desa' => $data_gizi->desa_kelurahan,
                    'period' => date('Y-m', strtotime($data_gizi->period))
                ])->with('error', 'Data yang sudah di-Approve atau di-Publish tidak dapat di-update.');
        }

        $request->validate([
            'period' => ['required','regex:/^\d{4}-\d{2}(-\d{2})?$/'],
            'desa_kelurahan' => 'required|string|max:100',
            'berat' => 'required|numeric|min:0|max:999.99',
            'tinggi' => 'required|numeric|min:0|max:999.99',
            'status_bb_u' => 'nullable|string|max:50',
            'zs_bb_u' => 'nullable|numeric|min:-99.99|max:99.99',
            'status_tb_u' => 'nullable|string|max:50',
            'zs_tb_u' => 'nullable|numeric|min:-99.99|max:99.99',
            'status_bb_tb' => 'nullable|string|max:50',
            'zs_bb_tb' => 'nullable|numeric|min:-99.99|max:99.99',
        ]);

        $data_gizi->update($request->all());

        $periodParam = date('Y-m', strtotime($data_gizi->period));
        return redirect()->route('gizi.detail', [
                'desa' => $data_gizi->desa_kelurahan,
                'period' => $periodParam
            ])->with('success', 'Data Gizi individual berhasil diperbarui.');
    }

    public function destroy(DataGizi $data_gizi)
    {
        $currentUserRoleName = Auth::user()->roles->first()->name;
        if (!$data_gizi->user->hasRole($currentUserRoleName)) {
            abort(403, 'AKSES DITOLAK: Anda hanya bisa mengelola data dari divisi Anda sendiri.');
        }

        // Cek tambahan: jangan izinkan hapus jika status sudah locked
         if (in_array($data_gizi->status, ['approved', 'published'])) {
             return redirect()->back()
                ->with('error', 'Data yang sudah di-Approve atau di-Publish tidak dapat dihapus.');
        }

        $desaKelurahan = $data_gizi->desa_kelurahan;
        $periodParam = date('Y-m', strtotime($data_gizi->period));

        $data_gizi->delete();

        $remainingData = DataGizi::where('desa_kelurahan', $desaKelurahan)
            ->whereDate('period', '=', $data_gizi->period)
            ->whereHas('user.roles', fn($q) => $q->where('name', $currentUserRoleName))
            ->exists();

        if (!$remainingData) {
            return redirect()->route('gizi.index', ['period' => $periodParam])
                ->with('success', 'Data Gizi terakhir untuk desa '.$desaKelurahan.' pada periode ini berhasil dihapus.');
        } else {
            return redirect()->route('gizi.detail', ['desa' => $desaKelurahan, 'period' => $periodParam])
                ->with('success', 'Data Gizi individual berhasil dihapus.');
        }
    }

    public function destroyAggregated(Request $request)
    {
        $request->validate([
            'desa_kelurahan' => 'required|string',
            'status' => ['required', Rule::in(['pending', 'approved', 'published'])],
            'period' => ['required','regex:/^\d{4}-\d{2}(-\d{2})?$/'],
        ]);

        // Cek tambahan: jangan izinkan hapus jika status sudah locked
        if (in_array($request->status, ['approved', 'published'])) {
             return redirect()->route('gizi.index', ['period' => $request->period])
                ->with('error', 'Data yang sudah di-Approve atau di-Publish tidak dapat dihapus.');
        }

        $currentUser = Auth::user();
        $userRoleName = $currentUser->roles->first()->name;
        $desaKelurahan = strtoupper(trim($request->desa_kelurahan));
        $status = $request->status;
        $period = preg_match('/^\d{4}-\d{2}$/', $request->period) ? ($request->period.'-01') : $request->period;

        try {
            $deletedCount = DataGizi::where('desa_kelurahan', $desaKelurahan)
                ->where('status', $status)
                ->whereDate('period', '=', date('Y-m-01', strtotime($period)))
                ->whereHas('user.roles', fn($q) => $q->where('name', $userRoleName))
                ->delete();

            if ($deletedCount > 0) {
                return redirect()->route('gizi.index', ['period' => date('Y-m', strtotime($period))])
                    ->with('success', "{$deletedCount} data Gizi (status '{$status}') untuk desa {$desaKelurahan} pada periode ini berhasil dihapus.");
            } else {
                return redirect()->route('gizi.index', ['period' => date('Y-m', strtotime($period))])
                    ->with('error', "Tidak ada data yang dapat dihapus (desa {$desaKelurahan}, status '{$status}') pada periode ini untuk divisi Anda.");
            }

        } catch (\Exception $e) {
            Log::error('Error deleting aggregated gizi data: ' . $e->getMessage());
            return redirect()->route('gizi.index', ['period' => date('Y-m', strtotime($period))])
                ->with('error', 'Terjadi kesalahan saat menghapus data. Silakan coba lagi.');
        }
    }

    public function import(Request $request)
    {
        $request->validate([
            'period' => ['required','regex:/^\d{4}-\d{2}(-\d{2})?$/'],
            'file' => 'required|mimes:xlsx,xls,csv|max:2048',
        ]);

        $period = preg_match('/^\d{4}-\d{2}$/', $request->period) ? ($request->period.'-01') : $request->period;
        $periodYm = date('Y-m', strtotime($period));
        $userRoleName = Auth::user()->roles->first()->name;

        // ===================================================================
        // CEK BARU: Blokir 'import' jika periode ini sudah 'locked'
        // ===================================================================
        $isPeriodLocked = DataGizi::whereDate('period', $period)
            ->whereIn('status', ['approved', 'published'])
            ->whereHas('user.roles', fn($q) => $q->where('name', $userRoleName))
            ->exists();

        if ($isPeriodLocked) {
            return redirect()->route('gizi.index', ['period' => $periodYm])
                ->with('error', "Gagal Import: Periode " . date('F Y', strtotime($period)) . " sudah memiliki data yang di-Approve atau di-Publish. Import ditutup untuk periode ini.");
        }
        // ===================================================================
        // END CEK BARU
        // ===================================================================

        try {
            $file = $request->file('file');

            // Lewatkan period ke import agar setiap row diset ke period ini (jika file tidak punya kolom period)
            Excel::import(new DataGiziImport($period, Auth::id()), $file);

            return redirect()->route('gizi.index', ['period' => date('Y-m', strtotime($period))])
                ->with('success', 'Data Gizi berhasil di-import dari Excel.');

        } catch (\Maatwebsite\Excel\Validators\ValidationException $e) {
            $failures = $e->failures();
            $firstFailure = $failures[0];
            $errorMessage = "Gagal import: "
                . $firstFailure->errors()[0]
                . " (baris " . $firstFailure->row() . ", kolom " . $firstFailure->attribute() . "). Pastikan format sesuai.";
            return redirect()->route('gizi.index', ['period' => $request->period])->with('error', $errorMessage);

        } catch (\Exception $e) {
            Log::error('Error importing Excel: ' . $e->getMessage());
            return redirect()->route('gizi.index', ['period' => $request->period])
                ->with('error', 'Terjadi kesalahan saat import data. Pastikan format file benar dan coba lagi.');
        }
    }

    public function detail(Request $request, $desa)
    {
        $currentUser = Auth::user();
        $userRoleName = $currentUser->roles->first()->name;
        $desaKelurahan = urldecode($desa);

        // Periode aktif untuk detail
        $availablePeriods = $this->getAvailablePeriodsForRole($userRoleName);
        $currentPeriod = $this->resolveCurrentPeriod($request, $userRoleName);

        $base = DataGizi::whereHas('user.roles', fn($q) => $q->where('name', $userRoleName))
            ->where('desa_kelurahan', strtoupper($desaKelurahan));

        if ($currentPeriod) {
            $base->whereDate('period', '=', $currentPeriod);
        }

        $dataGizi = $base->clone()
            ->orderBy('created_at', 'desc')
            ->paginate(20)
            ->appends([
                'period' => $currentPeriod ? date('Y-m', strtotime($currentPeriod)) : null
            ]);

        if ($dataGizi->isEmpty() && $dataGizi->currentPage() > 1) {
            return redirect()->route('gizi.detail', ['desa' => $desa, 'period' => date('Y-m', strtotime($currentPeriod ?? now()))]);
        }

        // Jangan redirect jika tidak ada data, biarkan view yang menampilkan 'data tidak ada'
        // if ($dataGizi->total() === 0) {
        //     return redirect()->route('gizi.index', ['period' => $currentPeriod ? date('Y-m', strtotime($currentPeriod)) : null])
        //         ->with('error', 'Tidak ada data Gizi untuk desa '.$desaKelurahan.' pada periode ini.');
        // }

        $statistik = $base->clone()
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

        return view('divisi.gizi.detail', compact('dataGizi', 'desaKelurahan', 'statistik', 'availablePeriods', 'currentPeriod'));
    }
}