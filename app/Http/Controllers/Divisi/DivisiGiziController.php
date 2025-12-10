<?php

namespace App\Http\Controllers\Divisi;

use App\Models\DataGizi;
use Illuminate\Http\Request;
use App\Imports\DataGiziImport;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;
use App\Imports\DataGiziFileImport;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;
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
                DB::raw('SUM(zs_bb_tb) as sum_zs_bbtb'),
                DB::raw("
                    (
                        SELECT dg2.user_id
                        FROM data_gizis dg2
                        WHERE dg2.desa_kelurahan = data_gizis.desa_kelurahan
                        AND dg2.status = data_gizis.status
                        AND DATE(dg2.period) = '" . $currentPeriod . "'
                        ORDER BY dg2.created_at DESC
                        LIMIT 1
                    ) as user_id
                ")
            )
            ->groupBy('desa_kelurahan', 'status')
            ->orderBy('tanggal_input_terbaru', 'desc')
            ->paginate(50)
            ->onEachSide(1);

        foreach ($aggregatedData as $row) {
            if (!empty($row->user_id)) {
                $user = \App\Models\User::find($row->user_id);
                if ($user) {
                    $row->setRelation('user', $user);
                }
            }
        }



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

        try {
            foreach ($request->file('files') as $file) {
                Excel::import(
                    new DataGiziFileImport($period, Auth::id()),
                    $file
                );
            }

            return redirect()
                ->route('gizi.index', ['period' => $periodYm])
                ->with('success', 'Import berhasil.');
        } catch (\Maatwebsite\Excel\Validators\ValidationException $e) {
            $f = $e->failures()[0];

            return back()->with(
                'error',
                "Kesalahan validasi: {$f->errors()[0]} (baris {$f->row()}, kolom {$f->attribute()})"
            );
        } catch (\Exception $e) {
            Log::error("IMPORT ERROR: " . $e->getMessage());

            return back()->with('error', "Terjadi kesalahan import: " . $e->getMessage());
        }
    }

    public function destroyAggregated(Request $request)
    {
        // 1. Validasi input
        $request->validate([
            'period'         => ['required', 'regex:/^\d{4}-\d{2}(-\d{2})?$/'],
            'desa_kelurahan' => 'required|string|max:150',
            'status'         => ['required', Rule::in(['pending', 'approved', 'published'])],
        ]);

        $currentUser  = Auth::user();
        $userRoleName = $currentUser->roles->first()->name;

        // 2. Normalisasi period -> selalu YYYY-MM-01
        $period = preg_match('/^\d{4}-\d{2}$/', $request->period)
            ? ($request->period . '-01')
            : $request->period;

        $periodDate  = date('Y-m-01', strtotime($period));   // buat whereDate
        $periodParam = date('Y-m', strtotime($periodDate));  // buat redirect

        // 3. Standarisasi desa & status
        $desa   = strtoupper(trim($request->desa_kelurahan));
        $status = $request->status;

        // 4. PROTEKSI: kalau dia mencoba hapus approved/published -> tolak
        if (in_array($status, ['approved', 'published'])) {
            return back()->with(
                'error',
                'Data dengan status Approved/Published tidak boleh dihapus secara agregat.'
            );
        }

        // 5. Hapus hanya data PENDING untuk desa + periode + role ini
        $deleted = DataGizi::where('desa_kelurahan', $desa)
            ->whereDate('period', $periodDate)
            ->where('status', 'pending') // paksa pending, lebih aman
            ->whereHas('user.roles', fn($q) => $q->where('name', $userRoleName))
            ->delete();

        if ($deleted === 0) {
            return back()->with(
                'error',
                'Tidak ada data pending yang dihapus untuk desa dan periode tersebut.'
            );
        }

        return redirect()
            ->route('gizi.index', ['period' => $periodParam])
            ->with(
                'success',
                "Semua data PENDING ({$deleted} baris) untuk desa {$desa} pada periode ini berhasil dihapus."
            );
    }
}
