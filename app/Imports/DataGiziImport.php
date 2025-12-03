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
        // "Desa/Kel" -> desa_kel (HeadingRowFormatter ubah jadi snake_case)
        // Format lama: desakel | desa_kel | desa_kelurahan
        $desa = $row['desakel']
            ?? $row['desa_kel']
            ?? $row['desa_kelurahan']
            ?? null;

        // ================================
        // Normalisasi BB (Kg) & TB (Cm)
        // ================================
        // Ambil dari bb_kg atau berat
        $bb = $row['bb_kg'] ?? $row['berat'] ?? null;
        if ($bb !== null && $bb !== '') {
            // ubah koma jadi titik, mis: "7,5" -> "7.5"
            $bb = str_replace(',', '.', $bb);
        }

        // Ambil dari tb_cm atau tinggi
        $tb = $row['tb_cm'] ?? $row['tinggi'] ?? null;
        if ($tb !== null && $tb !== '') {
            $tb = str_replace(',', '.', $tb);
        }

        return new DataGizi([
            'user_id'        => $this->userId,
            'period'         => $this->period,            // <— DIKUNCI dari form

            // ================================
            // Identitas posyandu & kunjungan
            // ================================
            // Nama Posyandu: "Nama Posyandu" → nama_posyandu
            'posyandu'       => $row['nama_posyandu']
                                ?? $row['posyandu']
                                ?? null,

            // Tanggal Kunjungan: "Tanggal Kunjungan" → tanggal_kunjungan
            'tgl_kunjungan'  => $row['tanggal_kunjungan']
                                ?? $row['tgl_kunjungan']
                                ?? null,

            'desa_kelurahan' => $desa,
            'alamat'         => $row['alamat']  ?? null,
            'rt'             => $row['rt']      ?? null,
            'rw'             => $row['rw']      ?? null,

            // ================================
            // Data anak
            // ================================
            // Nama Anak: "Nama Anak" → nama_anak
            'nama'           => $row['nama_anak']
                                ?? $row['nama']
                                ?? null,

            // Tanggal Lahir: "Tanggal Lahir" → tanggal_lahir
            'tgl_lahir'      => $row['tanggal_lahir']
                                ?? $row['tgl_lahir']
                                ?? null,

            'usia'           => $row['usia'] ?? $row['usia_bulan'] ?? null,
            'anak_ke'        => $row['anak_ke']  ?? null,
            'jenis_kelamin'  => $row['jenis_kelamin'] ?? null,

            // NIK anak
            'nik'            => $row['nik'] ?? null,

            // NIK EPPGBM, Nomor KK
            'nik_eppgbm'     => $row['nik_eppgbm'] ?? null,
            'nomor_kk'       => $row['nomor_kk']   ?? null,

            // BB Lahir (Kg), TB Lahir (Cm)
            'bb_lahir'       => $row['bb_lahir_kg'] ?? $row['bb_lahir'] ?? null,
            'tb_lahir'       => $row['tb_lahir_cm'] ?? $row['tb_lahir'] ?? null,

            // ================================
            // Status KIA, IMD, MBG
            // ================================
            'kia'            => $row['kia'] ?? null,
            'imd'            => $row['imd'] ?? null,
            'mbg'            => $row['mbg'] ?? null,

            // ================================
            // ASI Bulan 1–6
            // ================================
            'asi_bulan_1'    => $row['asi_bulan_1'] ?? null,
            'asi_bulan_2'    => $row['asi_bulan_2'] ?? null,
            'asi_bulan_3'    => $row['asi_bulan_3'] ?? null,
            'asi_bulan_4'    => $row['asi_bulan_4'] ?? null,
            'asi_bulan_5'    => $row['asi_bulan_5'] ?? null,
            'asi_bulan_6'    => $row['asi_bulan_6'] ?? null,

            // ================================
            // Pengukuran saat kunjungan
            // ================================
            'berat'          => $bb,
            'tinggi'         => $tb,

            'lila'           => $row['lila']            ?? null,
            'lingkar_kepala' => $row['lingkar_kepala']  ?? null,
            'cara_ukur'      => $row['cara_ukur']       ?? null,
            'edema'          => $row['edema']           ?? null,

            // ================================
            // Vitamin & Intervensi
            // ================================
            'vit_a'          => $row['vit_a']         ?? null,
            'pmt_biskuit'    => $row['pmt_biskuit']   ?? null,
            'taburia'        => $row['taburia']       ?? null,
            'kelas_ibu_balita' => $row['kelas_ibu_balita'] ?? null,

            // ================================
            // Data orang tua
            // ================================
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

            // ================================
            // Kolom BB/U
            // ================================
            'bb_u'           => $row['bb_u'] ?? null,
            'zs_bb_u'        => $row['zs_bb_u'] ?? $row['zs_bbu'] ?? null,
            'status_bb_u'    => $row['kategori_bb_u']
                                ?? $row['status_bb_u']
                                ?? $row['bbu']
                                ?? null,

            // ================================
            // Kolom TB/U
            // ================================
            'tb_u'           => $row['tb_u'] ?? null,
            'zs_tb_u'        => $row['zs_tb_u'] ?? $row['zs_tbu'] ?? null,
            'status_tb_u'    => $row['kategori_tb_u']
                                ?? $row['status_tb_u']
                                ?? $row['tbu']
                                ?? null,

            // ================================
            // Kolom BB/TB
            // ================================
            'bb_tb'          => $row['bb_tb'] ?? null,
            'zs_bb_tb'       => $row['zs_bb_tb'] ?? $row['zs_bbtb'] ?? null,
            'status_bb_tb'   => $row['kategori_bb_tb']
                                ?? $row['status_bb_tb']
                                ?? $row['bbtb']
                                ?? null,

            // Workflow
            'status'         => $row['status']  ?? 'pending',
        ]);
    }

    /**
     * Validasi setiap baris
     */
    public function rules(): array
    {
        return [
            // 🔹 Desa/Kelurahan: salah satu wajib
            'desakel'         => 'required_without_all:desa_kel,desa_kelurahan|string|max:100',
            'desa_kel'        => 'required_without_all:desakel,desa_kelurahan|string|max:100',
            'desa_kelurahan'  => 'required_without_all:desakel,desa_kel|string|max:100',

            // 🔹 Berat & Tinggi:
            // bb_kg & berat boleh pakai titik atau koma
            'bb_kg'  => 'required_without:berat|regex:/^\d+([.,]\d+)?$/',
            'berat'  => 'required_without:bb_kg|regex:/^\d+([.,]\d+)?$/',

            'tb_cm'  => 'required_without:tinggi|regex:/^\d+([.,]\d+)?$/',
            'tinggi' => 'required_without:tb_cm|regex:/^\d+([.,]\d+)?$/',

            // 🔹 Z-score (boleh kosong, tapi kalau diisi harus numeric)
            'zs_bbu'   => 'nullable|numeric|min:-99.99|max:99.99',
            'zs_bb_u'  => 'nullable|numeric|min:-99.99|max:99.99',

            'zs_tbu'   => 'nullable|numeric|min:-99.99|max:99.99',
            'zs_tb_u'  => 'nullable|numeric|min:-99.99|max:99.99',

            'zs_bbtb'  => 'nullable|numeric|min:-99.99|max:99.99',
            'zs_bb_tb' => 'nullable|numeric|min:-99.99|max:99.99',

            // 🔹 kolom baru (semua nullable, tipe basic)
            'nama'           => 'nullable|string|max:100',
            'nama_anak'      => 'nullable|string|max:100',

            'nama_posyandu'  => 'nullable|string|max:100',
            'posyandu'       => 'nullable|string|max:100',

            'alamat' => 'nullable|string|max:255',
            'rt'     => 'nullable|string|max:5',
            'rw'     => 'nullable|string|max:5',

            'tanggal_kunjungan' => 'nullable',
            'tgl_kunjungan'     => 'nullable',
            'tanggal_lahir'     => 'nullable',
            'tgl_lahir'         => 'nullable',

            'usia'       => 'nullable|integer|min:0|max:200',
            'usia_bulan' => 'nullable|integer|min:0|max:200',
            'anak_ke'    => 'nullable|integer|min:1|max:20',

            'nik'        => 'nullable|string|max:20',
            'nik_eppgbm' => 'nullable|string|max:30',
            'nomor_kk'   => 'nullable|string|max:30',

            'bb_lahir_kg' => 'nullable|numeric|min:0|max:999.99',
            'tb_lahir_cm' => 'nullable|numeric|min:0|max:999.99',
            'bb_lahir'    => 'nullable|numeric|min:0|max:999.99',
            'tb_lahir'    => 'nullable|numeric|min:0|max:999.99',

            'asi_bulan_1' => 'nullable|string|max:50',
            'asi_bulan_2' => 'nullable|string|max:50',
            'asi_bulan_3' => 'nullable|string|max:50',
            'asi_bulan_4' => 'nullable|string|max:50',
            'asi_bulan_5' => 'nullable|string|max:50',
            'asi_bulan_6' => 'nullable|string|max:50',

            'lila'           => 'nullable|numeric|min:0|max:999.99',
            'lingkar_kepala' => 'nullable|numeric|min:0|max:999.99',
            'cara_ukur'      => 'nullable|string|max:50',

            // KIA, IMD, MBG, Edema, Vit A, PMT, Taburia, Kelas Ibu Balita
            'kia'              => 'nullable',
            'imd'              => 'nullable',
            'mbg'              => 'nullable',
            'edema'            => 'nullable',
            'vit_a'            => 'nullable',
            'pmt_biskuit'      => 'nullable',
            'taburia'          => 'nullable',
            'kelas_ibu_balita' => 'nullable',

            // Orang tua
            'nama_orang_tua'      => 'nullable|string|max:100',
            'nama_ortu'           => 'nullable|string|max:100',
            'nik_orang_tua'       => 'nullable|string|max:30',
            'nik_ortu'            => 'nullable|string|max:30',
            'nomor_hp_orang_tua'  => 'nullable|string|max:20',
            'no_hp_orang_tua'     => 'nullable|string|max:20',
            'hp_ortu'             => 'nullable|string|max:20',

            // Status workflow
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

            'bb_kg.required_without'   => 'Kolom BB (Kg) atau Berat tidak boleh kosong.',
            'berat.required_without'   => 'Kolom BB (Kg) atau Berat tidak boleh kosong.',

            'tb_cm.required_without'   => 'Kolom TB (Cm) atau Tinggi tidak boleh kosong.',
            'tinggi.required_without'  => 'Kolom TB (Cm) atau Tinggi tidak boleh kosong.',

            'status.in'        => 'Status data harus salah satu dari: pending, approved, atau published.',
        ];
    }
}
