<?php

namespace App\Http\Controllers\Camat;

use App\Http\Controllers\Controller;
use App\Models\DataGizi;
use App\Models\DataPencegahan;
use App\Models\DataPenanggulangan;
use App\Models\DataDesa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB; // <-- Tambahkan ini

class CamatController extends Controller
{
    public function index()
    {
        // Sortir berdasarkan status agar pending/approved muncul di atas
        $allData = [
            'gizi' => DataGizi::with('user')->orderByRaw("FIELD(status, 'pending', 'approved', 'published')")->latest('periode')->get(),
            'pencegahan' => DataPencegahan::with('user')->orderByRaw("FIELD(status, 'pending', 'approved', 'published')")->latest('periode')->get(),
            'penanggulangan' => DataPenanggulangan::with('user')->orderByRaw("FIELD(status, 'pending', 'approved', 'published')")->latest('periode')->get(),
            'desa' => DataDesa::with('user')->orderByRaw("FIELD(status, 'pending', 'approved', 'published')")->latest('periode')->get(),
        ];

        return view('camat.index', compact('allData'));
    }

    public function updateStatus(Request $request)
    {
        $request->validate([
            'model' => 'required|string|in:DataGizi,DataPencegahan,DataPenanggulangan,DataDesa',
            'id' => 'required|integer',
            'new_status' => 'required|string|in:pending,approved', // Hanya bisa update ke pending atau approved
            'active_tab' => 'required|string',
        ]);

        $modelName = 'App\\Models\\' . $request->model;
        if (!class_exists($modelName)) {
            return back()->with('error', 'Tipe data tidak valid.')->withInput();
        }

        $data = $modelName::findOrFail($request->id);

        // Tambahan: Pastikan tidak bisa mengubah status 'published' secara individual
        if ($data->status === 'published' && $request->new_status === 'pending') {
             return back()->with('error', 'Tidak bisa mengubah status Published menjadi Pending. Tarik dulu.')->withInput();
        }

        $data->status = $request->new_status;
        $data->save();

        $message = $request->new_status === 'approved' ? 'Data berhasil di-approve.' : 'Data berhasil ditarik kembali (un-approved).';
        return back()->with('success', $message)->with('active_tab', $request->active_tab);
    }

    public function destroy(Request $request)
    {
        $request->validate([
            'model' => 'required|string|in:DataGizi,DataPencegahan,DataPenanggulangan,DataDesa',
            'id' => 'required|integer',
            'active_tab' => 'required|string',
        ]);

        $modelName = 'App\\Models\\' . $request->model;
        if (!class_exists($modelName)) {
            return back()->with('error', 'Tipe data tidak valid.')->withInput();
        }

        $data = $modelName::findOrFail($request->id);
        $data->delete();

        return back()->with('success', 'Data berhasil dihapus secara permanen.')->with('active_tab', $request->active_tab);
    }

    // ✅ METHOD BARU UNTUK BULK UPDATE
    public function bulkUpdateStatus(Request $request)
    {
        $request->validate([
            'model_type' => 'required|string|in:gizi,pencegahan,penanggulangan,desa', // Sesuaikan dengan value di form
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
        $error = false;

        if ($request->action === 'approve_all') {
            $currentStatus = 'pending';
            $newStatus = 'approved';
            $message = 'Semua data pending berhasil di-approve.';
        } elseif ($request->action === 'unapprove_all') {
            $currentStatus = 'approved';
            $newStatus = 'pending';
            $message = 'Semua data approved berhasil ditarik (un-approved).';
            // Tambahan: Pastikan tidak menarik data yang sudah published
             if ($modelClass::where('status', 'published')->exists()) {
                 $error = true;
                 $message = 'Gagal menarik semua data: Terdapat data yang sudah Published. Tarik data Published satu per satu jika diperlukan.';
             }
        }

        if (!$error && $currentStatus && $newStatus) {
            try {
                // Lakukan update massal
                $affectedRows = $modelClass::where('status', $currentStatus)->update(['status' => $newStatus]);
                if ($affectedRows == 0) {
                     $message = 'Tidak ada data dengan status "' . $currentStatus . '" yang perlu diupdate.';
                }
            } catch (\Exception $e) {
                 return back()->with('error', 'Terjadi kesalahan saat update massal: ' . $e->getMessage())->with('active_tab', $request->active_tab);
            }
        }

         if($error){
             return back()->with('error', $message)->with('active_tab', $request->active_tab);
         }

        return back()->with('success', $message)->with('active_tab', $request->active_tab);
    }
}