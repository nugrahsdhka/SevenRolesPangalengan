<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\DataGizi;
use App\Models\DataPencegahan;
use App\Models\DataPenanggulangan;
use App\Models\DataDesa;

class CamatController extends Controller
{
    /**
     * ✅ PERBAIKAN: Menampilkan SEMUA data dari setiap divisi.
     */
    public function index()
    {
        $allData = [
            'gizi' => DataGizi::with('user')->latest()->get(),
            'pencegahan' => DataPencegahan::with('user')->latest()->get(),
            'penanggulangan' => DataPenanggulangan::with('user')->latest()->get(),
            'desa' => DataDesa::with('user')->latest()->get(),
        ];

        return view('camat.index', compact('allData'));
    }

    /**
     * Mengubah status data (Approve atau Un-approve).
     */
    public function updateStatus(Request $request)
    {
        $request->validate([
            'model' => 'required|string',
            'id' => 'required|integer',
            'new_status' => 'required|string|in:pending,approved',
            'active_tab' => 'required|string',
        ]);

        $modelName = 'App\\Models\\' . $request->model;
        if (!class_exists($modelName)) {
            return back()->with('error', 'Tipe data tidak valid.');
        }

        $data = $modelName::findOrFail($request->id);
        $data->status = $request->new_status;
        $data->save();

        $message = $request->new_status === 'approved' ? 'Data berhasil di-approve.' : 'Data berhasil ditarik kembali (un-approved).';
        return back()->with('success', $message)->with('active_tab', $request->active_tab);
    }

    /**
     * Menghapus data secara permanen.
     */
    public function destroy(Request $request)
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
        $data->delete();

        return back()->with('success', 'Data berhasil dihapus secara permanen.')->with('active_tab', $request->active_tab);
    }
}