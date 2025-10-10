<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\DataGizi;
use App\Models\DataPencegahan;
use App\Models\DataPenanggulangan;
use App\Models\DataDesa;

class CamatController extends Controller
{
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

    public function updateStatus(Request $request)
    {
        $request->validate([
            'model' => 'required|string',
            'id' => 'required|integer',
            'new_status' => 'required|string|in:pending,approved',
            'active_tab' => 'required|string', // Validasi input tab aktif
        ]);

        $modelName = 'App\\Models\\' . $request->model;
        if (!class_exists($modelName)) {
            return back()->with('error', 'Tipe data tidak valid.');
        }

        $data = $modelName::findOrFail($request->id);
        $data->status = $request->new_status;
        $data->save();

        $message = $request->new_status === 'approved' ? 'Data berhasil di-approve.' : 'Data berhasil ditarik kembali (un-approved).';
        
        // Simpan tab aktif ke session sebelum redirect
        return back()->with('success', $message)->with('active_tab', $request->active_tab);
    }

    public function destroy(Request $request)
    {
        $request->validate([
            'model' => 'required|string',
            'id' => 'required|integer',
            'active_tab' => 'required|string', // Validasi input tab aktif
        ]);

        $modelName = 'App\\Models\\' . $request->model;
        if (!class_exists($modelName)) {
            return back()->with('error', 'Tipe data tidak valid.');
        }

        $data = $modelName::findOrFail($request->id);
        $data->delete();

        // Simpan tab aktif ke session sebelum redirect
        return back()->with('success', 'Data berhasil dihapus secara permanen.')->with('active_tab', $request->active_tab);
    }
}