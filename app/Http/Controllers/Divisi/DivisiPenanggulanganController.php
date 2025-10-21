<?php
namespace App\Http\Controllers\Divisi;

use App\Http\Controllers\Controller;
use App\Models\DataPenanggulangan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

// ✅ TAMBAHKAN USE STATEMENT INI
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\DataPenanggulanganImport;
use Illuminate\Support\Str;

class DivisiPenanggulanganController extends Controller
{
    public function index()
    {
        $currentUser = Auth::user();
        $userRoleName = $currentUser->roles->first()->name;
        $data = DataPenanggulangan::whereHas('user.roles', function ($query) use ($userRoleName) {
            $query->where('name', $userRoleName);
        })
        ->with('user')
        ->latest('periode')
        ->paginate(10);
        return view('divisi.penanggulangan.index', compact('data'));
    }

    public function create()
    {
        return view('divisi.penanggulangan.create');
    }

    public function store(Request $request)
    {
        $request->merge(['periode' => $request->periode . '-01']);
        $request->validate([
            'periode' => 'required|date',
            'jenis_bencana' => 'required|string|max:255',
            'jumlah_korban' => 'required|integer|min:0',
            'tindakan' => 'required|string',
        ]);
        $data = $request->all();
        $data['user_id'] = Auth::id();
        DataPenanggulangan::create($data);
        return redirect()->route('penanggulangan.index')->with('success', 'Data Penanggulangan berhasil ditambahkan.');
    }

    /**
     * ✅ PERBAIKAN: Memeriksa berdasarkan Role, bukan ID User
     */
    public function edit(DataPenanggulangan $data_penanggulangan)
    {
        $currentUserRoleName = Auth::user()->roles->first()->name;
        if (!$data_penanggulangan->user->hasRole($currentUserRoleName)) {
            abort(403, 'AKSES DITOLAK: Anda hanya bisa mengelola data dari divisi Anda sendiri.');
        }
        return view('divisi.penanggulangan.edit', ['data' => $data_penanggulangan]);
    }

    /**
     * ✅ PERBAIKAN: Memeriksa berdasarkan Role, bukan ID User
     */
    public function update(Request $request, DataPenanggulangan $data_penanggulangan)
    {
        $currentUserRoleName = Auth::user()->roles->first()->name;
        if (!$data_penanggulangan->user->hasRole($currentUserRoleName)) {
            abort(403, 'AKSES DITOLAK: Anda hanya bisa mengelola data dari divisi Anda sendiri.');
        }
        $request->merge(['periode' => $request->periode . '-01']);
        $request->validate([
            'periode' => 'required|date',
            'jenis_bencana' => 'required|string|max:255',
            'jumlah_korban' => 'required|integer|min:0',
            'tindakan' => 'required|string',
        ]);
        $data_penanggulangan->update($request->all());
        return redirect()->route('penanggulangan.index')->with('success', 'Data Penanggulangan berhasil diperbarui.');
    }

    /**
     * ✅ PERBAIKAN: Memeriksa berdasarkan Role, bukan ID User
     */
    public function destroy(DataPenanggulangan $data_penanggulangan)
    {
        $currentUserRoleName = Auth::user()->roles->first()->name;
        if (!$data_penanggulangan->user->hasRole($currentUserRoleName)) {
            abort(403, 'AKSES DITOLAK: Anda hanya bisa mengelola data dari divisi Anda sendiri.');
        }
        $data_penanggulangan->delete();
        return redirect()->route('penanggulangan.index')->with('success', 'Data Penanggulangan berhasil dihapus.');
    }

    // ✅ METHOD IMPORT BARU
    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls',
        ]);

        try {
            Excel::import(new DataPenanggulanganImport(), $request->file('file'));

            return redirect()->route('penanggulangan.index')->with('success', 'Data Penanggulangan berhasil di-import dari Excel.');

        } catch (\Maatwebsite\Excel\Validators\ValidationException $e) {
             $failures = $e->failures();
             $errorMessage = "Gagal import pada baris " . $failures[0]->row() . ". "
                           . "Error: " . $failures[0]->errors()[0]
                           . " (Nilai: '" . ($failures[0]->values()[$failures[0]->attribute()] ?? '') . "')";
             return redirect()->route('penanggulangan.index')->with('error', $errorMessage);
        } catch (\Exception $e) {
            return redirect()->route('penanggulangan.index')->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
}