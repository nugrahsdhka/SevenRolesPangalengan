<?php

namespace App\Http\Controllers\Admin;

use Carbon\Carbon;
use App\Models\DataDesa;
use App\Models\DataGizi;
use Illuminate\Http\Request;
use App\Models\DataPencegahan;
use App\Models\DataPenanggulangan;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class AdminController extends Controller
{
    public function publishedData(Request $request)
    {
        $perPage = 20;

        $availablePeriods = DataGizi::query()
            ->selectRaw('DATE_FORMAT(period, "%Y-%m-01") as period_start')
            ->whereNotNull('period')
            ->distinct()
            ->orderByDesc('period_start')
            ->pluck('period_start'); // collection 'Y-m-d'

        $requestedPeriod = $request->input('period');           // dari query string
        $sessionPeriod   = session('admin_published_period');   // terakhir dipakai

        if ($availablePeriods->isNotEmpty()) {
            $latestPeriodYm = Carbon::parse($availablePeriods->first())->format('Y-m');
        } else {
            $latestPeriodYm = now()->format('Y-m');
        }

        if ($requestedPeriod) {
            $periodYm = $requestedPeriod;
        } elseif ($sessionPeriod) {
            $periodYm = $sessionPeriod;
        } else {
            $periodYm = $availablePeriods->isNotEmpty()
                ? $latestPeriodYm
                : now()->format('Y-m');
        }

        // Simpan ke session biar konsisten saat pindah tab / reload
        session(['admin_published_period' => $periodYm]);

        // Bangun Carbon untuk tahun/bulan
        try {
            $currentPeriod = Carbon::createFromFormat('Y-m', $periodYm)->startOfMonth();
        } catch (\Exception $e) {
            // fallback kalau periodYm ngaco → pakai periode terbaru di DB atau now
            if ($availablePeriods->isNotEmpty()) {
                $currentPeriod = Carbon::parse($availablePeriods->first())->startOfMonth();
                $periodYm = $currentPeriod->format('Y-m');
                session(['admin_published_period' => $periodYm]);
            } else {
                $currentPeriod = now()->startOfMonth();
                $periodYm = $currentPeriod->format('Y-m');
                session(['admin_published_period' => $periodYm]);
            }
        }

        // ================== STATUS TAB (pending / approved / published) ==================
        $allowedStatuses = ['pending', 'approved', 'published'];
        $status = $request->input('status', 'approved');

        if (! in_array($status, $allowedStatuses)) {
            $status = 'approved';
        }

        // ================== STATUS COUNTS PER PERIODE ==================
        $statusCountsRaw = DataGizi::query()
            ->select('status', DB::raw('COUNT(DISTINCT desa_kelurahan) as total'))
            ->whereIn('status', $allowedStatuses)
            ->whereYear('period', $currentPeriod->year)
            ->whereMonth('period', $currentPeriod->month)
            ->groupBy('status')
            ->pluck('total', 'status')
            ->toArray();

        $statusCounts = [
            'pending'   => $statusCountsRaw['pending']   ?? 0,
            'approved'  => $statusCountsRaw['approved']  ?? 0,
            'published' => $statusCountsRaw['published'] ?? 0,
        ];

        // ================== DATA GIZI: AGREGASI PER DESA + FILTER PERIODE & STATUS ==================
        $giziQuery = DataGizi::query()
            ->select(
                'desa_kelurahan',
                'status',
                DB::raw('COUNT(*) as total_anak'),
                DB::raw('SUM(CASE WHEN zs_tb_u < -2 THEN 1 ELSE 0 END) as total_kasus_stunting'),
                DB::raw('SUM(zs_tb_u) as sum_zs_tbu'),
                DB::raw('MAX(created_at) as tanggal_input_terbaru'),
                DB::raw('(
                    SELECT user_id
                    FROM data_gizis dg2
                    WHERE dg2.desa_kelurahan = data_gizis.desa_kelurahan
                    AND dg2.status = data_gizis.status
                    AND YEAR(dg2.period) = ' . $currentPeriod->year . '
                    AND MONTH(dg2.period) = ' . $currentPeriod->month . '
                    ORDER BY created_at DESC
                    LIMIT 1
                ) as user_id')
            )
            ->whereIn('status', $allowedStatuses)
            ->where('status', $status) // TAB: hanya status aktif
            ->whereYear('period', $currentPeriod->year)
            ->whereMonth('period', $currentPeriod->month)
            ->groupBy('desa_kelurahan', 'status')
            ->orderByRaw("FIELD(status, 'pending', 'approved', 'published')")
            ->orderBy('tanggal_input_terbaru', 'desc');

        $allData['gizi'] = $giziQuery->paginate($perPage, ['*'], 'gizi_page');

        // Load user dari user_id hasil subquery
        foreach ($allData['gizi'] as $item) {
            if ($item->user_id) {
                $item->setRelation('user', \App\Models\User::find($item->user_id));
            }
        }

        // ================== DATA DIVISI LAIN ==================
        $allData['pencegahan'] = DataPencegahan::with('user')
            ->whereIn('status', ['approved', 'published', 'pending'])
            ->latest('periode')
            ->paginate($perPage, ['*'], 'pencegahan_page');

        $allData['penanggulangan'] = DataPenanggulangan::with('user')
            ->whereIn('status', ['approved', 'published', 'pending'])
            ->latest('periode')
            ->paginate($perPage, ['*'], 'penanggulangan_page');

        $allData['desa'] = DataDesa::with('user')
            ->whereIn('status', ['approved', 'published', 'pending'])
            ->latest('periode')
            ->paginate($perPage, ['*'], 'desa_page');

        // ================== BULK ACTION FLAGS ==================
        $bulkActionData = [
            'gizi' => [
                'has_pending'   => DataGizi::where('status', 'pending')
                    ->whereYear('period', $currentPeriod->year)
                    ->whereMonth('period', $currentPeriod->month)
                    ->exists(),
                'has_approved'  => DataGizi::where('status', 'approved')
                    ->whereYear('period', $currentPeriod->year)
                    ->whereMonth('period', $currentPeriod->month)
                    ->exists(),
                'has_published' => DataGizi::where('status', 'published')
                    ->whereYear('period', $currentPeriod->year)
                    ->whereMonth('period', $currentPeriod->month)
                    ->exists(),
            ],
            'pencegahan' => [
                'has_approved'  => DataPencegahan::where('status', 'approved')->exists(),
                'has_published' => DataPencegahan::where('status', 'published')->exists(),
            ],
            'penanggulangan' => [
                'has_approved'  => DataPenanggulangan::where('status', 'approved')->exists(),
                'has_published' => DataPenanggulangan::where('status', 'published')->exists(),
            ],
            'desa' => [
                'has_approved'  => DataDesa::where('status', 'approved')->exists(),
                'has_published' => DataDesa::where('status', 'published')->exists(),
            ],
        ];

        $activeTab = $request->input('active_tab', 'gizi');

        return view('admin.published.index', [
            'allData'          => $allData,
            'bulkActionData'   => $bulkActionData,
            'activeTab'        => $activeTab,
            'status'           => $status,
            'statusCounts'     => $statusCounts,
            'periodYm'         => $periodYm,
            'availablePeriods' => $availablePeriods,
        ]);
    }

    public function destroy(Request $request)
    {
        $request->validate([
            'model'      => 'required|string|in:DataGizi,DataPencegahan,DataPenanggulangan,DataDesa',
            'active_tab' => 'required|string',
        ]);

        $modelName = 'App\\Models\\' . $request->model;

        if (!class_exists($modelName)) {
            return back()
                ->with('error', 'Tipe data tidak valid.')
                ->withInput();
        }

        // ===== DataGizi: hapus berdasarkan desa + periode =====
        if ($request->model === 'DataGizi') {
            $request->validate([
                'desa_kelurahan' => 'required|string',
                'period'         => 'nullable|string', // Y-m
            ]);

            $desaKelurahan = $request->desa_kelurahan;
            $periodYm      = $request->input('period');

            $query = $modelName::where('desa_kelurahan', $desaKelurahan);

            if ($periodYm) {
                [$year, $month] = explode('-', $periodYm);
                $query->whereYear('period', $year)->whereMonth('period', $month);
            }

            $deletedCount = $query->delete();

            return back()
                ->with('success', "Semua data desa {$desaKelurahan} pada periode terkait berhasil dihapus ({$deletedCount} data).")
                ->with('active_tab', $request->active_tab);
        }

        // ===== Model lain: hapus per ID =====
        $request->validate([
            'id' => 'required|integer',
        ]);

        $data = $modelName::findOrFail($request->id);
        $data->delete();

        return back()
            ->with('success', 'Data berhasil dihapus secara permanen.')
            ->with('active_tab', $request->active_tab);
    }

    public function showGiziDetail(Request $request, $desaKelurahan)
    {
        $perPage = 100;

        // =========================
        //  Tentukan periode aktif
        // =========================
        $periodYm = $request->input('period');

        if (!$periodYm) {
            // Kalau tidak dikirim di URL, ambil periode TERBARU yang punya data untuk desa ini
            $latestPeriod = DataGizi::where('desa_kelurahan', $desaKelurahan)
                ->max('period');

            if ($latestPeriod) {
                $periodYm = Carbon::parse($latestPeriod)->format('Y-m');
            } else {
                // fallback kalau benar-benar belum ada data
                $periodYm = now()->format('Y-m');
            }
        }

        [$year, $month] = explode('-', $periodYm);
        $currentPeriod = Carbon::createFromDate($year, $month, 1);

        // =========================
        //  Daftar periode untuk dropdown (kalau nanti mau dipakai di Blade)
        // =========================
        $availablePeriods = DataGizi::where('desa_kelurahan', $desaKelurahan)
            ->select(DB::raw('DATE_FORMAT(period, "%Y-%m-01") as period_date'))
            ->distinct()
            ->orderBy('period_date', 'desc')
            ->pluck('period_date');

        // =========================
        //  Statistik per desa & periode
        // =========================
        $statistik = DataGizi::where('desa_kelurahan', $desaKelurahan)
            ->whereYear('period', $year)
            ->whereMonth('period', $month)
            ->select(
                DB::raw('COUNT(*) as total_anak'),
                DB::raw('SUM(CASE WHEN zs_tb_u < -2 THEN 1 ELSE 0 END) as total_stunting'),
                DB::raw('AVG(tinggi) as avg_tinggi'),
                DB::raw('AVG(zs_tb_u) as avg_zs_tbu')
            )
            ->first();

        // =========================
        //  Data individual (hanya Approved & Published) per periode
        // =========================
        $dataGizi = DataGizi::where('desa_kelurahan', $desaKelurahan)
            ->whereYear('period', $year)
            ->whereMonth('period', $month)
            ->whereIn('status', ['pending','approved', 'published'])
            ->orderBy('created_at', 'desc')
            ->paginate($perPage);

        // Pastikan pagination tetap bawa query ?period & ?from
        $dataGizi->appends($request->only('period', 'from'));

        return view('detail.detailGizi', [
            'desaKelurahan'   => $desaKelurahan,
            'statistik'       => $statistik,
            'dataGizi'        => $dataGizi,
            'periodYm'        => $periodYm,
            'currentPeriod'   => $currentPeriod,
            'availablePeriods'=> $availablePeriods,
        ]);
    }

    public function publish(Request $request)
    {
        $request->validate([
            'model'      => 'required|string|in:DataGizi,DataPencegahan,DataPenanggulangan,DataDesa',
            'active_tab' => 'required|string',
        ]);

        $modelName = 'App\\Models\\' . $request->model;
        if (! class_exists($modelName)) {
            return back()->with('error', 'Tipe data tidak valid.');
        }

        // ✅ Logika khusus DataGizi (berdasarkan desa_kelurahan + periode)
        if ($request->model === 'DataGizi') {
            $request->validate([
                'desa_kelurahan' => 'required|string',
                'current_status' => 'required|string|in:approved,published',
                'period'         => 'required|string|regex:/^\d{4}-\d{2}$/', // Y-m
            ]);

            $desaKelurahan = $request->desa_kelurahan;
            $currentStatus = $request->current_status;
            $periodYm      = $request->period;

            // parse periode
            try {
                $period = Carbon::createFromFormat('Y-m', $periodYm)->startOfMonth();
            } catch (\Exception $e) {
                return back()
                    ->with('error', 'Periode tidak valid untuk aksi publish.')
                    ->with('active_tab', $request->active_tab);
            }

            $year  = $period->year;
            $month = $period->month;

            $newStatus   = ($currentStatus === 'approved') ? 'published' : 'approved';
            $actionWord  = ($newStatus === 'published') ? 'di-publish' : 'ditarik';

            try {
                $affectedRows = $modelName::where('desa_kelurahan', $desaKelurahan)
                    ->where('status', $currentStatus)
                    ->whereYear('period', $year)
                    ->whereMonth('period', $month)
                    ->update(['status' => $newStatus]);

                $message = "Data desa {$desaKelurahan} periode {$period->isoFormat('MMMM Y')} berhasil {$actionWord} ({$affectedRows} data).";

                return back()
                    ->with('success', $message)
                    ->with('active_tab', $request->active_tab);

            } catch (\Exception $e) {
                return back()
                    ->with('error', 'Gagal update status: ' . $e->getMessage())
                    ->with('active_tab', $request->active_tab);
            }
        }

        // ✅ Logika untuk model lain (berdasarkan ID)
        $request->validate([
            'id' => 'required|integer',
        ]);

        $data = $modelName::findOrFail($request->id);

        if ($data->status === 'approved') {
            $data->status = 'published';
            $message = 'Data berhasil di-publish ke halaman publik.';
        } elseif ($data->status === 'published') {
            $data->status = 'approved';
            $message = 'Data berhasil ditarik dari halaman publik.';
        } else {
            return back()->with('error', 'Status data tidak valid untuk aksi ini.');
        }

        $data->save();

        return back()
            ->with('success', $message)
            ->with('active_tab', $request->active_tab);
    }

    public function bulkPublish(Request $request)
    {
        $request->validate([
            'model_type' => 'required|string|in:gizi,pencegahan,penanggulangan,desa',
            'action'     => 'required|string|in:publish_all,unpublish_all',
            'active_tab' => 'required|string',
            'period'     => 'nullable|string|regex:/^\d{4}-\d{2}$/', // dipakai untuk gizi
        ]);

        $modelMap = [
            'gizi'           => DataGizi::class,
            'pencegahan'     => DataPencegahan::class,
            'penanggulangan' => DataPenanggulangan::class,
            'desa'           => DataDesa::class,
        ];

        $modelClass = $modelMap[$request->model_type] ?? null;

        if (! $modelClass) {
            return back()->with('error', 'Tipe data tidak valid.');
        }

        $currentStatus = '';
        $newStatus     = '';
        $message       = '';

        if ($request->action === 'publish_all') {
            $currentStatus = 'approved';
            $newStatus     = 'published';
            $message       = 'Semua data approved berhasil di-publish.';
        } elseif ($request->action === 'unpublish_all') {
            $currentStatus = 'published';
            $newStatus     = 'approved';
            $message       = 'Semua data published berhasil ditarik kembali.';
        }

        if ($currentStatus && $newStatus) {
            try {
                $query = $modelClass::where('status', $currentStatus);

                // 🔒 Khusus DataGizi: batasi ke periode yang aktif
                if ($request->model_type === 'gizi' && $request->filled('period')) {
                    try {
                        $period = Carbon::createFromFormat('Y-m', $request->period)->startOfMonth();
                        $query->whereYear('period', $period->year)
                              ->whereMonth('period', $period->month);
                    } catch (\Exception $e) {
                        return back()
                            ->with('error', 'Periode tidak valid untuk aksi massal.')
                            ->with('active_tab', $request->active_tab);
                    }
                }

                $affectedRows = $query->update(['status' => $newStatus]);

                if ($affectedRows == 0) {
                    $message = 'Tidak ada data dengan status "' . $currentStatus . '" di periode ini yang perlu diupdate.';
                } else {
                    $message .= " ({$affectedRows} data diupdate)";
                }
            } catch (\Exception $e) {
                return back()
                    ->with('error', 'Terjadi kesalahan saat update massal: ' . $e->getMessage())
                    ->with('active_tab', $request->active_tab);
            }
        }

        return back()
            ->with('success', $message)
            ->with('active_tab', $request->active_tab);
    }
}
