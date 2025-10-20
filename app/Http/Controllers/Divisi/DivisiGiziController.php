<?php

namespace App\Http\Controllers\Divisi;

use App\Http\Controllers\Controller;
use App\Models\DataGizi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

// ✅ TAMBAHKAN 'USE' STATEMENT INI
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\DataGiziImport;

class DivisiGiziController extends Controller
{
    /**
     * Menampilkan data dari semua user dengan role yang sama.
     */
    public function index()
    {
        $currentUser = Auth::user();
        $userRoleName = $currentUser->roles->first()->name;

        $data = DataGizi::whereHas('user.roles', function ($query) use ($userRoleName) {
            $query->where('name', $userRoleName);
        })
        ->with('user')
        ->latest('periode')
        ->paginate(10);

        return view('divisi.gizi.index', compact('data'));
    }

    public function create()
    {
        return view('divisi.gizi.create');
    }

    public function store(Request $request)
    {
        $request->merge(['periode' => $request->periode . '-01']);
        $request->validate([
            'periode' => 'required|date',
            'judul' => 'required|string|max:255',
            'deskripsi' => 'required|string',
        ]);
        $data = $request->all();
        $data['user_id'] = Auth::id();
        DataGizi::create($data);
        return redirect()->route('gizi.index')->with('success', 'Data Gizi berhasil ditambahkan.');
    }

    /**
     * ✅ PERBAIKAN: Memeriksa berdasarkan Role, bukan ID User
     */
    public function edit(DataGizi $data_gizi)
    {
        $currentUserRoleName = Auth::user()->roles->first()->name;
        if (!$data_gizi->user->hasRole($currentUserRoleName)) {
            abort(403, 'AKSES DITOLAK: Anda hanya bisa mengelola data dari divisi Anda sendiri.');
        }

        return view('divisi.gizi.edit', ['data' => $data_gizi]);
    }

    /**
     * ✅ PERBAIKAN: Memeriksa berdasarkan Role, bukan ID User
     */
    public function update(Request $request, DataGizi $data_gizi)
    {
        $currentUserRoleName = Auth::user()->roles->first()->name;
        if (!$data_gizi->user->hasRole($currentUserRoleName)) {
            abort(403, 'AKSES DITOLAK: Anda hanya bisa mengelola data dari divisi Anda sendiri.');
        }
        
        $request->merge(['periode' => $request->periode . '-01']);
        $request->validate([
            'periode' => 'required|date',
            'judul' => 'required|string|max:255',
            'deskripsi' => 'required|string',
        ]);
        $data_gizi->update($request->all());
        return redirect()->route('gizi.index')->with('success', 'Data Gizi berhasil diperbarui.');
    }

    /**
     * ✅ PERBAIKAN: Memeriksa berdasarkan Role, bukan ID User
     */
    public function destroy(DataGizi $data_gizi)
    {
        $currentUserRoleName = Auth::user()->roles->first()->name;
        if (!$data_gizi->user->hasRole($currentUserRoleName)) {
            abort(403, 'AKSES DITOLAK: Anda hanya bisa mengelola data dari divisi Anda sendiri.');
        }

        $data_gizi->delete();
        return redirect()->route('gizi.index')->with('success', 'Data Gizi berhasil dihapus.');
    }

    // ✅ ==========================================================
    //    TAMBAHKAN METHOD BARU INI UNTUK MENGHANDLE IMPORT EXCEL
    // =============================================================
    public function import(Request $request)
    {
        // 1. Validasi form HANYA untuk file
        $request->validate([
            'file' => 'required|mimes:xlsx,xls',
            // 'periode' => 'required|date_format:Y-m', // <-- ✅ HAPUS INI
        ]);

        try {
            $file = $request->file('file');
            
            // $periode = $request->input('periode') . '-01'; // <-- ✅ HAPUS INI

            // 3. Jalankan proses import tanpa mengirim periode
            Excel::import(new DataGiziImport(), $file); // <-- ✅ UBAH INI

            // 4. Redirect kembali ke halaman index dengan pesan sukses
            return redirect()->route('gizi.index')
                ->with('success', 'Data Gizi berhasil di-import dari Excel.');

        } catch (\Maatwebsite\Excel\Validators\ValidationException $e) {
             $failures = $e->failures();
             // Ini sekarang akan menangkap error validasi DARI DALAM EXCEL
             // (cth: jika format 'periode' salah atau 'judul' kosong)
             $errorMessage = "Gagal import: " 
                           . $failures[0]->errors()[0] 
                           . " (pada baris " . $failures[0]->row() . ").";
             return redirect()->route('gizi.index')->with('error', $errorMessage);
        } catch (\Exception $e) {
            // Tangani jika ada error umum lainnya
            return redirect()->route('gizi.index')
                ->with('error', 'Terjadi kesalahan saat import data: ' . $e->getMessage());
        }
    }
}