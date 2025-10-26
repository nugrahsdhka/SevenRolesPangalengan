<?php

namespace App\Imports;

use App\Models\DataGizi;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class DataGiziImport implements ToModel, WithHeadingRow, WithValidation 
{
    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {
        // Debug: uncomment untuk lihat key yang tersedia
        // \Log::info('Row keys:', array_keys($row));
        
        // Laravel Excel mengubah header dengan pattern:
        // "Desa/Kel" menjadi "desakel" 
        // "BB/U" menjadi "bbu"
        // "ZS BB/U" menjadi "zs_bbu"
        
        return new DataGizi([
            'desa_kelurahan' => $row['desakel'] ?? $row['desa_kel'] ?? null, 
            'berat'          => $row['berat'] ?? null,
            'tinggi'         => $row['tinggi'] ?? null,
            'status_bb_u'    => $row['bbu'] ?? null,      
            'zs_bb_u'        => $row['zs_bbu'] ?? null,   
            'status_tb_u'    => $row['tbu'] ?? null,      
            'zs_tb_u'        => $row['zs_tbu'] ?? null,   
            'status_bb_tb'   => $row['bbtb'] ?? null,     
            'zs_bb_tb'       => $row['zs_bbtb'] ?? null,  
            'user_id'        => Auth::id(), 
        ]);
    }

    /**
     * Aturan validasi untuk data di dalam Excel
     */
    public function rules(): array
    {
        return [
            'desakel' => 'required|string|max:100', 
            'berat'   => 'required|numeric|min:0|max:999.99',
            'tinggi'  => 'required|numeric|min:0|max:999.99',
            'bbu'     => 'nullable|string|max:50',      
            'zs_bbu'  => 'nullable|numeric|min:-99.99|max:99.99', 
            'tbu'     => 'nullable|string|max:50',      
            'zs_tbu'  => 'required|numeric|min:-99.99|max:99.99', 
            'bbtb'    => 'nullable|string|max:50',      
            'zs_bbtb' => 'nullable|numeric|min:-99.99|max:99.99', 
        ];
    }

    /**
     * Pesan kustom untuk error validasi
     */
    public function customValidationMessages()
    {
        return [
            'desakel.required' => 'Kolom Desa/Kelurahan tidak boleh kosong.', 
            'berat.required' => 'Kolom Berat tidak boleh kosong.',
            'berat.numeric' => 'Kolom Berat harus berupa angka.',
            'tinggi.required' => 'Kolom Tinggi tidak boleh kosong.',
            'tinggi.numeric' => 'Kolom Tinggi harus berupa angka.',
            'zs_tbu.required' => 'Kolom ZS TB/U tidak boleh kosong.'
        ];
    }
}