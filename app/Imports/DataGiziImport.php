<?php

namespace App\Imports;

use App\Models\DataGizi;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class DataGiziImport implements ToModel, WithHeadingRow, WithValidation
{
    protected string $period;
    protected int $userId;

    public function __construct(string $period, int $userId)
    {
        $p = trim($period);

        // support "2025-11" -> "2025-11-01"
        if (preg_match('/^\d{4}-\d{2}$/', $p)) {
            $p .= '-01';
        }

        $this->period = date('Y-m-01', strtotime($p));
        $this->userId = $userId;
    }

    /**
     * Ubah string/apa pun jadi float aman (dukung koma/titik).
     * Kalau gagal dibaca -> null (tidak bikin import error).
     */
    private function toDecimal($value): ?float
    {
        if ($value === null || $value === '') {
            return null;
        }

        // kalau sudah numeric (misal dari Excel sebagai number)
        if (is_numeric($value)) {
            return (float) $value;
        }

        // Buang karakter aneh, sisakan digit, koma, titik, minus
        $v = trim((string) $value);
        $v = preg_replace('/[^\d,\.\-]/', '', $v);

        // Ganti koma jadi titik: "3,6" -> "3.6"
        $v = str_replace(',', '.', $v);

        if ($v === '' || !is_numeric($v)) {
            return null;
        }

        return (float) $v;
    }

    public function model(array $row)
    {
        // Desa / Kelurahan
        $desa = $row['desakel']
            ?? $row['desa_kel']
            ?? $row['desa_kelurahan']
            ?? null;

        // Pengukuran kunjungan
        $bb = $row['bb_kg'] ?? $row['berat'] ?? null;
        $tb = $row['tb_cm'] ?? $row['tinggi'] ?? null;

        $bb = $this->toDecimal($bb);
        $tb = $this->toDecimal($tb);

        // BB / TB lahir
        $bb_lahir = $row['bb_lahir_kg'] ?? $row['bb_lahir'] ?? null;
        $tb_lahir = $row['tb_lahir_cm'] ?? $row['tb_lahir'] ?? null;

        $bb_lahir = $this->toDecimal($bb_lahir);
        $tb_lahir = $this->toDecimal($tb_lahir);

        // LILA & lingkar kepala
        $lila           = $this->toDecimal($row['lila']           ?? null);
        $lingkarKepala  = $this->toDecimal($row['lingkar_kepala'] ?? null);

        // Z-score (ini yang pasti numeric di DB)
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
            'alamat'         => $row['alamat'] ?? $row['alamat_anak'] ?? null,
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

            // Semua kolom lain longgar (string / bebas), supaya gak gampang error

            // Posyandu & alamat
            'nama_posyandu'   => 'nullable|string|max:255',
            'posyandu'        => 'nullable|string|max:255',
            'alamat'          => 'nullable|string|max:255',
            'alamat_anak'     => 'nullable|string|max:255',
            'rt'              => 'nullable|string|max:50',
            'rw'              => 'nullable|string|max:50',

            // Tanggal & usia
            'tanggal_kunjungan' => 'nullable|string|max:50',
            'tgl_kunjungan'     => 'nullable|string|max:50',
            'tanggal_lahir'     => 'nullable|string|max:50',
            'tgl_lahir'         => 'nullable|string|max:50',
            'usia'              => 'nullable|string|max:20',
            'usia_bulan'        => 'nullable|string|max:20',
            'anak_ke'           => 'nullable|string|max:20',

            // Identitas anak & orang tua
            'nama'              => 'nullable|string|max:255',
            'nama_anak'         => 'nullable|string|max:255',
            'jenis_kelamin'     => 'nullable|string|max:20',
            'nik'               => 'nullable|string|max:50',
            'nik_eppgbm'        => 'nullable|string|max:50',
            'nomor_kk'          => 'nullable|string|max:50',
            'nama_orang_tua'    => 'nullable|string|max:255',
            'nama_ortu'         => 'nullable|string|max:255',
            'nik_orang_tua'     => 'nullable|string|max:50',
            'nik_ortu'          => 'nullable|string|max:50',
            'nomor_hp_orang_tua'=> 'nullable|string|max:50',
            'no_hp_orang_tua'   => 'nullable|string|max:50',
            'hp_ortu'           => 'nullable|string|max:50',

            // BB/TB lahir & kunjungan (dibersihkan di toDecimal)
            'bb_lahir_kg'       => 'nullable|string|max:50',
            'tb_lahir_cm'       => 'nullable|string|max:50',
            'bb_lahir'          => 'nullable|string|max:50',
            'tb_lahir'          => 'nullable|string|max:50',
            'bb_kg'             => 'nullable|string|max:50',
            'berat'             => 'nullable|string|max:50',
            'tb_cm'             => 'nullable|string|max:50',
            'tinggi'            => 'nullable|string|max:50',
            'lila'              => 'nullable|string|max:50',
            'lingkar_kepala'    => 'nullable|string|max:50',

            // ASI 1–6
            'asi_bulan_1'       => 'nullable|string|max:50',
            'asi_bulan_2'       => 'nullable|string|max:50',
            'asi_bulan_3'       => 'nullable|string|max:50',
            'asi_bulan_4'       => 'nullable|string|max:50',
            'asi_bulan_5'       => 'nullable|string|max:50',
            'asi_bulan_6'       => 'nullable|string|max:50',

            // Lain-lain (string semua)
            'kia'               => 'nullable|string|max:50',
            'imd'               => 'nullable|string|max:50',
            'mbg'               => 'nullable|string|max:50',
            'cara_ukur'         => 'nullable|string|max:50',
            'edema'             => 'nullable|string|max:50',
            'vit_a'             => 'nullable|string|max:50',
            'pmt_biskuit'       => 'nullable|string|max:50',
            'taburia'           => 'nullable|string|max:50',
            'kelas_ibu_balita'  => 'nullable|string|max:100',

            // Z-score dibiarkan bebas di validasi (boleh "3,6"), nanti toDecimal yang nyulap
            'zs_bbu'            => 'nullable|string|max:50',
            'zs_bb_u'           => 'nullable|string|max:50',
            'zs_tbu'            => 'nullable|string|max:50',
            'zs_tb_u'           => 'nullable|string|max:50',
            'zs_bbtb'           => 'nullable|string|max:50',
            'zs_bb_tb'          => 'nullable|string|max:50',

            // Kategori status antropometri (string biasa)
            'kategori_bb_u'     => 'nullable|string|max:100',
            'status_bb_u'       => 'nullable|string|max:100',
            'bbu'               => 'nullable|string|max:100',
            'kategori_tb_u'     => 'nullable|string|max:100',
            'status_tb_u'       => 'nullable|string|max:100',
            'tbu'               => 'nullable|string|max:100',
            'kategori_bb_tb'    => 'nullable|string|max:100',
            'status_bb_tb'      => 'nullable|string|max:100',
            'bbtb'              => 'nullable|string|max:100',

            // Workflow
            'status'            => 'nullable|in:pending,approved,published',
        ];
    }

    public function customValidationMessages(): array
    {
        return [
            'desakel.required_without_all'        => 'Kolom Desa/Kelurahan tidak boleh kosong.',
            'desa_kel.required_without_all'       => 'Kolom Desa/Kelurahan tidak boleh kosong.',
            'desa_kelurahan.required_without_all' => 'Kolom Desa/Kelurahan tidak boleh kosong.',
            'status.in' => 'Status data harus salah satu dari: pending, approved, atau published.',
        ];
    }
}
