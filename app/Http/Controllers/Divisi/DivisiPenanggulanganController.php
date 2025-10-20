<?php
namespace App\Http\Controllers\Divisi;

use App\Http\Controllers\Controller;
use App\Models\DataPenanggulangan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
}