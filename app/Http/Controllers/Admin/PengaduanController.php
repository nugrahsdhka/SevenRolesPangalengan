<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Complaint;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class PengaduanController extends Controller
{
    /**
     * Daftar pengaduan — bisa dilihat semua role internal.
     */
    public function index(Request $request)
    {
        $query = Complaint::query()->visibleTo($request->user())->latest();

        if ($request->filled('status') && array_key_exists($request->status, Complaint::STATUS)) {
            $query->where('status', $request->status);
        }

        if ($request->filled('kategori') && array_key_exists($request->kategori, Complaint::KATEGORI)) {
            $query->where('kategori', $request->kategori);
        }

        if ($request->filled('q')) {
            $q = '%' . $request->q . '%';
            $query->where(function ($w) use ($q) {
                $w->where('ticket_code', 'like', $q)
                  ->orWhere('judul', 'like', $q)
                  ->orWhere('desa_kelurahan', 'like', $q)
                  ->orWhere('puskesmas', 'like', $q)
                  ->orWhere('posyandu', 'like', $q)
                  ->orWhere('nama', 'like', $q);
            });
        }

        $complaints = $query->paginate(15)->withQueryString();

        $counts = Complaint::visibleTo($request->user())->selectRaw('status, COUNT(*) as total')
            ->groupBy('status')
            ->pluck('total', 'status');

        return view('pengaduan.index', [
            'complaints' => $complaints,
            'counts'     => $counts,
            'kategori'   => Complaint::KATEGORI,
            'statuses'   => Complaint::STATUS,
        ]);
    }

    /**
     * Detail pengaduan — bisa dilihat semua role internal.
     */
    public function show(Complaint $complaint)
    {
        abort_unless(auth()->user()->canViewComplaint($complaint), 403,
            'Pengaduan ini bukan untuk puskesmas Anda.');

        $complaint->load('penangan:id,name');

        return view('pengaduan.show', [
            'complaint'  => $complaint,
            'statuses'   => Complaint::STATUS,
            'canRespond' => auth()->user()->canRespondTo($complaint),
        ]);
    }

    /**
     * Ubah status & beri tanggapan — khusus petugas dari puskesmas yang diadukan.
     */
    public function update(Request $request, Complaint $complaint)
    {
        abort_unless($request->user()->canRespondTo($complaint), 403,
            'Pengaduan ini bukan untuk puskesmas Anda.');

        $data = $request->validate([
            'status'    => ['required', Rule::in(array_keys(Complaint::STATUS))],
            'tanggapan' => ['nullable', 'string', 'max:2000'],
        ]);

        $complaint->status = $data['status'];

        if (($data['tanggapan'] ?? null) !== $complaint->tanggapan) {
            $complaint->tanggapan = $data['tanggapan'] ?? null;
            $complaint->ditanggapi_at = $complaint->tanggapan ? now() : null;
        }

        $complaint->ditangani_oleh = $request->user()->id;
        $complaint->save();

        return redirect()->route('complaints.show', $complaint)
            ->with('success', 'Pengaduan berhasil diperbarui.');
    }

    /**
     * Hapus pengaduan — khusus IT Admin.
     */
    public function destroy(Complaint $complaint)
    {
        $complaint->delete();

        return redirect()->route('complaints.index')
            ->with('success', 'Pengaduan berhasil dihapus.');
    }
}
