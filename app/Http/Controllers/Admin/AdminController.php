<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DataDesa;
use App\Models\DataGizi;
use App\Models\DataPencegahan;
use App\Models\DataPenanggulangan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB; // <-- PASTIKAN INI DI-IMPORT

class AdminController extends Controller
{
    /**
     * Menampilkan SEMUA data dari setiap divisi dengan paginasi.
     */
    public function publishedData(Request $request)
    {
        $perPage = 10; 

        $dataToReview = [
            // ✅ MODIFIKASI: Query Gizi diubah menjadi Agregasi
            'gizi' => DataGizi::query()
                ->select(
                    'desa_kelurahan',
                    'status',
                    DB::raw('COUNT(*) as total_anak'),
                    DB::raw('SUM(CASE WHEN zs_tb_u < -2 THEN 1 ELSE 0 END) as total_kasus_stunting'),
                    DB::raw('SUM(zs_tb_u) as sum_zs_tbu'),
                    DB::raw('MAX(created_at) as tanggal_input_terbaru'),
                    DB::raw('(SELECT user_id FROM data_gizis dg2 WHERE dg2.desa_kelurahan = data_gizis.desa_kelurahan AND dg2.status = data_gizis.status ORDER BY created_at DESC LIMIT 1) as user_id')
                )
                ->whereIn('status', ['approved', 'published']) // <-- Filter Admin
                ->groupBy('desa_kelurahan', 'status')
                ->orderByRaw("FIELD(status, 'approved', 'published')")
                ->orderBy('tanggal_input_terbaru', 'desc')
                ->paginate($perPage, ['*'], 'gizi_page'),

            'pencegahan' => DataPencegahan::with('user')
                ->whereIn('status', ['approved', 'published'])
                ->orderByRaw("FIELD(status, 'approved', 'published')")
                ->latest('periode')
                ->paginate($perPage, ['*'], 'pencegahan_page'),

            'penanggulangan' => DataPenanggulangan::with('user')
                ->whereIn('status', ['approved', 'published'])
                ->orderByRaw("FIELD(status, 'approved', 'published')")
                ->latest('periode')
                ->paginate($perPage, ['*'], 'penanggulangan_page'),

            'desa' => DataDesa::with('user')
                ->whereIn('status', ['approved', 'published'])
                ->orderByRaw("FIELD(status, 'approved', 'published')")
                ->latest('periode')
                ->paginate($perPage, ['*'], 'desa_page'),
        ];

        // ✅ TAMBAHAN: Load user manual untuk data gizi (karena agregat)
        foreach ($dataToReview['gizi'] as $item) {
            if ($item->user_id) {
                $item->load(['user' => function($query) use ($item) {
                    $query->where('id', $item->user_id);
                }]);
            }
        }

        $bulkActionData = [
            'gizi' => [
                'has_approved' => DataGizi::where('status', 'approved')->exists(),
                'has_published' => DataGizi::where('status', 'published')->exists(),
            ],
            'pencegahan' => [
                'has_approved' => DataPencegahan::where('status', 'approved')->exists(),
                'has_published' => DataPencegahan::where('status', 'published')->exists(),
            ],
            'penanggulangan' => [
                'has_approved' => DataPenanggulangan::where('status', 'approved')->exists(),
                'has_published' => DataPenanggulangan::where('status', 'published')->exists(),
            ],
            'desa' => [
                'has_approved' => DataDesa::where('status', 'approved')->exists(),
                'has_published' => DataDesa::where('status', 'published')->exists(),
            ],
        ];

        $activeTab = $request->input('active_tab', session('active_tab', 'gizi'));

        return view('admin.published.index', compact('dataToReview', 'bulkActionData', 'activeTab'));
    }

    // ✅ METHOD BARU: Untuk menampilkan detail Gizi
    public function showGiziDetail(Request $request, $desaKelurahan)
    {
        // 1. Ambil data statistik untuk card di bagian atas
        $statistik = DataGizi::where('desa_kelurahan', $desaKelurahan)
            ->whereIn('status', ['approved', 'published']) // Hanya data yg relevan dgn Admin
            ->select(
                DB::raw('COUNT(*) as total_anak'),
                DB::raw('SUM(CASE WHEN zs_tb_u < -2 THEN 1 ELSE 0 END) as total_stunting'),
                DB::raw('AVG(tinggi) as avg_tinggi'),
                DB::raw('AVG(zs_tb_u) as avg_zs_tbu')
            )
            ->first();

        // 2. Ambil data individual dengan paginasi
        $perPage = 20; 
        $dataGizi = DataGizi::where('desa_kelurahan', $desaKelurahan)
            ->whereIn('status', ['approved', 'published']) // Hanya data yg relevan dgn Admin
            ->orderBy('created_at', 'desc')
            ->paginate($perPage);

        // 3. Tampilkan view baru
        // Menggunakan view admin/published/detailGizi.blade.php
        return view('admin.published.detailGizi', compact('desaKelurahan', 'statistik', 'dataGizi'));
    }


    /**
     * Aksi untuk PUBLISH atau PULL (tarik kembali) data.
     */
    public function publish(Request $request)
    {
        $request->validate([
            'model' => 'required|string|in:DataGizi,DataPencegahan,DataPenanggulangan,DataDesa',
            'active_tab' => 'required|string',
        ]);

        $modelName = 'App\\Models\\' . $request->model;
        if (!class_exists($modelName)) {
            return back()->with('error', 'Tipe data tidak valid.');
        }

        // ✅ MODIFIKASI: Logika terpisah untuk DataGizi
        if ($request->model === 'DataGizi') {
            $request->validate([
                'desa_kelurahan' => 'required|string',
                'current_status' => 'required|string|in:approved,published',
            ]);

            $desaKelurahan = $request->desa_kelurahan;
            $currentStatus = $request->current_status;
            
            $newStatus = ($currentStatus === 'approved') ? 'published' : 'approved';
            $actionWord = ($newStatus === 'published') ? 'di-publish' : 'ditarik';

            try {
                $affectedRows = $modelName::where('desa_kelurahan', $desaKelurahan)
                    ->where('status', $currentStatus)
                    ->update(['status' => $newStatus]);
                
                $message = "Data desa {$desaKelurahan} berhasil {$actionWord} ({$affectedRows} data).";
                return back()->with('success', $message)->with('active_tab', $request->active_tab);

            } catch (\Exception $e) {
                return back()->with('error', 'Gagal update status: ' . $e->getMessage())
                    ->with('active_tab', $request->active_tab);
            }
        }

        // Logika LAMA untuk model lain (berdasarkan ID)
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
        
        return back()->with('success', $message)->with('active_tab', $request->active_tab);
    }

    /**
     * METHOD AKSI MASSAL (BULK)
     */
    public function bulkPublish(Request $request)
    {
        $request->validate([
            'model_type' => 'required|string|in:gizi,pencegahan,penanggulangan,desa',
            'action' => 'required|string|in:publish_all,unpublish_all',
            'active_tab' => 'required|string',
        ]);

        $modelMap = [
            'gizi' => DataGizi::class,
            'pencegahan' => DataPencegahan::class,
            'penanggulangan' => DataPenanggulangan::class,
            'desa' => DataDesa::class,
        ];

        $modelClass = $modelMap[$request->model_type] ?? null;

        if (!$modelClass) {
            return back()->with('error', 'Tipe data tidak valid.');
        }

        $currentStatus = '';
        $newStatus = '';
        $message = '';

        if ($request->action === 'publish_all') {
            $currentStatus = 'approved';
            $newStatus = 'published';
            $message = 'Semua data approved berhasil di-publish.';
        } elseif ($request->action === 'unpublish_all') {
            $currentStatus = 'published';
            $newStatus = 'approved';
            $message = 'Semua data published berhasil ditarik kembali.';
        }

        if ($currentStatus && $newStatus) {
            try {
                $affectedRows = $modelClass::where('status', $currentStatus)->update(['status' => $newStatus]);
                if ($affectedRows == 0) {
                     $message = 'Tidak ada data dengan status "' . $currentStatus . '" yang perlu diupdate.';
                } else {
                    $message .= " ({$affectedRows} data diupdate)";
                }
            } catch (\Exception $e) {
                 return back()->with('error', 'Terjadi kesalahan saat update massal: ' . $e->getMessage())->with('active_tab', $request->active_tab);
            }
        }

        return back()->with('success', $message)->with('active_tab', $request->active_tab);
    }
}
