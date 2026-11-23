<?php

namespace App\Http\Controllers\Guest;

use App\Http\Controllers\Controller;
use App\Models\Complaint;
use App\Models\Faskes;
use App\Support\ComplaintNotifier;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class PengaduanController extends Controller
{
    /**
     * Form pengaduan publik (tanpa login).
     */
    public function create()
    {
        // Pohon pilihan: puskesmas -> desa -> daftar posyandu.
        // Sumbernya tabel faskes (diisi dari database/seeders/data/posyandu_faskes.csv).
        $wilayah = Faskes::query()
            ->where('jenis', 'Posyandu')
            ->where('is_active', true)
            ->whereNotNull('puskesmas')
            ->whereNotNull('desa_kelurahan')
            ->orderBy('puskesmas')
            ->orderBy('desa_kelurahan')
            ->orderBy('nama_faskes')
            ->get(['puskesmas', 'desa_kelurahan', 'nama_faskes'])
            ->groupBy('puskesmas')
            ->map(fn ($perPuskesmas) => $perPuskesmas
                ->groupBy('desa_kelurahan')
                ->map(fn ($perDesa) => $perDesa->pluck('nama_faskes')->values()));

        return view('public.pengaduan.create', [
            'wilayah'  => $wilayah,
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
            'puskesmas'      => ['required', 'string', 'max:100'],
            'desa_kelurahan' => ['required', 'string', 'max:100'],
            'posyandu'       => ['required', 'string', 'max:150'],
            'kategori'       => ['required', Rule::in(array_keys(Complaint::KATEGORI))],
            'judul'          => ['required', 'string', 'min:5', 'max:150'],
            'isi'            => ['required', 'string', 'min:20', 'max:3000'],
        ]);

        // Pilihan harus berurutan dan saling cocok: posyandu harus benar-benar
        // berada di desa tsb, dan desa tsb di bawah puskesmas tsb.
        $posyandu = Faskes::query()
            ->where('jenis', 'Posyandu')
            ->where('is_active', true)
            ->where('puskesmas', $data['puskesmas'])
            ->where('desa_kelurahan', $data['desa_kelurahan'])
            ->where('nama_faskes', $data['posyandu'])
            ->first();

        if (! $posyandu) {
            throw ValidationException::withMessages([
                'posyandu' => 'Pilihan puskesmas, desa, dan posyandu tidak sesuai. Silakan pilih ulang secara berurutan.',
            ]);
        }

        // Simpan nilai kanonik dari database (bukan teks mentah dari request).
        $data['puskesmas']      = $posyandu->puskesmas;
        $data['desa_kelurahan'] = $posyandu->desa_kelurahan;
        $data['posyandu']       = $posyandu->nama_faskes;

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

        // Email ke puskesmas yang dipilih + camat + IT Admin. Dijalankan setelah
        // respons terkirim (warga tidak menunggu SMTP) dan tidak pernah menggagalkan pengaduan.
        defer(fn () => ComplaintNotifier::notify($complaint));

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
