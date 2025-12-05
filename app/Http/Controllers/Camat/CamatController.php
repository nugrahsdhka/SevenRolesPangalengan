<?php

namespace App\Http\Controllers\Camat;

use Carbon\Carbon;
use App\Models\DataDesa;
use App\Models\DataGizi;
use Illuminate\Http\Request;
use App\Models\DataPencegahan;
use App\Models\DataPenanggulangan;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx\Rels;

class CamatController extends Controller
{
    public function index(Request $request)
    {
        $perPage = 15;
        $allData = []; // Inisialisasi array agar tidak error undefined

        // =========================
        //  1. Ambil semua periode tersedia
        // =========================
        $availablePeriods = DataGizi::select(DB::raw('DATE_FORMAT(period, "%Y-%m-01") as period_date'))
            ->distinct()
            ->orderBy('period_date', 'desc')
            ->pluck('period_date');

        // =========================
        //  2. Tentukan periodYm (Y-m)
        // =========================
        $requestedPeriod = $request->input('period');
        $sessionPeriod   = session('camat_gizi_period');

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
            $periodYm = $availablePeriods->isNotEmpty() ? $latestPeriodYm : now()->format('Y-m');
        }

        session(['camat_gizi_period' => $periodYm]);
        [$year, $month] = explode('-', $periodYm);

        // =========================
        //  STATUS TAB
        // =========================
        $status = $request->input('status', 'pending');

        // =========================
        //  QUERY DATA GIZI
        // =========================
        $giziQuery = DataGizi::query()
            ->select(
                'desa_kelurahan',
                'status',
                DB::raw('COUNT(*) as total_anak'),
                DB::raw('SUM(CASE WHEN zs_tb_u < -2 THEN 1 ELSE 0 END) as total_kasus_stunting'),
                DB::raw('SUM(zs_tb_u) as sum_zs_tbu'),
                DB::raw('MAX(created_at) as tanggal_input_terbaru'),
                DB::raw("
                    (
                        SELECT dg2.user_id
                        FROM data_gizis dg2
                        WHERE dg2.desa_kelurahan = data_gizis.desa_kelurahan
                        AND dg2.status = data_gizis.status
                        AND DATE_FORMAT(dg2.period, '%Y-%m') = '" . $periodYm . "'
                        ORDER BY dg2.created_at DESC
                        LIMIT 1
                    ) as user_id
                ")
            )
            ->whereYear('period', $year)
            ->whereMonth('period', $month)
            ->where('status', $status)
            ->groupBy('desa_kelurahan', 'status')
            ->orderByRaw("FIELD(status, 'pending', 'approved', 'published')")
            ->orderBy('tanggal_input_terbaru', 'desc');

        $allData['gizi'] = $giziQuery->paginate($perPage, ['*'], 'gizi_page');

        // =========================
        //  LOAD USER UNTUK GIZI (VERSI AMAN)
        // =========================
        // Ini perbaikan yang tadi saya berikan
        foreach ($allData['gizi'] as $item) {
            if (!empty($item->user_id)) {
                $user = \App\Models\User::find($item->user_id);
                if ($user) {
                    $item->setRelation('user', $user);
                }
            }
        }

        // =========================
        //  STATUS COUNTS
        // =========================
        $statusCountsRaw = DataGizi::select(
                'status',
                DB::raw('COUNT(DISTINCT desa_kelurahan) as total_desa')
            )
            ->whereYear('period', $year)
            ->whereMonth('period', $month)
            ->groupBy('status')
            ->pluck('total_desa', 'status')
            ->toArray();

        $statusCounts = [
            'pending'   => $statusCountsRaw['pending']   ?? 0,
            'approved'  => $statusCountsRaw['approved']  ?? 0,
            'published' => $statusCountsRaw['published'] ?? 0,
        ];

        // =========================
        //  TAB LAIN
        // =========================
        $allData['pencegahan'] = DataPencegahan::with('user')
            ->orderByRaw("FIELD(status, 'pending', 'approved', 'published')")
            ->latest('id')
            ->paginate($perPage, ['*'], 'pencegahan_page');

        $allData['penanggulangan'] = DataPenanggulangan::with('user')
            ->orderByRaw("FIELD(status, 'pending', 'approved', 'published')")
            ->latest('id')
            ->paginate($perPage, ['*'], 'penanggulangan_page');

        $allData['desa'] = DataDesa::with('user')
            ->orderByRaw("FIELD(status, 'pending', 'approved', 'published')")
            ->latest('id')
            ->paginate($perPage, ['*'], 'desa_page');

        // =========================
        //  BULK ACTION FLAGS
        // =========================
        $giziBase = DataGizi::whereYear('period', $year)->whereMonth('period', $month);

        $bulkActionData = [
            'gizi' => [
                'has_pending'  => (clone $giziBase)->where('status', 'pending')->exists(),
                'has_approved' => (clone $giziBase)->where('status', 'approved')->exists(),
            ],
            'pencegahan' => [
                'has_pending'  => DataPencegahan::where('status', 'pending')->exists(),
                'has_approved' => DataPencegahan::where('status', 'approved')->exists(),
            ],
            'penanggulangan' => [
                'has_pending'  => DataPenanggulangan::where('status', 'pending')->exists(),
                'has_approved' => DataPenanggulangan::where('status', 'approved')->exists(),
            ],
            'desa' => [
                'has_pending'  => DataDesa::where('status', 'pending')->exists(),
                'has_approved' => DataDesa::where('status', 'approved')->exists(),
            ],
        ];

        $activeTab = $request->input('active_tab', session('active_tab', 'gizi'));
        session(['active_tab' => $activeTab]);

        // =========================
        //  RETURN VIEW (PASTIKAN INI ADA)
        // =========================
        return view('camat.index', compact(
            'allData',          // <--- Variabel yang tadi hilang (Undefined)
            'bulkActionData',
            'activeTab',
            'periodYm',
            'availablePeriods',
            'status',
            'statusCounts'
        ));
    }

    public function updateStatus(Request $request)
    {
        $request->validate([
            'model'      => 'required|string|in:DataGizi,DataPencegahan,DataPenanggulangan,DataDesa',
            'new_status' => 'required|string|in:pending,approved',
            'active_tab' => 'required|string',
        ]);

        $modelName = 'App\\Models\\' . $request->model;

        if (!class_exists($modelName)) {
            return back()
                ->with('error', 'Tipe data tidak valid.')
                ->withInput();
        }

        // ===== DataGizi: update berdasarkan desa + periode =====
        if ($request->model === 'DataGizi') {
            $request->validate([
                'desa_kelurahan' => 'required|string',
                'period'         => 'nullable|string', // 'Y-m'
            ]);

            $desaKelurahan = $request->desa_kelurahan;
            $periodYm      = $request->input('period');

            $query = $modelName::where('desa_kelurahan', $desaKelurahan);

            if ($periodYm) {
                [$year, $month] = explode('-', $periodYm);
                $query->whereYear('period', $year)->whereMonth('period', $month);
            }

            // Cek apakah ada data published di scope tersebut
            $hasPublished = (clone $query)->where('status', 'published')->exists();

            if ($hasPublished && $request->new_status === 'pending') {
                return back()
                    ->with('error', 'Tidak bisa mengubah status Published menjadi Pending. Tarik dulu ke Approved.')
                    ->with('active_tab', $request->active_tab);
            }

            // Update semua data (kecuali published)
            $affectedRows = $query
                ->where('status', '!=', 'published')
                ->update(['status' => $request->new_status]);

            $message = $request->new_status === 'approved'
                ? "Data desa {$desaKelurahan} berhasil di-approve ({$affectedRows} data)."
                : "Data desa {$desaKelurahan} berhasil ditarik kembali ({$affectedRows} data).";

            return back()
                ->with('success', $message)
                ->with('active_tab', $request->active_tab);
        }

        // ===== Model lain (Pencegahan, Penanggulangan, Desa): berdasarkan ID =====
        $request->validate([
            'id' => 'required|integer',
        ]);

        $data = $modelName::findOrFail($request->id);

        if ($data->status === 'published' && $request->new_status === 'pending') {
            return back()
                ->with('error', 'Tidak bisa mengubah status Published menjadi Pending. Tarik dulu ke Approved.')
                ->with('active_tab', $request->active_tab);
        }

        $data->status = $request->new_status;
        $data->save();

        $message = $request->new_status === 'approved'
            ? 'Data berhasil di-approve.'
            : 'Data berhasil ditarik kembali (un-approved).';

        return back()
            ->with('success', $message)
            ->with('active_tab', $request->active_tab);
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

    public function bulkUpdateStatus(Request $request)
    {
        $request->validate([
            'model_type' => 'required|string|in:gizi,pencegahan,penanggulangan,desa',
            'action'     => 'required|string|in:approve_all,unapprove_all',
            'active_tab' => 'required|string',
            'period'     => 'nullable|string', // khusus gizi
        ]);

        // Mapping dari string ke class Model
        $modelMap = [
            'gizi'          => DataGizi::class,
            'pencegahan'    => DataPencegahan::class,
            'penanggulangan'=> DataPenanggulangan::class,
            'desa'          => DataDesa::class,
        ];

        $modelClass = $modelMap[$request->model_type] ?? null;

        if (!$modelClass) {
            return back()
                ->with('error', 'Tipe data tidak valid.')
                ->withInput();
        }

        $currentStatus = '';
        $newStatus     = '';
        $message       = '';

        if ($request->action === 'approve_all') {
            $currentStatus = 'pending';
            $newStatus     = 'approved';
            $message       = 'Semua data pending berhasil di-approve.';
        } elseif ($request->action === 'unapprove_all') {
            $currentStatus = 'approved';
            $newStatus     = 'pending';
            $message       = 'Semua data approved berhasil ditarik (un-approved).';
        }

        if ($currentStatus && $newStatus) {
            try {
                // Query dasar
                $query = $modelClass::where('status', $currentStatus);

                $periodYm = $request->input('period');

                // Khusus gizi → filter per-periode
                if ($request->model_type === 'gizi' && $periodYm) {
                    [$year, $month] = explode('-', $periodYm);
                    $query->whereYear('period', $year)->whereMonth('period', $month);

                    // Untuk unapprove_all, jangan kalau masih ada published di periode tsb
                    if ($request->action === 'unapprove_all') {
                        $publishedQuery = $modelClass::where('status', 'published')
                            ->whereYear('period', $year)
                            ->whereMonth('period', $month);

                        if ($publishedQuery->exists()) {
                            return back()
                                ->with('error', 'Gagal menarik semua data: terdapat data Published pada periode ini. Tarik data Published satu per satu jika diperlukan.')
                                ->with('active_tab', $request->active_tab);
                        }
                    }
                } else {
                    // Model lain (global): cek published untuk unapprove_all
                    if ($request->action === 'unapprove_all'
                        && $modelClass::where('status', 'published')->exists()
                    ) {
                        return back()
                            ->with('error', 'Gagal menarik semua data: Terdapat data yang sudah Published. Tarik data Published satu per satu jika diperlukan.')
                            ->with('active_tab', $request->active_tab);
                    }
                }

                // Lakukan update massal
                $affectedRows = $query->update(['status' => $newStatus]);

                if ($affectedRows == 0) {
                    $message = 'Tidak ada data dengan status "' . $currentStatus . '" yang perlu diupdate.';
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


    public function showGiziDetail(Request $request, $desaKelurahan)
    {
        $perPage = 50;

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
}
