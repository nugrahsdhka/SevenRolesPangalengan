<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Complaint;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class PengaduanController extends Controller
{
    /**
     * Daftar pengaduan aktif (baru, diproses, selesai, ditolak) — bisa dilihat semua role internal.
     * Pengaduan yang sudah dibuang ke sampah tidak ikut tampil di sini.
     */
    public function index(Request $request)
    {
        $query = Complaint::query()->aktif()->visibleTo($request->user())->latest();

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

        $counts = Complaint::aktif()->visibleTo($request->user())->selectRaw('status, COUNT(*) as total')
            ->groupBy('status')
            ->pluck('total', 'status');

        $trashCount = $this->canOpenTrash($request)
            ? Complaint::dibuang()->visibleTo($request->user())->count()
            : null;

        return view('pengaduan.index', [
            'complaints' => $complaints,
            'counts'     => $counts,
            'kategori'   => Complaint::KATEGORI,
            'statuses'   => Complaint::STATUS,
            'trashCount' => $trashCount,
        ]);
    }

    /**
     * Halaman sampah — hanya IT Admin, Camat, dan petugas puskesmas (Divisi Gizi).
     */
    public function trash(Request $request)
    {
        abort_unless($this->canOpenTrash($request), 403);

        $query = Complaint::query()->dibuang()->visibleTo($request->user())->with('pembuang:id,name')
            ->orderByDesc('dibuang_at');

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

        return view('pengaduan.trash', [
            'complaints' => $query->paginate(15)->withQueryString(),
        ]);
    }

    /**
     * Detail pengaduan — bisa dilihat semua role internal (termasuk yang ada di sampah).
     */
    public function show(Complaint $complaint)
    {
        abort_unless(auth()->user()->canViewComplaint($complaint), 403,
            'Pengaduan ini bukan untuk puskesmas Anda.');

        $complaint->load('penangan:id,name', 'pembuang:id,name');

        return view('pengaduan.show', [
            'complaint'    => $complaint,
            'statuses'     => Complaint::STATUS,
            // Pengaduan di sampah tidak bisa ditanggapi lagi sebelum dipulihkan.
            'canRespond'   => ! $complaint->isDibuang() && auth()->user()->canRespondTo($complaint),
            'canManageTrash' => auth()->user()->canManageComplaintTrash($complaint),
        ]);
    }

    /**
     * Ubah status & beri tanggapan — khusus petugas dari puskesmas yang diadukan.
     */
    public function update(Request $request, Complaint $complaint)
    {
        abort_unless($request->user()->canRespondTo($complaint), 403,
            'Pengaduan ini bukan untuk puskesmas Anda.');

        if ($complaint->isDibuang()) {
            return redirect()->route('complaints.show', $complaint)
                ->with('error', 'Pengaduan ada di sampah. Pulihkan dulu sebelum diubah.');
        }

        $data = $request->validate([
            'status'    => ['required', Rule::in(array_keys(Complaint::STATUS))],
            'tanggapan' => ['nullable', 'string', 'max:2000'],
        ]);

        // Hitung mundur 3 bulan (hapus otomatis) dimulai dari saat status berubah.
        if ($data['status'] !== $complaint->status) {
            $complaint->status_diperbarui_at = now();
        }

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
     * Buang ke sampah — hanya pengaduan selesai/ditolak. Data tetap ada di database
     * dan warga tetap bisa mengeceknya lewat kode tiket.
     */
    public function moveToTrash(Request $request, Complaint $complaint)
    {
        abort_unless($request->user()->canManageComplaintTrash($complaint), 403,
            'Anda tidak berhak membuang pengaduan ini.');

        if (! $complaint->isStatusAkhir()) {
            return back()->with('error', 'Hanya pengaduan berstatus Selesai atau Ditolak yang bisa dibuang ke sampah.');
        }

        if (! $complaint->isDibuang()) {
            $complaint->forceFill([
                'dibuang_at'   => now(),
                'dibuang_oleh' => $request->user()->id,
            ])->save();
        }

        return redirect()->route('complaints.index')
            ->with('success', 'Pengaduan dipindahkan ke sampah.');
    }

    /**
     * Pulihkan dari sampah ke daftar pengaduan.
     */
    public function restore(Request $request, Complaint $complaint)
    {
        abort_unless($request->user()->canManageComplaintTrash($complaint), 403,
            'Anda tidak berhak memulihkan pengaduan ini.');

        $complaint->forceFill(['dibuang_at' => null, 'dibuang_oleh' => null])->save();

        return redirect()->route('complaints.trash')
            ->with('success', 'Pengaduan dipulihkan ke daftar pengaduan.');
    }

    /**
     * Hapus permanen — hanya untuk pengaduan yang sudah ada di sampah.
     */
    public function forceDelete(Request $request, Complaint $complaint)
    {
        abort_unless($request->user()->canManageComplaintTrash($complaint), 403,
            'Anda tidak berhak menghapus pengaduan ini.');

        if (! $complaint->isDibuang()) {
            return back()->with('error', 'Buang pengaduan ke sampah dulu sebelum menghapus permanen.');
        }

        $complaint->delete();

        return redirect()->route('complaints.trash')
            ->with('success', 'Pengaduan dihapus permanen.');
    }

    private function canOpenTrash(Request $request): bool
    {
        return $request->user()->hasAnyRole(['IT Admin', 'Camat', 'Divisi Gizi']);
    }
}
