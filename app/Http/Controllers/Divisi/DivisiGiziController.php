<?php

namespace App\Http\Controllers\Divisi;

use App\Http\Controllers\Controller;
use App\Models\DataGizi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

// 'Use' statement untuk Excel
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\DataGiziImport;

class DivisiGiziController extends Controller
{
    public function index()
    {
        $currentUser = Auth::user();
        $userRoleName = $currentUser->roles->first()->name;

        // Query untuk mengambil data agregat, dikelompokkan berdasarkan desa DAN status
        $aggregatedData = DataGizi::whereHas('user.roles', function ($query) use ($userRoleName) {
                $query->where('name', $userRoleName);
            })
            ->select(
                'desa_kelurahan',
                'status', // <-- TAMBAHKAN STATUS DI SELECT
                DB::raw('COUNT(*) as total_anak'),
                DB::raw('SUM(CASE WHEN zs_tb_u < -2 THEN 1 ELSE 0 END) as total_kasus_stunting'),
                DB::raw('SUM(zs_tb_u) as sum_zs_tbu'),
                DB::raw('MAX(created_at) as tanggal_input_terbaru')
            )
            ->groupBy('desa_kelurahan', 'status') // <-- TAMBAHKAN STATUS DI GROUP BY
             // <-- TAMBAHKAN ORDER BERDASARKAN STATUS (pending dulu)
            ->orderByRaw("FIELD(status, 'pending', 'approved', 'published')")
            ->orderBy('tanggal_input_terbaru', 'desc')
            ->paginate(15);

        return view('divisi.gizi.index', compact('aggregatedData'));
    }

    public function create()
    {
        return view('divisi.gizi.create');
    }

    public function store(Request $request)
    {
        // Validasi disesuaikan dengan kolom tabel 'data_gizis'
        $request->validate([
            'desa_kelurahan' => 'required|string|max:100',
            'berat' => 'required|numeric|min:0|max:999.99',
            'tinggi' => 'required|numeric|min:0|max:999.99',
            'status_bb_u' => 'nullable|string|max:50',
            'zs_bb_u' => 'nullable|numeric|min:-99.99|max:99.99',
            'status_tb_u' => 'nullable|string|max:50',
            'zs_tb_u' => 'nullable|numeric|min:-99.99|max:99.99',
            'status_bb_tb' => 'nullable|string|max:50',
            'zs_bb_tb' => 'nullable|numeric|min:-99.99|max:99.99',
        ]);

        $data = $request->all();
        $data['user_id'] = Auth::id();

        // Mutator di Model otomatis akan uppercase & trim desa_kelurahan
        DataGizi::create($data);

        return redirect()->route('gizi.index')->with('success', 'Data Gizi berhasil ditambahkan.');
    }

    public function edit(DataGizi $data_gizi)
    {
        $currentUserRoleName = Auth::user()->roles->first()->name;
        if (!$data_gizi->user->hasRole($currentUserRoleName)) {
            abort(403, 'AKSES DITOLAK: Anda hanya bisa mengelola data dari divisi Anda sendiri.');
        }
        return view('divisi.gizi.edit', ['data' => $data_gizi]);
    }

    public function update(Request $request, DataGizi $data_gizi)
    {
        $currentUserRoleName = Auth::user()->roles->first()->name;
        if (!$data_gizi->user->hasRole($currentUserRoleName)) {
            abort(403, 'AKSES DITOLAK: Anda hanya bisa mengelola data dari divisi Anda sendiri.');
        }

        $request->validate([
            'desa_kelurahan' => 'required|string|max:100',
            'berat' => 'required|numeric|min:0|max:999.99',
            'tinggi' => 'required|numeric|min:0|max:999.99',
            'status_bb_u' => 'nullable|string|max:50',
            'zs_bb_u' => 'nullable|numeric|min:-99.99|max:99.99',
            'status_tb_u' => 'nullable|string|max:50',
            'zs_tb_u' => 'nullable|numeric|min:-99.99|max:99.99',
            'status_bb_tb' => 'nullable|string|max:50',
            'zs_bb_tb' => 'nullable|numeric|min:-99.99|max:99.99',
        ]);

        // Mutator di Model otomatis akan uppercase & trim desa_kelurahan
        $data_gizi->update($request->all());

        // Redirect kembali ke halaman detail desa yang sama
        return redirect()->route('gizi.detail', ['desa' => $data_gizi->desa_kelurahan])
                         ->with('success', 'Data Gizi individual berhasil diperbarui.');
    }

    public function destroy(DataGizi $data_gizi)
    {
        $currentUserRoleName = Auth::user()->roles->first()->name;
        if (!$data_gizi->user->hasRole($currentUserRoleName)) {
            abort(403, 'AKSES DITOLAK: Anda hanya bisa mengelola data dari divisi Anda sendiri.');
        }

        $desaKelurahan = $data_gizi->desa_kelurahan; // Simpan nama desa sebelum dihapus
        $data_gizi->delete();

        // Cek apakah masih ada data untuk desa ini
        $remainingData = DataGizi::where('desa_kelurahan', $desaKelurahan)
                                  ->whereHas('user.roles', function ($query) use ($currentUserRoleName) {
                                        $query->where('name', $currentUserRoleName);
                                    })
                                  ->exists();

        // Jika tidak ada data tersisa, redirect ke index, jika masih ada, redirect ke detail
        if (!$remainingData) {
            return redirect()->route('gizi.index')->with('success', 'Data Gizi terakhir untuk desa '.$desaKelurahan.' berhasil dihapus.');
        } else {
            return redirect()->route('gizi.detail', ['desa' => $desaKelurahan])->with('success', 'Data Gizi individual berhasil dihapus.');
        }
    }

    public function destroyAggregated(Request $request)
    {
        // 1. Validasi input dari form di index.blade.php
        $request->validate([
            'desa_kelurahan' => 'required|string',
            'status' => ['required', Rule::in(['pending', 'approved', 'published'])], // Pastikan status valid
        ]);

        $currentUser = Auth::user();
        // Dapatkan nama role user saat ini (misal: "Divisi Gizi")
        $userRoleName = $currentUser->roles->first()->name; 
        $desaKelurahan = strtoupper(trim($request->desa_kelurahan)); // Normalisasi nama desa
        $status = $request->status;

        try {
            // 2. Hapus data DataGizi yang cocok
            $deletedCount = DataGizi::where('desa_kelurahan', $desaKelurahan)
                ->where('status', $status)
                // Pastikan hanya menghapus data yang diinput oleh user dengan role yang sama
                ->whereHas('user.roles', function ($query) use ($userRoleName) { 
                    $query->where('name', $userRoleName);
                })
                ->delete();

            // 3. Beri feedback ke user
            if ($deletedCount > 0) {
                return redirect()->route('gizi.index')
                       ->with('success', "{$deletedCount} data Gizi dengan status '{$status}' untuk desa {$desaKelurahan} berhasil dihapus.");
            } else {
                // Ini terjadi jika data sudah dihapus di tab lain, atau ada percobaan hapus data divisi lain
                return redirect()->route('gizi.index')
                       ->with('error', "Tidak ada data Gizi dengan status '{$status}' untuk desa {$desaKelurahan} yang dapat dihapus oleh Anda.");
            }

        } catch (\Exception $e) {
            // Tangkap jika ada error database atau lainnya
            Log::error('Error deleting aggregated gizi data: ' . $e->getMessage()); // Log error untuk debug
            return redirect()->route('gizi.index')
                   ->with('error', 'Terjadi kesalahan saat menghapus data. Silakan coba lagi.');
        }
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv|max:2048', // Tambahkan max size
        ]);

        try {
            $file = $request->file('file');
            Excel::import(new DataGiziImport(), $file);

            return redirect()->route('gizi.index')
                ->with('success', 'Data Gizi berhasil di-import dari Excel.');

        } catch (\Maatwebsite\Excel\Validators\ValidationException $e) {
             $failures = $e->failures();
             // Ambil pesan error pertama saja untuk ditampilkan
             $firstFailure = $failures[0];
             $errorMessage = "Gagal import: "
                             . $firstFailure->errors()[0]
                             . " (pada baris " . $firstFailure->row() . ", kolom " . $firstFailure->attribute() . "). Pastikan format sesuai.";
             return redirect()->route('gizi.index')->with('error', $errorMessage);
        } catch (\Exception $e) {
            // Tangkap error umum lainnya
            Log::error('Error importing Excel: ' . $e->getMessage()); // Log error untuk debugging
            return redirect()->route('gizi.index')
                ->with('error', 'Terjadi kesalahan saat import data. Pastikan format file benar dan coba lagi.');
        }
    }

    public function detail($desa)
    {
        $currentUser = Auth::user();
        $userRoleName = $currentUser->roles->first()->name;

        $desaKelurahan = urldecode($desa);

        $dataGizi = DataGizi::whereHas('user.roles', function ($query) use ($userRoleName) {
                $query->where('name', $userRoleName);
            })
            ->where('desa_kelurahan', strtoupper($desaKelurahan))
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        if ($dataGizi->isEmpty() && $dataGizi->currentPage() > 1) {
            return redirect()->route('gizi.detail', ['desa' => $desa, 'page' => 1]);
        }

        // Jika tidak ada data sama sekali untuk user ini di desa ini
        if ($dataGizi->total() === 0) {
             // Redirect ke index dengan pesan error jika tidak ada data sama sekali
             return redirect()->route('gizi.index')->with('error', 'Tidak ada data Gizi ditemukan untuk desa ' . $desaKelurahan . ' yang Anda kelola.');
        }


        $statistik = DataGizi::whereHas('user.roles', function ($query) use ($userRoleName) {
                $query->where('name', $userRoleName);
            })
            ->where('desa_kelurahan', strtoupper($desaKelurahan))
            ->selectRaw('
                COUNT(*) as total_anak,
                SUM(CASE WHEN zs_tb_u < -2 THEN 1 ELSE 0 END) as total_stunting,
                SUM(CASE WHEN zs_bb_u < -2 THEN 1 ELSE 0 END) as total_gizi_kurang,
                SUM(CASE WHEN zs_bb_tb < -2 THEN 1 ELSE 0 END) as total_kurus,
                AVG(zs_tb_u) as avg_zs_tbu,
                AVG(zs_bb_u) as avg_zs_bbu,
                AVG(zs_bb_tb) as avg_zs_bbtb,
                AVG(berat) as avg_berat,
                AVG(tinggi) as avg_tinggi
            ')
            ->first();

        return view('divisi.gizi.detail', compact('dataGizi', 'desaKelurahan', 'statistik'));
    }
}

