<?php

namespace App\Imports;

use App\Models\DataPencegahan; // Pastikan namespace model Anda benar
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

// Perhatikan: Tidak ada 'use PhpOffice\PhpSpreadsheet\Shared\Date;' lagi

class DataPencegahanImport implements ToModel, WithHeadingRow, WithValidation
{
    /**
    * @param array $row Data dari satu baris di Excel
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        // Pastikan nama header di Excel Anda adalah:
        // periode, judul_kegiatan, lokasi, tanggal_kegiatan, laporan_singkat
        
        return new DataPencegahan([
            'periode'           => $row['periode'] . '-01', // Tambahkan '-01' untuk hari
            'judul_kegiatan'    => $row['judul_kegiatan'],
            'lokasi'            => $row['lokasi'],
            'tanggal_kegiatan'  => $row['tanggal_kegiatan'], // Langsung gunakan string Y-m-d
            'laporan_singkat'   => $row['laporan_singkat'],
            'user_id'           => Auth::id(), // Ambil ID user yang login
            'status'            => 'pending', // Set status default
        ]);
    }

    /**
     * Tentukan aturan validasi untuk data di dalam Excel.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            // Kolom 'periode' harus ada dan formatnya YYYY-MM
            'periode'           => 'required|date_format:Y-m',
            
            // Kolom 'judul_kegiatan' harus ada, berupa teks, maks 255 char
            'judul_kegiatan'    => 'required|string|max:255',
            
            // Kolom 'lokasi' harus ada, berupa teks, maks 255 char
            'lokasi'            => 'required|string|max:255',
            
            // Kolom 'tanggal_kegiatan' harus ada dan formatnya YYYY-MM-DD
            'tanggal_kegiatan'  => 'required|date_format:Y-m-d',
            
            // Kolom 'laporan_singkat' harus ada dan berupa teks
            'laporan_singkat'   => 'required|string',
        ];
    }
}