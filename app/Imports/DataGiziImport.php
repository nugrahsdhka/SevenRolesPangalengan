<?php

namespace App\Imports;

use App\Models\DataGizi;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class DataGiziImport implements ToModel, WithHeadingRow
{
    protected string $period;
    protected int $userId;

    public function __construct(string $period, int $userId)
    {
        $p = trim($period);

        // support format "2025-11" -> "2025-11-01"
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

        if (is_numeric($value)) {
            return (float) $value;
        }

        $v = trim((string) $value);
        // sisakan digit, koma, titik, minus
        $v = preg_replace('/[^\d,\.\-]/', '', $v);
        // koma -> titik
        $v = str_replace(',', '.', $v);

        if ($v === '' || !is_numeric($v)) {
            return null;
        }

        return (float) $v;
    }

    /**
     * Potong string supaya tidak melebihi max length kolom DB.
     */
    private function safeString($value, ?int $maxLen = null): ?string
    {
        if ($value === null || $value === '') {
            return null;
        }

        $s = trim((string) $value);

        if ($maxLen !== null && mb_strlen($s) > $maxLen) {
            $s = mb_substr($s, 0, $maxLen);
        }

        return $s;
    }

    public function model(array $row)
    {
        // -----------------------------------
        // 1. Normalisasi beberapa field kunci
        // -----------------------------------

        // Heading dari file:
        // "Desa/Kel" -> desa_kel
        $desa = $row['desa_kel']
            ?? $row['desakel']
            ?? $row['desa_kelurahan']
            ?? null;

        // BB / TB kunjungan
        // "BB (Kg)" -> bb_kg, "TB (Cm)" -> tb_cm
        $bb = $row['bb_kg'] ?? $row['berat'] ?? null;
        $tb = $row['tb_cm'] ?? $row['tinggi'] ?? null;

        $bb = $this->toDecimal($bb);
        $tb = $this->toDecimal($tb);

        // BB / TB lahir
        // "BB Lahir (Kg)" -> bb_lahir_kg, "TB Lahir (Cm)" -> tb_lahir_cm
        $bb_lahir = $row['bb_lahir_kg'] ?? $row['bb_lahir'] ?? null;
        $tb_lahir = $row['tb_lahir_cm'] ?? $row['tb_lahir'] ?? null;

        $bb_lahir = $this->toDecimal($bb_lahir);
        $tb_lahir = $this->toDecimal($tb_lahir);

        // LILA & lingkar kepala
        // Sheet rekap: "LILA" -> lila, "Lingkar Kepala" -> lingkar_kepala
        // Sheet per-posyandu: "LILA (Cm)" -> lila_cm, "Lingkar Kepala (Cm)" -> lingkar_kepala_cm
        $lila = $this->toDecimal($row['lila'] ?? $row['lila_cm'] ?? null);
        $lingkarKepala = $this->toDecimal($row['lingkar_kepala'] ?? $row['lingkar_kepala_cm'] ?? null);

        // Z-score (yang pasti numeric)
        // "ZS BB/U" -> zs_bb_u, "ZS TB/U" -> zs_tb_u, "ZS BB/TB" -> zs_bb_tb
        $zs_bb_u  = $this->toDecimal($row['zs_bb_u']  ?? $row['zs_bbu']  ?? null);
        $zs_tb_u  = $this->toDecimal($row['zs_tb_u']  ?? $row['zs_tbu']  ?? null);
        $zs_bb_tb = $this->toDecimal($row['zs_bb_tb'] ?? $row['zs_bbtb'] ?? null);

        // BB/U, TB/U, BB/TB nilai mentah (boleh kosong)
        $bb_u  = $this->toDecimal($row['bb_u'] ?? null);
        $tb_u  = $this->toDecimal($row['tb_u'] ?? null);
        $bb_tb = $this->toDecimal($row['bb_tb'] ?? null);

        // Usia: di sheet rekap "Usia" -> usia, di sheet posyandu "Usia (Bulan)" -> usia_bulan
        $usia = $row['usia'] ?? $row['usia_bulan'] ?? null;

        // -----------------------------------
        // 2. Build model DataGizi
        // -----------------------------------

        return new DataGizi([
            'user_id'        => $this->userId,
            'period'         => $this->period,

            // Identitas posyandu & kunjungan
            // "Nama Posyandu" -> nama_posyandu
            'posyandu'       => $this->safeString(
                $row['nama_posyandu'] ?? $row['posyandu'] ?? null,
                100
            ),
            // "Tanggal Kunjungan" -> tanggal_kunjungan
            'tgl_kunjungan'  => $row['tanggal_kunjungan'] ?? $row['tgl_kunjungan'] ?? null,

            'desa_kelurahan' => $this->safeString($desa, 100),
            'alamat'         => $this->safeString($row['alamat_anak'] ?? $row['alamat'] ?? null, 255),
            'rt'             => $this->safeString($row['rt'] ?? null, 5),
            'rw'             => $this->safeString($row['rw'] ?? null, 5),

            // Data anak
            'nama'           => $this->safeString($row['nama_anak'] ?? $row['nama'] ?? null, 100),
            'tgl_lahir'      => $row['tanggal_lahir'] ?? $row['tgl_lahir'] ?? null,
            'usia'           => $this->safeString($usia, 20),
            'anak_ke'        => $this->safeString($row['anak_ke'] ?? null, 5),
            'jenis_kelamin'  => $this->safeString($row['jenis_kelamin'] ?? null, 20),
            'nik'            => $this->safeString($row['nik'] ?? null, 20),
            'nik_eppgbm'     => $this->safeString($row['nik_eppgbm'] ?? null, 30),
            'nomor_kk'       => $this->safeString($row['nomor_kk'] ?? null, 30),

            // BB/TB lahir
            'bb_lahir'       => $bb_lahir,
            'tb_lahir'       => $tb_lahir,

            // KIA, IMD, MBG
            'kia'            => $this->safeString($row['kia'] ?? null, 20),
            'imd'            => $this->safeString($row['imd'] ?? null, 20),
            'mbg'            => $this->safeString($row['mbg'] ?? null, 20),

            // ASI Bulan 1–6
            'asi_bulan_1'    => $this->safeString($row['asi_bulan_1'] ?? null, 50),
            'asi_bulan_2'    => $this->safeString($row['asi_bulan_2'] ?? null, 50),
            'asi_bulan_3'    => $this->safeString($row['asi_bulan_3'] ?? null, 50),
            'asi_bulan_4'    => $this->safeString($row['asi_bulan_4'] ?? null, 50),
            'asi_bulan_5'    => $this->safeString($row['asi_bulan_5'] ?? null, 50),
            'asi_bulan_6'    => $this->safeString($row['asi_bulan_6'] ?? null, 50),

            // Pengukuran kunjungan
            'berat'          => $bb,
            'tinggi'         => $tb,
            'lila'           => $lila,
            'lingkar_kepala' => $lingkarKepala,
            'cara_ukur'      => $this->safeString($row['cara_ukur'] ?? null, 50),
            'edema'          => $this->safeString($row['edema'] ?? null, 20),

            // Vitamin & intervensi
            'vit_a'          => $this->safeString($row['vit_a'] ?? null, 20),
            'pmt_biskuit'    => $this->safeString($row['pmt_biskuit'] ?? null, 20),
            'taburia'        => $this->safeString($row['taburia'] ?? null, 20),
            'kelas_ibu_balita' => $this->safeString($row['kelas_ibu_balita'] ?? null, 100),

            // Data orang tua
            'nama_ortu'      => $this->safeString(
                $row['nama_orang_tua'] ?? $row['nama_ortu'] ?? null,
                100
            ),
            'nik_ortu'       => $this->safeString(
                $row['nik_orang_tua'] ?? $row['nik_ortu'] ?? null,
                30
            ),
            'hp_ortu'        => $this->safeString(
                $row['nomor_hp_orang_tua'] ?? $row['no_hp_orang_tua'] ?? $row['hp_ortu'] ?? null,
                20
            ),

            // BB/U
            'bb_u'           => $bb_u,
            'zs_bb_u'        => $zs_bb_u,
            'status_bb_u'    => $this->safeString(
                $row['kategori_bb_u'] ?? $row['status_bb_u'] ?? $row['bbu'] ?? null,
                100
            ),

            // TB/U
            'tb_u'           => $tb_u,
            'zs_tb_u'        => $zs_tb_u,
            'status_tb_u'    => $this->safeString(
                $row['kategori_tb_u'] ?? $row['status_tb_u'] ?? $row['tbu'] ?? null,
                100
            ),

            // BB/TB
            'bb_tb'          => $bb_tb,
            'zs_bb_tb'       => $zs_bb_tb,
            'status_bb_tb'   => $this->safeString(
                $row['kategori_bb_tb'] ?? $row['status_bb_tb'] ?? $row['bbtb'] ?? null,
                100
            ),

            // Workflow
            'status'         => $this->safeString($row['status'] ?? 'pending', 20),
        ]);
    }
}
