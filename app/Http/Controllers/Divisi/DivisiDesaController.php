<?php

namespace App\Http\Controllers\Divisi;

use App\Http\Controllers\Controller;
use App\Models\DataDesa;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Carbon\Carbon;
use Illuminate\Support\Str;

// ✅ TAMBAHKAN USE STATEMENT INI
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\DataDesaImport;

class DivisiDesaController extends Controller
{
    public function index()
    {
        $currentUser = Auth::user();
        $userRoleName = $currentUser->roles->first()->name;

        $allData = DataDesa::whereHas('user.roles', function ($query) use ($userRoleName) {
            $query->where('name', $userRoleName);
        })
        ->with('user')
        ->latest('periode')
        ->paginate(10);
        
        $currentPeriod = Carbon::now()->format('Y-m');
        
        $canCreate = !DataDesa::whereHas('user.roles', function ($query) use ($userRoleName) {
                                $query->where('name', $userRoleName);
                            })
                            ->whereRaw("DATE_FORMAT(periode, '%Y-%m') = ?", [$currentPeriod])
                            ->exists();

        return view('divisi.desa.index', [
            'data' => $allData,
            'canCreate' => $canCreate,
        ]);
    }

    public function create()
    {
        return view('divisi.desa.create');
    }

    public function store(Request $request)
    {
        $request->merge([
            'nama_desa' => Str::title($request->nama_desa),
            'periode' => $request->periode . '-01'
        ]);
        
        $userRoleName = Auth::user()->roles->first()->name;
        $userIdsInSameRole = User::role($userRoleName)->pluck('id');

        $request->validate([
            'periode' => 'required|date',
            'nama_desa' => [
                'required', 'string', 'max:255',
                Rule::unique('data_desas')->where(function ($query) use ($request, $userIdsInSameRole) {
                    return $query->where('periode', $request->periode)
                                   ->whereIn('user_id', $userIdsInSameRole);
                }),
            ],
            'jumlah_penduduk' => 'required|integer|min:0',
            'luas_wilayah' => 'required|integer|min:0',
            'potensi_desa' => 'required|string',
            'jumlah_kasus_stunting' => 'nullable|integer|min:0',
        ], [
            'nama_desa.unique' => 'Data untuk desa ini pada periode yang dipilih sudah diinput oleh rekan Anda.'
        ]);

        $data = $request->all();
        $data['user_id'] = Auth::id();
        $data['jumlah_kasus_stunting'] = $request->input('jumlah_kasus_stunting', 0);
        DataDesa::create($data);

        return redirect()->route('desa.index')->with('success', 'Data Desa berhasil ditambahkan.');
    }

    public function edit(DataDesa $data_desa)
    {
        $currentUserRoleName = Auth::user()->roles->first()->name;
        if (!$data_desa->user->hasRole($currentUserRoleName)) {
            abort(403, 'AKSES DITOLAK: Anda hanya bisa mengelola data dari divisi Anda sendiri.');
        }
        return view('divisi.desa.edit', ['data' => $data_desa]);
    }

    public function update(Request $request, DataDesa $data_desa)
    {
        $currentUserRoleName = Auth::user()->roles->first()->name;
        if (!$data_desa->user->hasRole($currentUserRoleName)) {
            abort(403, 'AKSES DITOLAK: Anda hanya bisa mengelola data dari divisi Anda sendiri.');
        }

        $request->merge([
            'nama_desa' => Str::title($request->nama_desa),
            'periode' => $request->periode . '-01'
        ]);
        
        $userRoleName = Auth::user()->roles->first()->name;
        $userIdsInSameRole = User::role($userRoleName)->pluck('id');
        
        $request->validate([
            'periode' => 'required|date',
            'nama_desa' => [
                'required', 'string', 'max:255',
                Rule::unique('data_desas')->ignore($data_desa->id)->where(function ($query) use ($request, $userIdsInSameRole) {
                    return $query->where('periode', $request->periode)
                                   ->whereIn('user_id', $userIdsInSameRole);
                }),
            ],
            'jumlah_penduduk' => 'required|integer|min:0',
            'luas_wilayah' => 'required|integer|min:0',
            'potensi_desa' => 'required|string',
            'jumlah_kasus_stunting' => 'nullable|integer|min:0',
        ], [
            'nama_desa.unique' => 'Data untuk desa ini pada periode yang dipilih sudah diinput oleh rekan Anda.'
        ]);

        $data = $request->all();
        $data['jumlah_kasus_stunting'] = $request->input('jumlah_kasus_stunting', 0);
        $data_desa->update($data);

        return redirect()->route('desa.index')->with('success', 'Data Desa berhasil diperbarui.');
    }

    public function destroy(DataDesa $data_desa)
    {
        $currentUserRoleName = Auth::user()->roles->first()->name;
        if (!$data_desa->user->hasRole($currentUserRoleName)) {
            abort(403, 'AKSES DITOLAK: Anda hanya bisa mengelola data dari divisi Anda sendiri.');
        }
        $data_desa->delete();
        return redirect()->route('desa.index')->with('success', 'Data Desa berhasil dihapus.');
    }

    // ✅ METHOD IMPORT BARU
    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls',
        ]);

        try {
            Excel::import(new DataDesaImport(), $request->file('file'));

            return redirect()->route('desa.index')->with('success', 'Data Desa berhasil di-import dari Excel.');

        } catch (\Maatwebsite\Excel\Validators\ValidationException $e) {
             $failures = $e->failures();
             $errorMessage = "Gagal import pada baris " . $failures[0]->row() . ". "
                           . "Error: " . $failures[0]->errors()[0]
                           . " (Nilai: '" . ($failures[0]->values()[$failures[0]->attribute()] ?? '') . "')";
             return redirect()->route('desa.index')->with('error', $errorMessage);
        } catch (\Exception $e) {
            // Tangani error duplikasi database atau error lainnya
            if (Str::contains($e->getMessage(), 'Duplicate entry')) {
                return redirect()->route('desa.index')->with('error', 'Gagal import: Terdapat data desa duplikat untuk periode yang sama di dalam file Excel Anda.');
            }
            return redirect()->route('desa.index')->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
}