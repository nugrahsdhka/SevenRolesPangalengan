<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DataDesa;
use App\Models\DataGizi;
use App\Models\DataPencegahan;
use App\Models\DataPenanggulangan;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    /**
     * Menampilkan SEMUA data dari setiap divisi dengan paginasi.
     */
    // ✅ MODIFIKASI: Tambahkan (Request $request)
    public function publishedData(Request $request)
    {
        // Tentukan jumlah item per halaman
        $perPage = 10; // Anda bisa sesuaikan angka ini

        // ✅ MODIFIKASI: Ubah ->get() menjadi ->paginate() dengan page name unik
        $dataToReview = [
            'gizi' => DataGizi::with('user')
                ->whereIn('status', ['approved', 'published'])
                ->orderByRaw("FIELD(status, 'approved', 'published')")
                ->latest('periode')
                ->paginate($perPage, ['*'], 'gizi_page'), // <-- Unik

            'pencegahan' => DataPencegahan::with('user')
                ->whereIn('status', ['approved', 'published'])
                ->orderByRaw("FIELD(status, 'approved', 'published')")
                ->latest('periode')
                ->paginate($perPage, ['*'], 'pencegahan_page'), // <-- Unik

            'penanggulangan' => DataPenanggulangan::with('user')
                ->whereIn('status', ['approved', 'published'])
                ->orderByRaw("FIELD(status, 'approved', 'published')")
                ->latest('periode')
                ->paginate($perPage, ['*'], 'penanggulangan_page'), // <-- Unik

            'desa' => DataDesa::with('user')
                ->whereIn('status', ['approved', 'published'])
                ->orderByRaw("FIELD(status, 'approved', 'published')")
                ->latest('periode')
                ->paginate($perPage, ['*'], 'desa_page'), // <-- Unik
        ];

        // ✅ TAMBAHAN: Cek status untuk tombol bulk action (cek ke seluruh tabel)
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

        // ✅ TAMBAHAN: Logika untuk menentukan active tab
        // Prioritas:
        // 1. Dari request (klik link paginasi ?active_tab=...)
        // 2. Dari session (redirect setelah update/delete)
        // 3. Default 'gizi'
        $activeTab = $request->input('active_tab', session('active_tab', 'gizi'));

        // Kirim semua data ke view
        return view('admin.published.index', compact('dataToReview', 'bulkActionData', 'activeTab'));
    }

    /**
     * Aksi untuk PUBLISH atau PULL (tarik kembali) data.
     */
    public function publish(Request $request)
    {
        $request->validate([
            'model' => 'required|string',
            'id' => 'required|integer',
            'active_tab' => 'required|string',
        ]);

        $modelName = 'App\\Models\\' . $request->model;
        if (!class_exists($modelName)) {
            return back()->with('error', 'Tipe data tidak valid.');
        }

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
     * ✅ METHOD BARU UNTUK AKSI MASSAL (BULK)
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
                }
            } catch (\Exception $e) {
                 return back()->with('error', 'Terjadi kesalahan saat update massal: ' . $e->getMessage())->with('active_tab', $request->active_tab);
            }
        }

        return back()->with('success', $message)->with('active_tab', $request->active_tab);
    }
}
