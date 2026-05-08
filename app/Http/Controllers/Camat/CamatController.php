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
use Illuminate\Validation\Rule;

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
            return back()->with('error', 'Tipe data tidak valid.')->withInput();
        }

        // ===== LOGIKA KHUSUS DATA GIZI (Agregasi) =====
        if ($request->model === 'DataGizi') {
            $request->validate([
                'desa_kelurahan' => 'required|string',
                'period'         => 'nullable|string',
            ]);

            $desaKelurahan = $request->desa_kelurahan;
            $periodYm      = $request->input('period');

            $query = $modelName::where('desa_kelurahan', $desaKelurahan);

            if ($periodYm) {
                [$year, $month] = explode('-', $periodYm);
                $query->whereYear('period', $year)->whereMonth('period', $month);
            }

            /**
             * SISTEM PROTEKSI STATUS:
             */
            if ($request->new_status === 'approved') {
                // Aksi Approve: Hanya ambil yang saat ini 'pending'
                // Data 'published' dan 'need_review' tidak akan tersentuh
                $query->where('status', 'pending');
                $actionWord = "disetujui";
            } else {
                // Aksi Tarik Kembali: Hanya ambil yang saat ini 'approved'
                // Data yang sudah 'published' tetap aman (tidak berubah jadi pending)
                $query->where('status', 'approved');
                $actionWord = "ditarik kembali ke Pending";
            }

            $affectedRows = $query->update([
                'status' => $request->new_status,
            ]);

            if ($affectedRows === 0) {
                return back()->with('error', 'Tidak ada data yang memenuhi syarat untuk diupdate (Data mungkin sudah Published atau masih Anomali).');
            }

            return back()->with('success', "Berhasil: {$affectedRows} data desa {$desaKelurahan} {$actionWord}.")->with('active_tab', $request->active_tab);
        }

        // ===== LOGIKA UNTUK MODEL LAIN (Berdasarkan ID) =====
        $request->validate(['id' => 'required|integer']);
        $data = $modelName::findOrFail($request->id);
        
        // Proteksi untuk model non-agregat: Jangan izinkan menarik jika sudah published
        if ($data->status === 'published' && $request->new_status === 'pending') {
            return back()->with('error', 'Data yang sudah Published tidak dapat ditarik kembali.');
        }

        // Jangan izinkan ubah data anomali di sini
        if ($data->status === 'need_review') {
            return back()->with('error', 'Data anomali harus diperbaiki oleh Divisi Gizi terlebih dahulu.');
        }

        $data->status = $request->new_status;
        $data->save();

        return back()->with('success', 'Status data berhasil diperbarui.')->with('active_tab', $request->active_tab);
    }

    public function bulkUpdateStatus(Request $request)
    {
        $request->validate([
            'model_type' => 'required|string|in:gizi,pencegahan,penanggulangan,desa',
            'action'     => 'required|string|in:approve_all,unapprove_all',
            'active_tab' => 'required|string',
            'period'     => 'nullable|string',
        ]);

        $modelMap = [
            'gizi'           => \App\Models\DataGizi::class,
            'pencegahan'     => \App\Models\DataPencegahan::class,
            'penanggulangan' => \App\Models\DataPenanggulangan::class,
            'desa'           => \App\Models\DataDesa::class,
        ];

        $modelClass = $modelMap[$request->model_type];
        $periodYm   = $request->input('period');

        try {
            if ($request->action === 'approve_all') {
                // Bulk Approve: Hanya menyasar status 'pending'
                $query = $modelClass::where('status', 'pending');
                $targetStatus = 'approved';
            } else {
                // Bulk Unapprove: Hanya menyasar status 'approved'
                // Data 'published' terkunci secara otomatis karena tidak masuk filter ini
                $query = $modelClass::where('status', 'approved');
                $targetStatus = 'pending';
            }

            // Filter Periode (Khusus Gizi)
            if ($request->model_type === 'gizi' && $periodYm) {
                [$year, $month] = explode('-', $periodYm);
                $query->whereYear('period', $year)->whereMonth('period', $month);
            }

            $affectedRows = $query->update(['status' => $targetStatus]);

            if ($affectedRows === 0) {
                return back()->with('error', 'Tidak ada data yang memenuhi syarat untuk diproses.');
            }

            return back()->with('success', "Berhasil memperbarui {$affectedRows} data.")
                         ->with('active_tab', $request->active_tab);

        } catch (\Exception $e) {
            return back()->with('error', 'Terjadi kesalahan sistem saat pembaruan massal.');
        }
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

        // ===== DataGizi: hapus berdasarkan desa + periode + status =====
        if ($request->model === 'DataGizi') {
            $request->validate([
                'desa_kelurahan' => 'required|string',
                'period'         => 'nullable|string', // Y-m
                'status'         => ['required', Rule::in(['pending', 'approved', 'published'])],
            ]);

            $desaKelurahan = $request->desa_kelurahan;
            $periodYm      = $request->input('period');
            $status        = $request->input('status');

            $query = $modelName::where('desa_kelurahan', $desaKelurahan)
                ->where('status', $status);   // ⬅️ FILTER STATUS WAJIB

            if ($periodYm) {
                [$year, $month] = explode('-', $periodYm);
                $query->whereYear('period', $year)->whereMonth('period', $month);
            }

            $deletedCount = $query->delete();

            if ($deletedCount === 0) {
                return back()
                    ->with(
                        'error',
                        "Tidak ada data dengan status {$status} untuk desa {$desaKelurahan} pada periode tersebut yang dihapus."
                    )
                    ->with('active_tab', $request->active_tab);
            }

            return back()
                ->with(
                    'success',
                    "Semua data dengan status {$status} untuk desa {$desaKelurahan} pada periode terkait berhasil dihapus ({$deletedCount} data)."
                )
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
}
