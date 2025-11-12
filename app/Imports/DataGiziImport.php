<?php

namespace App\Imports;

use App\Models\DataGizi;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class DataGiziImport implements ToModel, WithHeadingRow, WithValidation
{
    /**
     * Periode dan user dipaksa dari form (bukan dari file),
     * agar semua baris masuk ke periode yang sama.
     *
     * @param string $period  'YYYY-MM' atau 'YYYY-MM-DD'
     * @param int    $userId
     */
    public function __construct(
        private string $period,
        private int $userId
    ) {
        // Normalisasi ke tanggal pertama bulan
        $p = trim($this->period);
        if (preg_match('/^\d{4}-\d{2}$/', $p)) {
            $p .= '-01';
        }
        $this->period = date('Y-m-01', strtotime($p));
    }

    /**
     * Map setiap row ke model DataGizi
     *
     * @param array $row
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {
        // Header tipikal Laravel Excel:
        // "Desa/Kel" -> desakel | desa_kel | desa_kelurahan (tergantung file)
        // "BB/U" -> bbu ; "ZS BB/U" -> zs_bbu ; "TB/U" -> tbu ; "ZS TB/U" -> zs_tbu
        // "BB/TB" -> bbtb ; "ZS BB/TB" -> zs_bbtb

        $desa = $row['desakel']
            ?? $row['desa_kel']
            ?? $row['desa_kelurahan']
            ?? null;

        return new DataGizi([
            'user_id'        => $this->userId,
            'period'         => $this->period,            // <— DIKUNCI dari form
            'desa_kelurahan' => $desa,
            'berat'          => $row['berat']   ?? null,
            'tinggi'         => $row['tinggi']  ?? null,
            'status_bb_u'    => $row['bbu']     ?? null,
            'zs_bb_u'        => $row['zs_bbu']  ?? null,
            'status_tb_u'    => $row['tbu']     ?? null,
            'zs_tb_u'        => $row['zs_tbu']  ?? null,
            'status_bb_tb'   => $row['bbtb']    ?? null,
            'zs_bb_tb'       => $row['zs_bbtb'] ?? null,
            // opsional: kalau file punya kolom 'status', pakai; kalau tidak, default 'pending'
            'status'         => $row['status']  ?? 'pending',
        ]);
    }

    /**
     * Validasi setiap baris
     */
    public function rules(): array
    {
        return [
            'desakel' => 'required_without_all:desa_kel,desa_kelurahan|string|max:100',
            'desa_kel' => 'required_without_all:desakel,desa_kelurahan|string|max:100',
            'desa_kelurahan' => 'required_without_all:desakel,desa_kel|string|max:100',

            'berat'   => 'required|numeric|min:0|max:999.99',
            'tinggi'  => 'required|numeric|min:0|max:999.99',

            'bbu'     => 'nullable|string|max:50',
            'zs_bbu'  => 'nullable|numeric|min:-99.99|max:99.99',

            'tbu'     => 'nullable|string|max:50',
            'zs_tbu'  => 'required|numeric|min:-99.99|max:99.99',

            'bbtb'    => 'nullable|string|max:50',
            'zs_bbtb' => 'nullable|numeric|min:-99.99|max:99.99',

            'status'  => 'nullable|in:pending,approved,published',
        ];
    }

    /**
     * Pesan error kustom
     */
    public function customValidationMessages()
    {
        return [
            'desakel.required_without_all'        => 'Kolom Desa/Kelurahan tidak boleh kosong.',
            'desa_kel.required_without_all'       => 'Kolom Desa/Kelurahan tidak boleh kosong.',
            'desa_kelurahan.required_without_all' => 'Kolom Desa/Kelurahan tidak boleh kosong.',

            'berat.required'   => 'Kolom Berat tidak boleh kosong.',
            'berat.numeric'    => 'Kolom Berat harus berupa angka.',
            'tinggi.required'  => 'Kolom Tinggi tidak boleh kosong.',
            'tinggi.numeric'   => 'Kolom Tinggi harus berupa angka.',

            'zs_tbu.required'  => 'Kolom ZS TB/U tidak boleh kosong.',
            'status.in'        => 'Status data harus salah satu dari: pending, approved, atau published.',
        ];
    }
}
