<?php

namespace App\Http\Controllers;

use App\Models\DataDesa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Carbon\Carbon;
use Illuminate\Support\Str;

class DivisiDesaController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $allData = DataDesa::where('user_id', $user->id)->latest('periode')->paginate(10);

        // Menggunakan format Y-m untuk perbandingan
        $currentPeriod = Carbon::now()->format('Y-m');
        $canCreate = !DataDesa::where('user_id', $user->id)
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
        // ✅ PERBAIKAN: Normalisasi input nama_desa dan periode SEBELUM validasi
        $request->merge([
            'nama_desa' => Str::title($request->nama_desa),
            'periode' => $request->periode . '-01' // Mengubah '2025-10' menjadi '2025-10-01'
        ]);

        $request->validate([
            'periode' => 'required|date',
            'nama_desa' => [
                'required',
                'string',
                'max:255',
                Rule::unique('data_desas')->where(function ($query) use ($request) {
                    return $query->where('periode', $request->periode)
                                 ->where('user_id', Auth::id());
                }),
            ],
            'jumlah_penduduk' => 'required|integer|min:0',
            'luas_wilayah' => 'required|integer|min:0',
            'potensi_desa' => 'required|string',
            'jumlah_kasus_stunting' => 'nullable|integer|min:0',
        ], [
            'nama_desa.unique' => 'Data untuk desa ini pada periode yang dipilih sudah ada. Silakan edit data yang sudah ada.'
        ]);

        $data = $request->all();
        $data['user_id'] = Auth::id();
        $data['jumlah_kasus_stunting'] = $request->input('jumlah_kasus_stunting', 0);
        DataDesa::create($data);

        return redirect()->route('desa.index')->with('success', 'Data Desa berhasil ditambahkan.');
    }

    public function edit(DataDesa $data_desa)
    {
        if ($data_desa->user_id !== Auth::id()) {
            abort(403, 'AKSES DITOLAK');
        }
        return view('divisi.desa.edit', ['data' => $data_desa]);
    }

    public function update(Request $request, DataDesa $data_desa)
    {
        if ($data_desa->user_id !== Auth::id()) {
            abort(403, 'AKSES DITOLAK');
        }

        // ✅ PERBAIKAN: Normalisasi input nama_desa dan periode SEBELUM validasi
        $request->merge([
            'nama_desa' => Str::title($request->nama_desa),
            'periode' => $request->periode . '-01'
        ]);
        
        $request->validate([
            'periode' => 'required|date',
            'nama_desa' => [
                'required',
                'string',
                'max:255',
                Rule::unique('data_desas')->ignore($data_desa->id)->where(function ($query) use ($request) {
                    return $query->where('periode', $request->periode)
                                 ->where('user_id', Auth::id());
                }),
            ],
            'jumlah_penduduk' => 'required|integer|min:0',
            'luas_wilayah' => 'required|integer|min:0',
            'potensi_desa' => 'required|string',
            'jumlah_kasus_stunting' => 'nullable|integer|min:0',
        ], [
            'nama_desa.unique' => 'Data untuk desa ini pada periode yang dipilih sudah ada.'
        ]);

        $data = $request->all();
        $data['jumlah_kasus_stunting'] = $request->input('jumlah_kasus_stunting', 0);
        $data_desa->update($data);

        return redirect()->route('desa.index')->with('success', 'Data Desa berhasil diperbarui.');
    }

    public function destroy(DataDesa $data_desa)
    {
        if ($data_desa->user_id !== Auth::id()) {
            abort(403, 'AKSES DITOLAK');
        }
        $data_desa->delete();
        return redirect()->route('desa.index')->with('success', 'Data Desa berhasil dihapus.');
    }
}