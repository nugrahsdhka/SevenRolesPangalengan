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

        // support "2025-11" -> "2025-11-01"
        if (preg_match('/^\d{4}-\d{2}$/', $p)) {
            $p .= '-01';
        }

        $this->period = date('Y-m-01', strtotime($p));
        $this->userId = $userId;
    }

    /**
     * Ubah string/apa pun jadi float aman (dukung koma/titik).
     * Kalau gagal dibaca -> null.
     */
    private function toDecimal($value): ?float
    {
        if ($value === null || $value === '') return null;
        if (is_numeric($value)) return (float) $value;

        $v = trim((string) $value);
        $v = preg_replace('/[^\d,\.\-]/', '', $v);
        $v = str_replace(',', '.', $v);

        if ($v === '' || !is_numeric($v)) {
            return null;
        }

        return (float) $v;
    }

    /**
     * Potong string biar tidak lebih dari maxLen, dan trim.
     */
    private function safeString($value, ?int $maxLen = null): ?string
    {
        if ($value === null || $value === '') return null;

        $s = trim((string) $value);
        if ($maxLen !== null && mb_strlen($s) > $maxLen) {
            $s = mb_substr($s, 0, $maxLen);
        }
        return $s;
    }

    /**
     * Normalisasi tanggal ke format Y-m-d.
     * Support:
     * - Excel serial number
     * - "31/01/2025", "31-01-2025", "2025-01-31", dll.
     */
    private function toDateYmd($value): ?string
    {
        if ($value === null || $value === '') return null;

        // Excel serial
        if (is_numeric($value)) {
            try {
                return ExcelDate::excelToDateTimeObject($value)->format('Y-m-d');
            } catch (\Throwable $e) {
                return null;
            }
        }

        // String: ganti "/" jadi "-" biar strtotime lebih nurut
        $s = str_replace('/', '-', trim((string) $value));
        $ts = strtotime($s);
        if ($ts === false) {
            return null;
        }
        return date('Y-m-d', $ts);
    }

    /**
     * Konversi ke integer aman untuk kolom usia / anak_ke.
     * - Kalau numeric -> (int)
     * - Kalau string mulai "=" (formula) -> null
     * - Kalau ada angka di dalam teks -> ambil angka pertama
     * - Kalau nggak nemu angka -> null
     */
    private function toIntOrNull($value): ?int
    {
        if ($value === null || $value === '') {
            return null;
        }

        if (is_numeric($value)) {
            return (int) $value;
        }

        $s = trim((string) $value);

        // kalau ini formula Excel, kita anggap saja null
        if (isset($s[0]) && $s[0] === '=') {
            return null;
        }

        // cari angka di dalam string
        if (preg_match('/-?\d+/', $s, $m)) {
            return (int) $m[0];
        }

        return null;
    }

    public function model(array $row)
    {
        // =============== 1. Ambil & normalisasi desa ===============
        $desa = $row['desa_kel']
            ?? $row['desakel']
            ?? $row['desa_kelurahan']
            ?? null;

        $desa = $this->safeString($desa, 100);

        // Kalau desa kosong total → SKIP baris ini
        if ($desa === null || $desa === '') {
            return null;
        }

        // =============== 2. Angka pengukuran ===============
        $bb = $this->toDecimal($row['bb_kg'] ?? $row['berat'] ?? null);
        $tb = $this->toDecimal($row['tb_cm'] ?? $row['tinggi'] ?? null);

        $bb_lahir = $this->toDecimal($row['bb_lahir_kg'] ?? $row['bb_lahir'] ?? null);
        $tb_lahir = $this->toDecimal($row['tb_lahir_cm'] ?? $row['tb_lahir'] ?? null);

        $lila = $this->toDecimal($row['lila'] ?? $row['lila_cm'] ?? null);
        $lingkarKepala = $this->toDecimal($row['lingkar_kepala'] ?? $row['lingkar_kepala_cm'] ?? null);

        // Z-score (numeric)
        $zs_bb_u  = $this->toDecimal($row['zs_bb_u']  ?? $row['zs_bbu']  ?? null);
        $zs_tb_u  = $this->toDecimal($row['zs_tb_u']  ?? $row['zs_tbu']  ?? null);
        $zs_bb_tb = $this->toDecimal($row['zs_bb_tb'] ?? $row['zs_bbtb'] ?? null);

        $bb_u  = $this->toDecimal($row['bb_u'] ?? null);
        $tb_u  = $this->toDecimal($row['tb_u'] ?? null);
        $bb_tb = $this->toDecimal($row['bb_tb'] ?? null);

        // Usia & Anak ke -> integer
        $usiaRaw  = $row['usia'] ?? $row['usia_bulan'] ?? null;
        $usiaInt  = $this->toIntOrNull($usiaRaw);
        $anakKeInt = $this->toIntOrNull($row['anak_ke'] ?? null);

        // Tanggal-tanggal
        $tglKunjungan = $this->toDateYmd($row['tanggal_kunjungan'] ?? $row['tgl_kunjungan'] ?? null);
        $tglLahir     = $this->toDateYmd($row['tanggal_lahir'] ?? $row['tgl_lahir'] ?? null);

        // =============== 3. Build model ===============
        return new DataGizi([
            'user_id'        => $this->userId,
            'period'         => $this->period,

            // Identitas posyandu & kunjungan
            'posyandu'       => $this->safeString($row['nama_posyandu'] ?? $row['posyandu'] ?? null, 100),
            'tgl_kunjungan'  => $tglKunjungan,
            'desa_kelurahan' => $desa,
            'alamat'         => $this->safeString($row['alamat_anak'] ?? $row['alamat'] ?? null, 255),
            'rt'             => $this->safeString($row['rt'] ?? null, 5),
            'rw'             => $this->safeString($row['rw'] ?? null, 5),

            // Data anak
            'nama'           => $this->safeString($row['nama_anak'] ?? $row['nama'] ?? null, 100),
            'tgl_lahir'      => $tglLahir,
            'usia'           => $usiaInt,           // ← INT / NULL
            'anak_ke'        => $anakKeInt,        // ← INT / NULL
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
            'nama_ortu'      => $this->safeString($row['nama_orang_tua'] ?? $row['nama_ortu'] ?? null, 100),
            'nik_ortu'       => $this->safeString($row['nik_orang_tua'] ?? $row['nik_ortu'] ?? null, 30),
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
            'status'         => 'pending',
        ]);
    }
}
