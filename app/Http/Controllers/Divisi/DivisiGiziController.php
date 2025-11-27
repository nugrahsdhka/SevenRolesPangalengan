<?php

namespace App\Http\Controllers\Divisi;

use App\Models\DataGizi;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;
use App\Imports\DataGiziFileImport;
use Illuminate\Support\Facades\Log;

// Excel
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;

class DivisiGiziController extends Controller
{
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

    private function resolveCurrentPeriod(Request $request, string $roleName): string
    {
        // 1. Ambil dari query / input
        $requested = $request->query('period') ?? $request->input('period'); // Y-m atau Y-m-d
        $sessionKey = 'gizi_period_' . $roleName;

        // 2. Daftar periode yang ada di DB untuk role ini (Y-m-01)
        $available = $this->getAvailablePeriodsForRole($roleName); // array of 'Y-m-01'

        // 3. Normalisasi prioritas → hasil akhir: $periodYm (Y-m)
        if ($requested) {
            // kalau user kirim 'Y-m', tambahkan '-01'
            if (preg_match('/^\d{4}-\d{2}$/', $requested)) {
                $periodYm = $requested;
            } else {
                $periodYm = date('Y-m', strtotime($requested));
            }
        } elseif (session()->has($sessionKey)) {
            $periodYm = session($sessionKey);
        } elseif (!empty($available)) {
            // pakai periode terbaru di DB
            $periodYm = date('Y-m', strtotime($available[0]));
        } else {
            // kalau sama sekali belum ada data → pakai bulan sekarang
            $periodYm = now()->format('Y-m');
        }

        // 4. Simpan ke session supaya konsisten saat reload
        session([$sessionKey => $periodYm]);

        // 5. Return format 'Y-m-01' (dipakai untuk whereDate('period', ...))
        return $periodYm . '-01';
    }

    public function index(Request $request)
    {
        $currentUser   = Auth::user();
        $userRoleName  = $currentUser->roles->first()->name;

        // Section dari sidebar: 'tambah', 'data', 'detail'
        $view = $request->get('view', 'data');

        // Inisialisasi variabel tambahan untuk view detail
        $desaList     = collect();
        $selectedDesa = null;
        $dataDetail   = collect();
        $detailStat   = null;

        // ================== PERIODE ==================
        $availablePeriods = $this->getAvailablePeriodsForRole($userRoleName);  // array 'Y-m-01'
        $currentPeriod    = $this->resolveCurrentPeriod($request, $userRoleName); // 'Y-m-01'
        $periodYm         = date('Y-m', strtotime($currentPeriod));             // untuk dipakai di route/Blade kalau perlu

        // ================== STATUS TAB ==================
        $status = $request->get('status', 'pending');
        if (! in_array($status, ['pending', 'approved', 'published'])) {
            $status = 'pending';
        }

        // Query base: filter role & periode
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

        $aggregatedData = $base->clone()
            ->where('status', $status)
            ->select(
                'desa_kelurahan',
                'status',
                DB::raw('COUNT(*) as total_anak'),
                DB::raw('MAX(created_at) as tanggal_input_terbaru'),

                // Stunting (TB/U)
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
            ->paginate(50)
            ->onEachSide(1);

        if ($view === 'detail') {
            // Daftar desa di periode & role ini
            $desaList = $base->clone()
                ->select('desa_kelurahan')
                ->distinct()
                ->orderBy('desa_kelurahan')
                ->pluck('desa_kelurahan');

            // Desa yang dipilih (dari query ?desa=...), fallback ke pertama
            $selectedDesa = $request->get('desa');
            if (!$selectedDesa && $desaList->count() > 0) {
                $selectedDesa = $desaList->first();
            }

            if ($selectedDesa) {
                $detailBase = $base->clone()
                    ->where('desa_kelurahan', strtoupper($selectedDesa));

                // Data individu (paginate)
                $dataDetail = $detailBase->clone()
                    ->orderBy('created_at', 'desc')
                    ->paginate(50)
                    ->onEachSide(1)
                    ->appends([
                        'view'   => 'detail',
                        'desa'   => $selectedDesa,
                        'period' => $currentPeriod ? date('Y-m', strtotime($currentPeriod)) : null,
                    ]);
                    

                // Statistik ringkas
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
            'period'   => ['required', 'regex:/^\d{4}-\d{2}(-\d{2})?$/'],
            'files'    => ['required', 'array'],
            'files.*'  => 'file|mimes:xlsx,xls,csv|max:10240', // 10MB per file
        ]);

        // Normalisasi period jadi Y-m-d (tanggal 1)
        $period = preg_match('/^\d{4}-\d{2}$/', $request->period)
            ? ($request->period . '-01')
            : $request->period;

        $periodYm     = date('Y-m', strtotime($period));
        $userRoleName = Auth::user()->roles->first()->name;

        // Cek: apakah SUDAH ada data PUBLISHED di periode ini?
        $periodHasData = DataGizi::whereDate('period', $period)
            ->whereIn('status', ['published'])
            ->exists();

        if ($periodHasData) {
            return back()
                ->with('error', 'Import ditolak: data untuk periode ini sudah berstatus "published". Hapus atau batalkan publikasi terlebih dahulu.')
                ->withInput();
        }

        try {
            $files = $request->file('files');

            foreach ($files as $file) {
                // 🔴 DULU: Excel::import(new DataGiziImport($period, Auth::id()), $file);
                // ✅ SEKARANG: pakai wrapper yang hanya proses Sheet1
                Excel::import(
                    new DataGiziFileImport($period, Auth::id()),
                    $file
                );
            }

            return redirect()->route('gizi.index', ['period' => $periodYm])
                ->with('success', 'Data Gizi berhasil di-import.');

        } catch (\Maatwebsite\Excel\Validators\ValidationException $e) {
            $failures     = $e->failures();
            $firstFailure = $failures[0];

            $errorMessage = "Gagal import: "
                . $firstFailure->errors()[0]
                . " (baris " . $firstFailure->row()
                . ", kolom " . $firstFailure->attribute()
                . "). Pastikan format sesuai.";

            return redirect()->route('gizi.index', ['period' => $periodYm])
                ->with('error', $errorMessage);

        } catch (\Exception $e) {
            Log::error('Error importing Excel: ' . $e->getMessage());

            return redirect()->route('gizi.index', ['period' => $periodYm])
                ->with('error', 'Terjadi kesalahan saat import data. Pastikan format file benar dan coba lagi.');
        }
    }

    public function detail(Request $request, $desa)
    {
        $currentUser   = Auth::user();
        $userRoleName  = $currentUser->roles->first()->name;
        $desaKelurahan = urldecode($desa);

        // ================== PERIODE DETAIL ==================
        $availablePeriods = $this->getAvailablePeriodsForRole($userRoleName);      // 'Y-m-01'
        $currentPeriod    = $this->resolveCurrentPeriod($request, $userRoleName);  // 'Y-m-01'
        $periodYm         = date('Y-m', strtotime($currentPeriod));


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

        return view('divisi.gizi.detail', compact(
            'dataGizi',
            'desaKelurahan',
            'statistik',
            'availablePeriods',
            'currentPeriod',
            'periodYm',
        ));
    }
}