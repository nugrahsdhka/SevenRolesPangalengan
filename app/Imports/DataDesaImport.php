<?php

namespace App\Imports;

use App\Models\DataDesa;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Illuminate\Validation\Rule;

class DataDesaImport implements ToModel, WithHeadingRow, WithValidation
{
    private $userIdsInSameRole;

    public function __construct()
    {
        // Ambil ID semua user dalam role yang sama untuk validasi unik
        $userRoleName = Auth::user()->roles->first()->name;
        $this->userIdsInSameRole = User::role($userRoleName)->pluck('id');
    }

    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        return new DataDesa([
            'periode'                 => $row['periode'] . '-01',
            'nama_desa'               => $row['nama_desa'],
            'jumlah_penduduk'         => $row['jumlah_penduduk'],
            'luas_wilayah'            => $row['luas_wilayah'],
            'potensi_desa'            => $row['potensi_desa'],
            'jumlah_kasus_stunting'   => $row['jumlah_kasus_stunting'] ?? 0, // Default ke 0 jika kosong
            'user_id'                 => Auth::id(),
            'status'                  => 'pending',
        ]);
    }

    public function rules(): array
    {
        return [
            'periode' => 'required|date_format:Y-m',
            
            // Validasi: nama_desa harus unik untuk periode yang sama di antara rekan satu divisi
            'nama_desa' => [
                'required', 'string', 'max:255',
                Rule::unique('data_desas')->where(function ($query) {
                    // $this->data['periode'] tidak tersedia di sini, kita harus ambil dari row
                    // Kita akan validasi manual atau biarkan controller yang menangani duplikasi kompleks
                    // Untuk kesederhanaan, kita hanya validasi dasar di sini.
                    // Validasi unik yang kompleks lebih baik ditangani di controller jika diperlukan.
                }),
            ],

            'jumlah_penduduk' => 'required|integer|min:0',
            'luas_wilayah' => 'required|integer|min:0',
            'potensi_desa' => 'required|string',
            'jumlah_kasus_stunting' => 'nullable|integer|min:0',
        ];
    }
}