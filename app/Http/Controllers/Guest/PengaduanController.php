<?php

namespace App\Http\Controllers\Guest;

use App\Http\Controllers\Controller;
use App\Models\Complaint;
use App\Models\Faskes;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class PengaduanController extends Controller
{
    /**
     * Form pengaduan publik (tanpa login).
     */
    public function create()
    {
        $desaList = Faskes::whereNotNull('desa_kelurahan')
            ->distinct()
            ->orderBy('desa_kelurahan')
            ->pluck('desa_kelurahan');

        return view('public.pengaduan.create', [
            'desaList' => $desaList,
            'kategori' => Complaint::KATEGORI,
        ]);
    }

    /**
     * Simpan pengaduan baru, lalu arahkan ke halaman cek status dengan kode tiket.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'nama'           => ['nullable', 'string', 'max:100'],
            'kontak'         => ['nullable', 'string', 'max:100'],
            'desa_kelurahan' => ['required', 'string', 'max:100'],
            'kategori'       => ['required', Rule::in(array_keys(Complaint::KATEGORI))],
            'judul'          => ['required', 'string', 'min:5', 'max:150'],
            'isi'            => ['required', 'string', 'min:20', 'max:3000'],
        ]);

        // Honeypot anti-spam: field "website" disembunyikan dari manusia.
        // Kalau terisi, anggap bot — pura-pura sukses tanpa menyimpan.
        if ($request->filled('website')) {
            return redirect()->route('pengaduan.create')
                ->with('success', 'Pengaduan Anda telah diterima.');
        }

        $complaint = Complaint::create($data + [
            'ticket_code' => Complaint::generateTicketCode(),
            'status'      => 'baru',
        ]);

        return redirect()
            ->route('pengaduan.track', ['kode' => $complaint->ticket_code])
            ->with('success', 'Pengaduan berhasil dikirim. Simpan kode tiket Anda untuk memantau status.');
    }

    /**
     * Cek status pengaduan berdasarkan kode tiket.
     * Data pribadi pelapor (nama & kontak) tidak ditampilkan di sini.
     */
    public function track(Request $request)
    {
        $kode = strtoupper(trim((string) $request->query('kode', '')));
        $complaint = null;

        if ($kode !== '') {
            $complaint = Complaint::where('ticket_code', $kode)->first();
        }

        return view('public.pengaduan.track', [
            'kode'      => $kode,
            'complaint' => $complaint,
            'searched'  => $kode !== '',
        ]);
    }
}
