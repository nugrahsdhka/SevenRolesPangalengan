<?php

namespace App\Imports;

use App\Models\DataDesa;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Illuminate\Support\Str;

class DataDesaImport implements ToModel, WithHeadingRow, WithValidation
{
    private $userIdsInSameRole;

    /** Kunci "desa|periode" yang sudah terlihat di file ini (deteksi duplikat dalam file). */
    private array $seen = [];

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

    /**
     * Closure rule Laravel hanya melihat satu nilai, sehingga kunci gabungan
     * "desa + periode" dibuat di sini sebagai field sintetis `desa_periode`.
     * Dipakai untuk cek unik per desa + periode (bukan unik global).
     */
    public function prepareForValidation(array $data, int $index): array
    {
        $nama    = isset($data['nama_desa']) ? trim((string) $data['nama_desa']) : '';
        $periode = isset($data['periode']) ? trim((string) $data['periode']) : '';

        $data['desa_periode'] = ($nama !== '' && $periode !== '')
            ? Str::title($nama) . '|' . $periode
            : null;

        return $data;
    }

    public function rules(): array
    {
        return [
            'periode' => 'required|date_format:Y-m',

            'nama_desa' => ['required', 'string', 'max:255'],

            // Unik per desa + periode di antara rekan satu divisi (sama seperti form
            // manual di DivisiDesaController), plus tidak boleh dobel di dalam satu file.
            'desa_periode' => [
                function ($attribute, $value, $fail) {
                    if (isset($this->seen[$value])) {
                        $fail('Desa ini muncul lebih dari sekali untuk periode yang sama di dalam file.');
                        return;
                    }
                    $this->seen[$value] = true;

                    [$nama, $periode] = explode('|', $value, 2);

                    $exists = DataDesa::where('nama_desa', $nama)
                        ->whereDate('periode', $periode . '-01')
                        ->whereIn('user_id', $this->userIdsInSameRole)
                        ->exists();

                    if ($exists) {
                        $fail('Data untuk desa ini pada periode tersebut sudah diinput oleh divisi Anda.');
                    }
                },
            ],

            'jumlah_penduduk' => 'required|integer|min:0',
            'luas_wilayah' => 'required|integer|min:0',
            'potensi_desa' => 'required|string',
            'jumlah_kasus_stunting' => 'nullable|integer|min:0',
        ];
    }
}