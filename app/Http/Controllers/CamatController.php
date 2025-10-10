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
     * Menampilkan semua data yang relevan untuk Camat (pending & approved).
     */
    public function index()
    {
        $allData = [
            'gizi' => DataGizi::with('user')->whereIn('status', ['pending', 'approved'])->latest()->get(),
            'pencegahan' => DataPencegahan::with('user')->whereIn('status', ['pending', 'approved'])->latest()->get(),
            'penanggulangan' => DataPenanggulangan::with('user')->whereIn('status', ['pending', 'approved'])->latest()->get(),
            'desa' => DataDesa::with('user')->whereIn('status', ['pending', 'approved'])->latest()->get(),
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
            'new_status' => 'required|string|in:pending,approved', // Hanya menerima 'pending' atau 'approved'
        ]);

        $modelName = 'App\\Models\\' . $request->model;
        if (!class_exists($modelName)) {
            return back()->with('error', 'Tipe data tidak valid.');
        }

        $data = $modelName::findOrFail($request->id);
        $data->status = $request->new_status;
        $data->save();

        $message = $request->new_status === 'approved' ? 'Data berhasil di-approve.' : 'Data berhasil ditarik kembali (un-approved).';
        return back()->with('success', $message);
    }

    /**
     * Menghapus data secara permanen.
     */
    public function destroy(Request $request)
    {
        $request->validate([
            'model' => 'required|string',
            'id' => 'required|integer',
        ]);

        $modelName = 'App\\Models\\' . $request->model;
        if (!class_exists($modelName)) {
            return back()->with('error', 'Tipe data tidak valid.');
        }

        $data = $modelName::findOrFail($request->id);
        $data->delete();

        return back()->with('success', 'Data berhasil dihapus secara permanen.');
    }
}