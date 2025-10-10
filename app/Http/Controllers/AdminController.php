<?php

namespace App\Http\Controllers;

use App\Models\DataDesa;
use App\Models\DataGizi;
use App\Models\DataPencegahan;
use App\Models\DataPenanggulangan;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    /**
     * Menampilkan data yang sudah di-approve Camat dan yang sudah di-publish.
     */
    public function publishedData()
    {
        $dataToReview = [
            'gizi' => DataGizi::with('user')->whereIn('status', ['approved', 'published'])->latest()->get(),
            'pencegahan' => DataPencegahan::with('user')->whereIn('status', ['approved', 'published'])->latest()->get(),
            'penanggulangan' => DataPenanggulangan::with('user')->whereIn('status', ['approved', 'published'])->latest()->get(),
            'desa' => DataDesa::with('user')->whereIn('status', ['approved', 'published'])->latest()->get(),
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
            'active_tab' => 'required|string', // ✅ Tambahkan validasi untuk tab aktif
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
        
        // ✅ Simpan tab aktif ke session sebelum redirect
        return back()->with('success', $message)->with('active_tab', $request->active_tab);
    }
}