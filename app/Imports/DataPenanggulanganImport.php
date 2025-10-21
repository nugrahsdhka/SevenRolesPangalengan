<?php

namespace App\Imports;

use App\Models\DataPenanggulangan;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class DataPenanggulanganImport implements ToModel, WithHeadingRow, WithValidation
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        return new DataPenanggulangan([
            'periode'       => $row['periode'] . '-01',
            'jenis_bencana' => $row['jenis_bencana'],
            'jumlah_korban' => $row['jumlah_korban'],
            'tindakan'      => $row['tindakan'],
            'user_id'       => Auth::id(),
            'status'        => 'pending',
        ]);
    }

    public function rules(): array
    {
        return [
            'periode'       => 'required|date_format:Y-m',
            'jenis_bencana' => 'required|string|max:255',
            'jumlah_korban' => 'required|integer|min:0',
            'tindakan'      => 'required|string',
        ];
    }
}