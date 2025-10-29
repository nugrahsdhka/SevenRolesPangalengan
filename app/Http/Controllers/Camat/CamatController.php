<?php

namespace App\Http\Controllers\Camat;

use App\Http\Controllers\Controller;
use App\Models\DataGizi;
use App\Models\DataPencegahan;
use App\Models\DataPenanggulangan;
use App\Models\DataDesa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx\Rels;

class CamatController extends Controller
{
    public function index(Request $request)
    {
        // Tentukan jumlah item per halaman
        $perPage = 15;

        $allData = [
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
                ->with(['user' => function($query) {
                    // Eager load user berdasarkan user_id dari subquery
                }])
                ->groupBy('desa_kelurahan', 'status')
                ->orderByRaw("FIELD(status, 'pending', 'approved', 'published')")
                ->orderBy('tanggal_input_terbaru', 'desc')
                ->paginate($perPage, ['*'], 'gizi_page'),

            'pencegahan' => DataPencegahan::with('user')
                ->orderByRaw("FIELD(status, 'pending', 'approved', 'published')")
                ->latest('periode')
                ->paginate($perPage, ['*'], 'pencegahan_page'),

            'penanggulangan' => DataPenanggulangan::with('user')
                ->orderByRaw("FIELD(status, 'pending', 'approved', 'published')")
                ->latest('periode')
                ->paginate($perPage, ['*'], 'penanggulangan_page'),

            'desa' => DataDesa::with('user')
                ->orderByRaw("FIELD(status, 'pending', 'approved', 'published')")
                ->latest('periode')
                ->paginate($perPage, ['*'], 'desa_page'),
        ];

        // ✅ Load user manually untuk data gizi (karena agregat)
        foreach ($allData['gizi'] as $item) {
            if ($item->user_id) {
                $item->load(['user' => function($query) use ($item) {
                    $query->where('id', $item->user_id);
                }]);
            }
        }

        // Cek status untuk tombol bulk action
        $bulkActionData = [
            'gizi' => [
                'has_pending' => DataGizi::where('status', 'pending')->exists(),
                'has_approved' => DataGizi::where('status', 'approved')->exists(),
            ],
            'pencegahan' => [
                'has_pending' => DataPencegahan::where('status', 'pending')->exists(),
                'has_approved' => DataPencegahan::where('status', 'approved')->exists(),
            ],
            'penanggulangan' => [
                'has_pending' => DataPenanggulangan::where('status', 'pending')->exists(),
                'has_approved' => DataPenanggulangan::where('status', 'approved')->exists(),
            ],
            'desa' => [
                'has_pending' => DataDesa::where('status', 'pending')->exists(),
                'has_approved' => DataDesa::where('status', 'approved')->exists(),
            ],
        ];

        // Tentukan active tab
        $activeTab = $request->input('active_tab', session('active_tab', 'gizi'));

        return view('camat.index', compact('allData', 'bulkActionData', 'activeTab'));
    }

    public function updateStatus(Request $request)
    {
        $request->validate([
            'model' => 'required|string|in:DataGizi,DataPencegahan,DataPenanggulangan,DataDesa',
            'new_status' => 'required|string|in:pending,approved',
            'active_tab' => 'required|string',
        ]);

        $modelName = 'App\\Models\\' . $request->model;
        
        if (!class_exists($modelName)) {
            return back()->with('error', 'Tipe data tidak valid.')->withInput();
        }

        // ✅ PERBAIKAN: Untuk DataGizi, update berdasarkan desa_kelurahan
        if ($request->model === 'DataGizi') {
            $request->validate([
                'desa_kelurahan' => 'required|string',
            ]);

            $desaKelurahan = $request->desa_kelurahan;
            
            // Cek apakah ada data published
            $hasPublished = $modelName::where('desa_kelurahan', $desaKelurahan)
                ->where('status', 'published')
                ->exists();

            if ($hasPublished && $request->new_status === 'pending') {
                return back()->with('error', 'Tidak bisa mengubah status Published menjadi Pending. Tarik dulu ke Approved.')
                    ->with('active_tab', $request->active_tab);
            }

            // Update semua data dalam desa tersebut
            $affectedRows = $modelName::where('desa_kelurahan', $desaKelurahan)
                ->where('status', '!=', 'published') // Jangan ubah yang published
                ->update(['status' => $request->new_status]);

            $message = $request->new_status === 'approved' 
                ? "Data desa {$desaKelurahan} berhasil di-approve ({$affectedRows} data)." 
                : "Data desa {$desaKelurahan} berhasil ditarik kembali ({$affectedRows} data).";
            
            return back()->with('success', $message)->with('active_tab', $request->active_tab);
        }

        // ✅ Untuk model lain (Pencegahan, Penanggulangan, Desa) - update berdasarkan ID
        $request->validate([
            'id' => 'required|integer',
        ]);

        $data = $modelName::findOrFail($request->id);

        if ($data->status === 'published' && $request->new_status === 'pending') {
            return back()->with('error', 'Tidak bisa mengubah status Published menjadi Pending. Tarik dulu ke Approved.')
                ->with('active_tab', $request->active_tab);
        }

        $data->status = $request->new_status;
        $data->save();

        $message = $request->new_status === 'approved' 
            ? 'Data berhasil di-approve.' 
            : 'Data berhasil ditarik kembali (un-approved).';
        
        return back()->with('success', $message)->with('active_tab', $request->active_tab);
    }

    public function destroy(Request $request)
    {
        $request->validate([
            'model' => 'required|string|in:DataGizi,DataPencegahan,DataPenanggulangan,DataDesa',
            'active_tab' => 'required|string',
        ]);

        $modelName = 'App\\Models\\' . $request->model;
        
        if (!class_exists($modelName)) {
            return back()->with('error', 'Tipe data tidak valid.')->withInput();
        }

        // ✅ PERBAIKAN: Untuk DataGizi, hapus berdasarkan desa_kelurahan
        if ($request->model === 'DataGizi') {
            $request->validate([
                'desa_kelurahan' => 'required|string',
            ]);

            $desaKelurahan = $request->desa_kelurahan;
            
            // Hapus semua data dalam desa tersebut
            $deletedCount = $modelName::where('desa_kelurahan', $desaKelurahan)->delete();

            return back()->with('success', "Semua data desa {$desaKelurahan} berhasil dihapus ({$deletedCount} data).")
                ->with('active_tab', $request->active_tab);
        }

        // ✅ Untuk model lain - hapus berdasarkan ID
        $request->validate([
            'id' => 'required|integer',
        ]);

        $data = $modelName::findOrFail($request->id);
        $data->delete();

        return back()->with('success', 'Data berhasil dihapus secara permanen.')
            ->with('active_tab', $request->active_tab);
    }

    public function bulkUpdateStatus(Request $request)
    {
        $request->validate([
            'model_type' => 'required|string|in:gizi,pencegahan,penanggulangan,desa',
            'action' => 'required|string|in:approve_all,unapprove_all',
            'active_tab' => 'required|string',
        ]);

        // Mapping dari string ke class Model
        $modelMap = [
            'gizi' => DataGizi::class,
            'pencegahan' => DataPencegahan::class,
            'penanggulangan' => DataPenanggulangan::class,
            'desa' => DataDesa::class,
        ];

        $modelClass = $modelMap[$request->model_type] ?? null;

        if (!$modelClass) {
            return back()->with('error', 'Tipe data tidak valid.')->withInput();
        }

        $currentStatus = '';
        $newStatus = '';
        $message = '';

        if ($request->action === 'approve_all') {
            $currentStatus = 'pending';
            $newStatus = 'approved';
            $message = 'Semua data pending berhasil di-approve.';
        } elseif ($request->action === 'unapprove_all') {
            $currentStatus = 'approved';
            $newStatus = 'pending';
            $message = 'Semua data approved berhasil ditarik (un-approved).';
            
            // Cek apakah ada data published
            if ($modelClass::where('status', 'published')->exists()) {
                return back()->with('error', 'Gagal menarik semua data: Terdapat data yang sudah Published. Tarik data Published satu per satu jika diperlukan.')
                    ->with('active_tab', $request->active_tab);
            }
        }

        if ($currentStatus && $newStatus) {
            try {
                // Lakukan update massal
                $affectedRows = $modelClass::where('status', $currentStatus)->update(['status' => $newStatus]);
                
                if ($affectedRows == 0) {
                    $message = 'Tidak ada data dengan status "' . $currentStatus . '" yang perlu diupdate.';
                } else {
                    $message .= " ({$affectedRows} data diupdate)";
                }
            } catch (\Exception $e) {
                return back()->with('error', 'Terjadi kesalahan saat update massal: ' . $e->getMessage())
                    ->with('active_tab', $request->active_tab);
            }
        }

        return back()->with('success', $message)->with('active_tab', $request->active_tab);
    }

    public function showGiziDetail(Request $request, $desaKelurahan)
    {
        // 1. Ambil data statistik untuk card di bagian atas
        $statistik = DataGizi::where('desa_kelurahan', $desaKelurahan)
            ->select(
                DB::raw('COUNT(*) as total_anak'),
                DB::raw('SUM(CASE WHEN zs_tb_u < -2 THEN 1 ELSE 0 END) as total_stunting'),
                DB::raw('AVG(tinggi) as avg_tinggi'),
                DB::raw('AVG(zs_tb_u) as avg_zs_tbu')
            )
            ->first();

        // 2. Ambil data individual dengan paginasi
        $perPage = 20; // Atur jumlah data per halaman
        $dataGizi = DataGizi::where('desa_kelurahan', $desaKelurahan)
            ->orderBy('created_at', 'desc')
            ->paginate($perPage);

        // 3. Tampilkan view baru dengan data yang diperlukan
        return view('detail.detailGizi', compact('desaKelurahan','statistik','dataGizi'));
    }
}