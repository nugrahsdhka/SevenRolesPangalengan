<?php

namespace App\Imports;

use App\Models\DataGizi;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class DataGiziImport implements ToModel, WithHeadingRow, WithValidation
{
    public function __construct(
        private string $period,
        private int $userId
    ) {
        $p = trim($this->period);
        if (preg_match('/^\d{4}-\d{2}$/', $p)) {
            $p .= '-01';
        }
        $this->period = date('Y-m-01', strtotime($p));
    }

    /**
     * Helper: ubah string jadi angka desimal aman (support koma/titik).
     * Kalau gagal dibaca → return null (tidak bikin import error).
     */
    private function toDecimal($value): ?float
    {
        if ($value === null || $value === '') {
            return null;
        }

        if (is_numeric($value)) {
            return (float) $value;
        }

        // Buang karakter aneh, sisakan digit, koma, titik, minus
        $v = trim((string) $value);
        $v = preg_replace('/[^\d,\.\-]/', '', $v);

        // ganti koma jadi titik: "7,5" -> "7.5"
        $v = str_replace(',', '.', $v);

        if ($v === '' || !is_numeric($v)) {
            return null;
        }

        return (float) $v;
    }

    public function model(array $row)
    {
        $desa = $row['desakel']
            ?? $row['desa_kel']
            ?? $row['desa_kelurahan']
            ?? null;

        // ================================
        // Normalisasi angka pengukuran
        // ================================
        $bb = $row['bb_kg'] ?? $row['berat'] ?? null;
        $tb = $row['tb_cm'] ?? $row['tinggi'] ?? null;

        $bb = $this->toDecimal($bb);
        $tb = $this->toDecimal($tb);

        $bb_lahir = $row['bb_lahir_kg'] ?? $row['bb_lahir'] ?? null;
        $tb_lahir = $row['tb_lahir_cm'] ?? $row['tb_lahir'] ?? null;

        $bb_lahir = $this->toDecimal($bb_lahir);
        $tb_lahir = $this->toDecimal($tb_lahir);

        $lila           = $this->toDecimal($row['lila']           ?? null);
        $lingkarKepala  = $this->toDecimal($row['lingkar_kepala'] ?? null);

        $zs_bb_u  = $this->toDecimal($row['zs_bb_u']  ?? $row['zs_bbu']  ?? null);
        $zs_tb_u  = $this->toDecimal($row['zs_tb_u']  ?? $row['zs_tbu']  ?? null);
        $zs_bb_tb = $this->toDecimal($row['zs_bb_tb'] ?? $row['zs_bbtb'] ?? null);

        return new DataGizi([
            'user_id'        => $this->userId,
            'period'         => $this->period,

            // Identitas posyandu & kunjungan
            'posyandu'       => $row['nama_posyandu']
                                ?? $row['posyandu']
                                ?? null,
            'tgl_kunjungan'  => $row['tanggal_kunjungan']
                                ?? $row['tgl_kunjungan']
                                ?? null,
            'desa_kelurahan' => $desa,
            'alamat'         => $row['alamat'] ?? null,
            'rt'             => $row['rt'] ?? null,
            'rw'             => $row['rw'] ?? null,

            // Data anak
            'nama'           => $row['nama_anak']
                                ?? $row['nama']
                                ?? null,
            'tgl_lahir'      => $row['tanggal_lahir']
                                ?? $row['tgl_lahir']
                                ?? null,
            'usia'           => $row['usia'] ?? $row['usia_bulan'] ?? null,
            'anak_ke'        => $row['anak_ke'] ?? null,
            'jenis_kelamin'  => $row['jenis_kelamin'] ?? null,
            'nik'            => $row['nik'] ?? null,
            'nik_eppgbm'     => $row['nik_eppgbm'] ?? null,
            'nomor_kk'       => $row['nomor_kk'] ?? null,

            // BB/TB lahir
            'bb_lahir'       => $bb_lahir,
            'tb_lahir'       => $tb_lahir,

            // KIA, IMD, MBG
            'kia'            => $row['kia'] ?? null,
            'imd'            => $row['imd'] ?? null,
            'mbg'            => $row['mbg'] ?? null,

            // ASI Bulan 1–6
            'asi_bulan_1'    => $row['asi_bulan_1'] ?? null,
            'asi_bulan_2'    => $row['asi_bulan_2'] ?? null,
            'asi_bulan_3'    => $row['asi_bulan_3'] ?? null,
            'asi_bulan_4'    => $row['asi_bulan_4'] ?? null,
            'asi_bulan_5'    => $row['asi_bulan_5'] ?? null,
            'asi_bulan_6'    => $row['asi_bulan_6'] ?? null,

            // Pengukuran kunjungan
            'berat'          => $bb,
            'tinggi'         => $tb,
            'lila'           => $lila,
            'lingkar_kepala' => $lingkarKepala,
            'cara_ukur'      => $row['cara_ukur'] ?? null,
            'edema'          => $row['edema'] ?? null,

            // Vitamin & intervensi
            'vit_a'          => $row['vit_a'] ?? null,
            'pmt_biskuit'    => $row['pmt_biskuit'] ?? null,
            'taburia'        => $row['taburia'] ?? null,
            'kelas_ibu_balita' => $row['kelas_ibu_balita'] ?? null,

            // Data orang tua
            'nama_ortu'      => $row['nama_orang_tua']
                                ?? $row['nama_ortu']
                                ?? null,
            'nik_ortu'       => $row['nik_orang_tua']
                                ?? $row['nik_ortu']
                                ?? null,
            'hp_ortu'        => $row['nomor_hp_orang_tua']
                                ?? $row['no_hp_orang_tua']
                                ?? $row['hp_ortu']
                                ?? null,

            // BB/U
            'bb_u'           => $this->toDecimal($row['bb_u'] ?? null),
            'zs_bb_u'        => $zs_bb_u,
            'status_bb_u'    => $row['kategori_bb_u']
                                ?? $row['status_bb_u']
                                ?? $row['bbu']
                                ?? null,

            // TB/U
            'tb_u'           => $this->toDecimal($row['tb_u'] ?? null),
            'zs_tb_u'        => $zs_tb_u,
            'status_tb_u'    => $row['kategori_tb_u']
                                ?? $row['status_tb_u']
                                ?? $row['tbu']
                                ?? null,

            // BB/TB
            'bb_tb'          => $this->toDecimal($row['bb_tb'] ?? null),
            'zs_bb_tb'       => $zs_bb_tb,
            'status_bb_tb'   => $row['kategori_bb_tb']
                                ?? $row['status_bb_tb']
                                ?? $row['bbtb']
                                ?? null,

            // Workflow
            'status'         => $row['status'] ?? 'pending',
        ]);
    }

    public function rules(): array
    {
        return [
            // Desa/Kel: salah satu wajib
            'desakel'         => 'required_without_all:desa_kel,desa_kelurahan|string|max:100',
            'desa_kel'        => 'required_without_all:desakel,desa_kelurahan|string|max:100',
            'desa_kelurahan'  => 'required_without_all:desakel,desa_kel|string|max:100',

            // BB/TB & pengukuran: TIDAK divalidasi numeric lagi
            // (kita bersihin sendiri di toDecimal agar tidak bikin import gagal)
            'bb_kg'   => 'nullable',
            'berat'   => 'nullable',
            'tb_cm'   => 'nullable',
            'tinggi'  => 'nullable',

            // Z-score: tetap numeric kalau diisi
            'zs_bbu'   => 'nullable|numeric|min:-99.99|max:99.99',
            'zs_bb_u'  => 'nullable|numeric|min:-99.99|max:99.99',
            'zs_tbu'   => 'nullable|numeric|min:-99.99|max:99.99',
            'zs_tb_u'  => 'nullable|numeric|min:-99.99|max:99.99',
            'zs_bbtb'  => 'nullable|numeric|min:-99.99|max:99.99',
            'zs_bb_tb' => 'nullable|numeric|min:-99.99|max:99.99',

            // kolom lain tetap seperti sebelumnya
            'nama'           => 'nullable|string|max:100',
            'nama_anak'      => 'nullable|string|max:100',
            'nama_posyandu'  => 'nullable|string|max:100',
            'posyandu'       => 'nullable|string|max:100',
            'alamat'         => 'nullable|string|max:255',
            'rt'             => 'nullable|string|max:5',
            'rw'             => 'nullable|string|max:5',
            'tanggal_kunjungan' => 'nullable',
            'tgl_kunjungan'     => 'nullable',
            'tanggal_lahir'     => 'nullable',
            'tgl_lahir'         => 'nullable',
            'usia'              => 'nullable|integer|min:0|max:200',
            'usia_bulan'        => 'nullable|integer|min:0|max:200',
            'anak_ke'           => 'nullable|integer|min:1|max:20',
            'nik'               => 'nullable|string|max:20',
            'nik_eppgbm'        => 'nullable|string|max:30',
            'nomor_kk'          => 'nullable|string|max:30',
            'bb_lahir_kg'       => 'nullable|numeric|min:0|max:999.99',
            'tb_lahir_cm'       => 'nullable|numeric|min:0|max:999.99',
            'bb_lahir'          => 'nullable|numeric|min:0|max:999.99',
            'tb_lahir'          => 'nullable|numeric|min:0|max:999.99',
            'asi_bulan_1'       => 'nullable|string|max:50',
            'asi_bulan_2'       => 'nullable|string|max:50',
            'asi_bulan_3'       => 'nullable|string|max:50',
            'asi_bulan_4'       => 'nullable|string|max:50',
            'asi_bulan_5'       => 'nullable|string|max:50',
            'asi_bulan_6'       => 'nullable|string|max:50',
            'lila'              => 'nullable|numeric|min:0|max:999.99',
            'lingkar_kepala'    => 'nullable|numeric|min:0|max:999.99',
            'cara_ukur'         => 'nullable|string|max:50',
            'kia'               => 'nullable',
            'imd'               => 'nullable',
            'mbg'               => 'nullable',
            'edema'             => 'nullable',
            'vit_a'             => 'nullable',
            'pmt_biskuit'       => 'nullable',
            'taburia'           => 'nullable',
            'kelas_ibu_balita'  => 'nullable',
            'nama_orang_tua'    => 'nullable|string|max:100',
            'nama_ortu'         => 'nullable|string|max:100',
            'nik_orang_tua'     => 'nullable|string|max:30',
            'nik_ortu'          => 'nullable|string|max:30',
            'nomor_hp_orang_tua'=> 'nullable|string|max:20',
            'no_hp_orang_tua'   => 'nullable|string|max:20',
            'hp_ortu'           => 'nullable|string|max:20',
            'status'            => 'nullable|in:pending,approved,published',
        ];
    }

    public function customValidationMessages()
    {
        return [
            'desakel.required_without_all'        => 'Kolom Desa/Kelurahan tidak boleh kosong.',
            'desa_kel.required_without_all'       => 'Kolom Desa/Kelurahan tidak boleh kosong.',
            'desa_kelurahan.required_without_all' => 'Kolom Desa/Kelurahan tidak boleh kosong.',
            'status.in' => 'Status data harus salah satu dari: pending, approved, atau published.',
        ];
    }
}
