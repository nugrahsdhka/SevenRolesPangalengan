<?php

namespace App\Imports;

use App\Models\DataGizi;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation; // ✅ 1. Tambahkan ini

class DataGiziImport implements ToModel, WithHeadingRow, WithValidation // ✅ 2. Tambahkan ini
{
    /**
     * ✅ 3. Hapus seluruh method __construct()
     * Kita tidak lagi perlu mengirimkan periode dari controller.
     */

    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        return new DataGizi([
            'judul'     => $row['judul'],
            'deskripsi' => $row['deskripsi'],
            
            // ✅ 4. Baca 'periode' dari Excel dan tambahkan '-01'
            'periode'   => $row['periode'] . '-01', 
            
            // ✅ 5. Ambil user_id langsung di sini
            'user_id'   => Auth::id(), 
            'status'    => 'pending',
        ]);
    }

    /**
     * ✅ 6. Tambahkan method rules() untuk validasi data di dalam Excel
     */
    public function rules(): array
    {
        return [
            'periode' => 'nullable|date_format:Y-m',
            'judul' => 'required|string|max:255',
            'deskripsi' => 'required|string',
        ];
    }
}