<?php

namespace App\Http\Controllers\Divisi;

use App\Models\DataGizi;
use App\Models\ImportJob;
use Illuminate\Http\Request;
use App\Imports\DataGiziImport;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;
use App\Imports\DataGiziFileImport;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

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
        $requested = $request->query('period') ?? $request->input('period');
        $sessionKey = 'gizi_period_' . $roleName;

        $available = $this->getAvailablePeriodsForRole($roleName);

        if ($requested) {
            if (preg_match('/^\d{4}-\d{2}$/', $requested)) {
                $periodYm = $requested;
            } else {
                $periodYm = date('Y-m', strtotime($requested));
            }
        } elseif (session()->has($sessionKey)) {
            $periodYm = session($sessionKey);
        } elseif (! empty($available)) {
            $periodYm = date('Y-m', strtotime($available[0]));
        } else {
            $periodYm = now()->format('Y-m');
        }

        session([$sessionKey => $periodYm]);

        return $periodYm . '-01';
    }

    public function index(Request $request)
    {
        $currentUser  = Auth::user();
        $userRoleName = $currentUser->roles->first()->name;

        $view = $request->get('view', 'data');

        $desaList     = collect();
        $selectedDesa = null;
        $dataDetail   = collect();
        $detailStat   = null;

        $availablePeriods = $this->getAvailablePeriodsForRole($userRoleName);
        $currentPeriod    = $this->resolveCurrentPeriod($request, $userRoleName);
        $periodYm         = date('Y-m', strtotime($currentPeriod));

        $status = $request->get('status', 'pending');
        if (! in_array($status, ['pending', 'approved', 'published'])) {
            $status = 'pending';
        }

        $base = DataGizi::whereHas('user.roles', fn($q) => $q->where('name', $userRoleName))
            ->whereDate('period', $currentPeriod);

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
                DB::raw('SUM(CASE WHEN zs_tb_u < -2 THEN 1 ELSE 0 END) as total_kasus_stunting'),
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
            $desaList = $base->clone()
                ->select('desa_kelurahan')
                ->distinct()
                ->orderBy('desa_kelurahan')
                ->pluck('desa_kelurahan');

            $selectedDesa = $request->get('desa') ?? $desaList->first();

            if ($selectedDesa) {
                $detailBase = $base->clone()
                    ->where('desa_kelurahan', strtoupper($selectedDesa));

                $dataDetail = $detailBase->clone()
                    ->orderBy('created_at', 'desc')
                    ->paginate(50)
                    ->onEachSide(1)
                    ->appends([
                        'view'   => 'detail',
                        'desa'   => $selectedDesa,
                        'period' => date('Y-m', strtotime($currentPeriod)),
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
        $request->validate([
            'period'         => ['required', 'regex:/^\d{4}-\d{2}(-\d{2})?$/'],
            'desa_kelurahan' => 'required|string|max:150',
            // sekarang berat & tinggi boleh null
            'berat'          => 'nullable|numeric',
            'tinggi'         => 'nullable|numeric',
        ]);

        $period      = date('Y-m-01', strtotime($request->period));
        $periodParam = date('Y-m', strtotime($period));
        $desa        = strtoupper(trim($request->desa_kelurahan));
        $userRoleName = Auth::user()->roles->first()->name;

        $isEntryLocked = DataGizi::where('desa_kelurahan', $desa)
            ->whereDate('period', $period)
            ->whereIn('status', ['approved', 'published'])
            ->whereHas('user.roles', fn($q) => $q->where('name', $userRoleName))
            ->exists();

        if ($isEntryLocked) {
            return back()->with('error', 'Data desa ini sudah di-Approve/Published.');
        }

        $data            = $request->all();
        $data['user_id'] = Auth::id();

        DataGizi::create($data);

        return redirect()->route('gizi.index', ['period' => $periodParam])
            ->with('success', 'Data berhasil ditambahkan.');
    }

    public function update(Request $request, DataGizi $data_gizi)
    {
        $request->validate([
            'desa_kelurahan' => 'required|string|max:150',
            'berat'          => 'nullable|numeric',
            'tinggi'         => 'nullable|numeric',
        ]);

        $data_gizi->update($request->all());

        return back()->with('success', 'Data berhasil diperbarui.');
    }

    public function import(Request $request)
    {
        $request->validate([
            'period'  => ['required', 'regex:/^\d{4}-\d{2}(-\d{2})?$/'],
            'files'   => ['required', 'array'],
            'files.*' => 'file|mimes:xlsx,xls,csv|max:10240',
        ]);

        $period = preg_match('/^\d{4}-\d{2}$/', $request->period)
            ? ($request->period . '-01')
            : $request->period;

        $periodYm = date('Y-m', strtotime($period));
        $userId   = Auth::id();

        // untuk sekarang: pakai file pertama saja
        $file = $request->file('files')[0];

        // 1) simpan file ke storage
        $storedPath = $file->store('imports/data_gizi');

        // 2) hitung total baris data (tanpa header)
        $collection = Excel::toCollection(
            new class implements ToCollection, WithHeadingRow {
                public function collection(\Illuminate\Support\Collection $rows) {}
            },
            $file
        );

        $firstSheet = $collection->first();
        $totalRows  = $firstSheet ? $firstSheet->count() : 0; // sudah tanpa header karena WithHeadingRow

        // 3) buat ImportJob
        $importJob = ImportJob::create([
            'user_id'        => $userId,
            'type'           => 'data_gizi',
            'period'         => $periodYm,
            'file_path'      => $storedPath,
            'total_rows'     => $totalRows,
            'processed_rows' => 0,
            'status'         => 'queued',
        ]);

        try {
            // 4) queue import
            Excel::queueImport(
                new DataGiziImport($period, $userId, $importJob),
                Storage::path($storedPath)
            )->chain([
                function () use ($importJob) {
                    $importJob->update(['status' => 'success']);
                }
            ]);

            $importJob->update(['status' => 'running']);
        } catch (\Throwable $e) {
            Log::error('IMPORT QUEUE ERROR: ' . $e->getMessage());

            $importJob->update([
                'status'        => 'failed',
                'error_message' => $e->getMessage(),
            ]);

            return back()->with('error', 'Gagal mengantrikan import: ' . $e->getMessage());
        }

        // 5) redirect ke halaman progress
        return redirect()
            ->route('gizi.import.progress', $importJob->id);
    }

    public function importProgress(ImportJob $job)
    {
        // bisa tambahin authorize kalau perlu
        return view('divisi.gizi.import-progress', compact('job'));
    }

    public function importStatus(ImportJob $job)
    {
        $total     = max(1, $job->total_rows); // hindari divide by zero
        $processed = min($job->processed_rows, $total);
        $percent   = round($processed / $total * 100);

        return response()->json([
            'status'         => $job->status,
            'total_rows'     => $total,
            'processed_rows' => $processed,
            'percent'        => $percent,
            'error_message'  => $job->error_message,
        ]);
    }
}
