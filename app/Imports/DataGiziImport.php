<?php

namespace App\Imports;

use App\Models\DataGizi;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use PhpOffice\PhpSpreadsheet\Shared\Date as ExcelDate;

class DataGiziImport implements ToModel, WithHeadingRow
{
    protected string $period;
    protected int $userId;

    public function __construct(string $period, int $userId)
    {
        $p = trim($period);

        if (preg_match('/^\d{4}-\d{2}$/', $p)) {
            $p .= '-01';
        }

        $this->period = date('Y-m-01', strtotime($p));
        $this->userId = $userId;
    }

    /* ============================================================
       CLEANER UTAMA — MEMBUANG FORMULA EXCEL SEPERTI:
       =IFERROR(__xludf.DUMMYFUNCTION("COMPUTED_VALUE"),"MELATI 1")
       ============================================================ */
    private function cleanExcelFormula($value): ?string
    {
        if ($value === null || $value === '') return null;

        $s = trim((string) $value);

        // Jika ada "=" maka itu formula
        if (strlen($s) > 0 && $s[0] === '=') {

            // Ambil string di dalam tanda kutip terakhir
            if (preg_match_all('/"([^"]*)"/', $s, $matches)) {
                $last = trim(end($matches[1]));
                return $last !== '' ? $last : null;
            }

            return null;
        }

        return $s;
    }

    /* Bersihkan string + batasi panjang */
    private function safeString($value, ?int $maxLen = null): ?string
    {
        $value = $this->cleanExcelFormula($value);
        if ($value === null || $value === '') return null;

        $s = trim($value);
        if ($maxLen !== null && mb_strlen($s) > $maxLen) {
            $s = mb_substr($s, 0, $maxLen);
        }
        return $s;
    }

    /* Ubah string menjadi float */
    private function toDecimal($value): ?float
    {
        $value = $this->cleanExcelFormula($value);
        if ($value === null || $value === '') return null;

        if (is_numeric($value)) return (float)$value;

        $v = trim((string) $value);
        $v = preg_replace('/[^\d,\.\-]/', '', $v);
        $v = str_replace(',', '.', $v);

        return is_numeric($v) ? (float)$v : null;
    }

    /* Ubah string menjadi integer */
    private function toIntOrNull($value): ?int
    {
        $value = $this->cleanExcelFormula($value);

        if ($value === null || $value === '') return null;

        if (is_numeric($value)) return (int)$value;

        $s = trim((string)$value);

        if ($s[0] === '=') return null;

        if (preg_match('/-?\d+/', $s, $m)) {
            return (int)$m[0];
        }

        return null;
    }

    /* Konversi tanggal ke Y-m-d */
    private function toDateYmd($value): ?string
    {
        $value = $this->cleanExcelFormula($value);

        if ($value === null || $value === '') return null;

        if (is_numeric($value)) {
            try {
                return ExcelDate::excelToDateTimeObject($value)->format('Y-m-d');
            } catch (\Throwable $e) {
                return null;
            }
        }

        $s = str_replace('/', '-', trim((string)$value));
        $ts = strtotime($s);

        return $ts ? date('Y-m-d', $ts) : null;
    }

    /* ============================================================
       BAGIAN UTAMA PEMETAAN ROW EXCEL → MODEL
       ============================================================ */
    public function model(array $row)
    {
        // Ambil nama desa
        $desa = $this->safeString(
            $row['desa_kel'] ??
            $row['desakel'] ??
            $row['desa_kelurahan'] ??
            null,
            100
        );

        if ($desa === null || $desa === '') return null;

        // Angka
        $bb         = $this->toDecimal($row['bb_kg'] ?? $row['berat'] ?? null);
        $tb         = $this->toDecimal($row['tb_cm'] ?? $row['tinggi'] ?? null);
        $bb_lahir   = $this->toDecimal($row['bb_lahir'] ?? null);
        $tb_lahir   = $this->toDecimal($row['tb_lahir'] ?? null);
        $lila       = $this->toDecimal($row['lila'] ?? null);
        $kepala     = $this->toDecimal($row['lingkar_kepala'] ?? null);

        $zs_bb_u    = $this->toDecimal($row['zs_bb_u'] ?? null);
        $zs_tb_u    = $this->toDecimal($row['zs_tb_u'] ?? null);
        $zs_bb_tb   = $this->toDecimal($row['zs_bb_tb'] ?? null);

        // Integer
        $usia   = $this->toIntOrNull($row['usia'] ?? null);
        $anakKe = $this->toIntOrNull($row['anak_ke'] ?? null);

        // Tanggal
        $tglKunjungan = $this->toDateYmd($row['tanggal_kunjungan'] ?? null);
        $tglLahir     = $this->toDateYmd($row['tanggal_lahir'] ?? null);

        return new DataGizi([

            'user_id'        => $this->userId,
            'period'         => $this->period,

            // Identitas posyandu
            'posyandu'       => $this->safeString($row['nama_posyandu'] ?? null, 150),
            'tgl_kunjungan'  => $tglKunjungan,
            'desa_kelurahan' => $desa,
            'alamat'         => $this->safeString($row['alamat_anak'] ?? $row['alamat'] ?? null, 255),
            'rt'             => $this->safeString($row['rt'] ?? null, 50),
            'rw'             => $this->safeString($row['rw'] ?? null, 50),

            // Data anak
            'nama'           => $this->safeString($row['nama_anak'] ?? null, 150),
            'tgl_lahir'      => $tglLahir,
            'usia'           => $usia,
            'anak_ke'        => $anakKe,
            'jenis_kelamin'  => $this->safeString($row['jenis_kelamin'] ?? null, 10),
            'nik'            => $this->safeString($row['nik'] ?? null, 50),
            'nik_eppgbm'     => $this->safeString($row['nik_eppgbm'] ?? null, 50),
            'nomor_kk'       => $this->safeString($row['nomor_kk'] ?? null, 50),

            // Lahir
            'bb_lahir'       => $bb_lahir,
            'tb_lahir'       => $tb_lahir,

            // KIA / IMD / MBG
            'kia'            => $this->safeString($row['kia'] ?? null, 50),
            'imd'            => $this->safeString($row['imd'] ?? null, 50),
            'mbg'            => $this->safeString($row['mbg'] ?? null, 50),

            // ASI
            'asi_bulan_1'    => $this->safeString($row['asi_bulan_1'] ?? null, 50),
            'asi_bulan_2'    => $this->safeString($row['asi_bulan_2'] ?? null, 50),
            'asi_bulan_3'    => $this->safeString($row['asi_bulan_3'] ?? null, 50),
            'asi_bulan_4'    => $this->safeString($row['asi_bulan_4'] ?? null, 50),
            'asi_bulan_5'    => $this->safeString($row['asi_bulan_5'] ?? null, 50),
            'asi_bulan_6'    => $this->safeString($row['asi_bulan_6'] ?? null, 50),

            // Pengukuran
            'berat'          => $bb,
            'tinggi'         => $tb,
            'lila'           => $lila,
            'lingkar_kepala' => $kepala,
            'cara_ukur'      => $this->safeString($row['cara_ukur'] ?? null, 50),
            'edema'          => $this->safeString($row['edema'] ?? null, 20),

            // Intervensi
            'vit_a'          => $this->safeString($row['vit_a'] ?? null, 50),
            'pmt_biskuit'    => $this->safeString($row['pmt_biskuit'] ?? null, 50),
            'taburia'        => $this->safeString($row['taburia'] ?? null, 50),
            'kelas_ibu_balita' => $this->safeString($row['kelas_ibu_balita'] ?? null, 200),

            // Orang tua
            'nama_ortu'      => $this->safeString($row['nama_orang_tua'] ?? null, 150),
            'nik_ortu'       => $this->safeString($row['nik_orang_tua'] ?? null, 50),
            'hp_ortu'        => $this->safeString($row['nomor_hp_orang_tua'] ?? null, 50),

            // Z-score
            'bb_u'           => $this->toDecimal($row['bb_u'] ?? null),
            'zs_bb_u'        => $zs_bb_u,
            'status_bb_u'    => $this->safeString($row['kategori_bb_u'] ?? null, 100),
            'tb_u'           => $this->toDecimal($row['tb_u'] ?? null),
            'zs_tb_u'        => $zs_tb_u,
            'status_tb_u'    => $this->safeString($row['kategori_tb_u'] ?? null, 100),
            'bb_tb'          => $this->toDecimal($row['bb_tb'] ?? null),
            'zs_bb_tb'       => $zs_bb_tb,
            'status_bb_tb'   => $this->safeString($row['kategori_bb_tb'] ?? null, 100),

            // workflow
            'status'         => 'pending',
        ]);
    }
}
