<?php

namespace App\Http\Controllers\Admin; // ✅ PERBAIKAN: Ubah namespace ini

use App\Http\Controllers\Controller; // ✅ PERBAIKAN: Tambahkan ini
use App\Models\DataDesa;
use App\Models\DataGizi;
use App\Models\DataPencegahan;
use App\Models\DataPenanggulangan;
use Illuminate\Http\Request;

class AdminController extends Controller // ✅ PERBAIKAN: extends Controller
{
    /**
     * Menampilkan SEMUA data dari setiap divisi.
     */
    public function publishedData()
    {
        $dataToReview = [
            'gizi' => DataGizi::with('user')->latest()->get(),
            'pencegahan' => DataPencegahan::with('user')->latest()->get(),
            'penanggulangan' => DataPenanggulangan::with('user')->latest()->get(),
            'desa' => DataDesa::with('user')->latest()->get(),
        ];

        return view('admin.published.index', compact('dataToReview'));
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
}