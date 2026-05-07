<?php

namespace App\Imports;

use App\Models\DataGizi;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use PhpOffice\PhpSpreadsheet\Shared\Date as ExcelDate;

class DataGiziImport implements ToModel, WithHeadingRow, WithChunkReading, WithBatchInserts
{
    protected string $period;
    protected int $userId;

    public function __construct(string $period, int $userId)
    {
        $p = trim($period);

        // izinkan input "2025-12" → jadikan "2025-12-01"
        if (preg_match('/^\d{4}-\d{2}$/', $p)) {
            $p .= '-01';
        }

        $this->period = date('Y-m-01', strtotime($p)); // paksa selalu tgl 1
        $this->userId = $userId;
    }

    public function chunkSize(): int
    {
        return 500;
    }

    public function batchSize(): int
    {
        return 500;
    }

    private function filterZScore($value): ?float
    {
        if ($value === null) return null;

        // Standar WHO Implausible values biasanya di luar -6 dan 6
        if ($value < -6 || $value > 6) {
            return null; 
        }

        return (float) $value;
    }

    // =========================================================
    // Helpers: bersihkan formula/string/numeric/tanggal
    // =========================================================

    private function cleanFormulaForString($value): ?string
    {
        if ($value === null || $value === '') return null;

        $s = trim((string) $value);

        // kalau bukan formula, kembalikan apa adanya
        if ($s === '' || $s[0] !== '=') {
            return $s;
        }

        // ambil teks terakhir di dalam "..."
        if (preg_match_all('/"([^"]*)"/', $s, $matches)) {
            $last = trim(end($matches[1]));
            return $last !== '' ? $last : null;
        }

        return null;
    }

    private function cleanFormulaForNumeric($value)
    {
        if ($value === null || $value === '') return null;

        if (is_numeric($value)) return $value;

        $s = trim((string) $value);
        if ($s === '') return null;

        // bukan formula, kembalikan string apa adanya
        if ($s[0] !== '=') {
            return $s;
        }

        // ambil angka terakhir di formula, contoh: =IF(...;2,5;1,0)
        if (preg_match_all('/-?\d+(?:[.,]\d+)?/', $s, $matches)) {
            $lastNumber = end($matches[0]);
            return $lastNumber;
        }

        return null;
    }

    private function safeString($value, ?int $maxLen = null): ?string
    {
        $value = $this->cleanFormulaForString($value);
        if ($value === null || $value === '') return null;

        $s = trim($value);
        if ($maxLen !== null && mb_strlen($s) > $maxLen) {
            $s = mb_substr($s, 0, $maxLen);
        }

        return $s;
    }

    private function toDecimal($value): ?float
    {
        $value = $this->cleanFormulaForNumeric($value);
        if ($value === null || $value === '') return null;

        if (is_numeric($value)) return (float) $value;

        $v = trim((string) $value);
        // buang semua selain angka, koma, titik, minus
        $v = preg_replace('/[^\d,\.\-]/', '', $v);
        // koma → titik
        $v = str_replace(',', '.', $v);

        if ($v === '' || !is_numeric($v)) {
            return null;
        }

        return (float) $v;
    }

    private function toIntOrNull($value): ?int
    {
        $value = $this->cleanFormulaForNumeric($value);
        if ($value === null || $value === '') return null;

        if (is_numeric($value)) return (int) $value;

        $s = trim((string) $value);

        if (preg_match('/-?\d+/', $s, $m)) {
            return (int) $m[0];
        }

        return null;
    }

    private function toDateYmd($value): ?string
    {
        $value = $this->cleanFormulaForNumeric($value);
        if ($value === null || $value === '') return null;

        // jika value numeric → kemungkinan besar serial date Excel
        if (is_numeric($value)) {
            try {
                return ExcelDate::excelToDateTimeObject($value)->format('Y-m-d');
            } catch (\Throwable $e) {
                // jika gagal, lanjut coba parse string di bawah
            }
        }

        $s  = str_replace('/', '-', trim((string) $value));
        $ts = strtotime($s);
        if ($ts === false) {
            return null;
        }

        return date('Y-m-d', $ts);
    }

    // =========================================================
    // Inti: mapping row Excel → DataGizi
    // =========================================================

    public function model(array $row)
    {
        // Nama kolom desa di Excel: "Desa/Kel" → slug: desa_kel
        $desa = $this->safeString(
            $row['desa_kel'] ??
            $row['desakel'] ??
            $row['desa_kelurahan'] ??
            null,
            100
        );

        // kalau tidak ada desa → skip baris ini
        if ($desa === null || $desa === '') {
            return null;
        }

        // BB/TB kunjungan
        $bb = $this->toDecimal($row['bb_kg'] ?? $row['berat'] ?? null);
        $tb = $this->toDecimal($row['tb_cm'] ?? $row['tinggi'] ?? null);

        // BB/TB lahir
        $bb_lahir = $this->toDecimal($row['bb_lahir'] ?? $row['bb_lahir_kg'] ?? null);
        $tb_lahir = $this->toDecimal($row['tb_lahir'] ?? $row['tb_lahir_cm'] ?? null);

        // LILA – di beberapa file bisa typo jadi "LLA"
        $lila = $this->toDecimal($row['lila'] ?? $row['lla'] ?? null);

        $lingkarKepala = $this->toDecimal($row['lingkar_kepala'] ?? null);

        // Z-score
        $zs_bb_u  = $this->filterZScore($this->toDecimal($row['zs_bb_u'] ?? $row['zs_bbu'] ?? null));
        $zs_tb_u  = $this->filterZScore($this->toDecimal($row['zs_tb_u'] ?? $row['zs_tbu'] ?? null));
        $zs_bb_tb = $this->filterZScore($this->toDecimal($row['zs_bb_tb'] ?? $row['zs_bbtb'] ?? null));


        // Indeks BB/U, TB/U, BB/TB (kolom angka, misal 2.5, -1.8, dst)
        $bb_u  = $this->toDecimal($row['bb_u'] ?? null);
        $tb_u  = $this->toDecimal($row['tb_u'] ?? null);
        $bb_tb = $this->toDecimal($row['bb_tb'] ?? null);

        // Usia & anak ke
        $usiaRaw   = $row['usia'] ?? $row['usia_bulan'] ?? null;
        $usiaInt   = $this->toIntOrNull($usiaRaw);
        $anakKeInt = $this->toIntOrNull($row['anak_ke'] ?? null);

        // Tanggal kunjungan & lahir
        $tglKunjungan = $this->toDateYmd($row['tanggal_kunjungan'] ?? $row['tgl_kunjungan'] ?? null);
        $tglLahir     = $this->toDateYmd($row['tanggal_lahir'] ?? $row['tgl_lahir'] ?? null);

        return new DataGizi([
            'user_id'        => $this->userId,
            'period'         => $this->period,

            // Identitas posyandu
            'posyandu'       => $this->safeString($row['nama_posyandu'] ?? $row['posyandu'] ?? null, 150),
            'tgl_kunjungan'  => $tglKunjungan,
            'desa_kelurahan' => $desa,
            'alamat'         => $this->safeString($row['alamat_anak'] ?? $row['alamat'] ?? null, 255),
            'rt'             => $this->safeString($row['rt'] ?? null, 50),
            'rw'             => $this->safeString($row['rw'] ?? null, 50),

            // Data anak
            'nama'           => $this->safeString($row['nama_anak'] ?? $row['nama'] ?? null, 150),
            'tgl_lahir'      => $tglLahir,
            'usia'           => $usiaInt,
            'anak_ke'        => $anakKeInt,
            'jenis_kelamin'  => $this->safeString($row['jenis_kelamin'] ?? null, 10),
            'nik'            => $this->safeString($row['nik'] ?? null, 50),
            'nik_eppgbm'     => $this->safeString($row['nik_eppgbm'] ?? null, 50),
            'nomor_kk'       => $this->safeString($row['nomor_kk'] ?? null, 50),

            // Lahir
            'bb_lahir'       => $bb_lahir,
            'tb_lahir'       => $tb_lahir,

            // KIA, IMD, MBG
            'kia'            => $this->safeString($row['kia'] ?? null, 50),
            'imd'            => $this->safeString($row['imd'] ?? null, 50),
            'mbg'            => $this->safeString($row['mbg'] ?? null, 50),

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
            'vit_a'          => $this->safeString($row['vit_a'] ?? null, 50),
            'pmt_biskuit'    => $this->safeString($row['pmt_biskuit'] ?? null, 50),
            'taburia'        => $this->safeString($row['taburia'] ?? null, 50),
            'kelas_ibu_balita' => $this->safeString($row['kelas_ibu_balita'] ?? null, 200),

            // Data orang tua
            'nama_ortu'      => $this->safeString($row['nama_orang_tua'] ?? $row['nama_ortu'] ?? null, 150),
            'nik_ortu'       => $this->safeString($row['nik_orang_tua'] ?? $row['nik_ortu'] ?? null, 50),
            'hp_ortu'        => $this->safeString(
                $row['nomor_hp_orang_tua'] ??
                $row['no_hp_orang_tua'] ??
                $row['hp_ortu'] ??
                null,
                50
            ),

            // BB/U
            'bb_u'           => $bb_u,
            'zs_bb_u'        => $zs_bb_u,
            'status_bb_u'    => $this->safeString(
                $row['kategori_bb_u'] ??   // format lama (kalau ada)
                $row['kategori_bbu'] ??    // ✅ sesuai header "Kategori BBU"
                $row['status_bb_u']   ??
                $row['bbu']           ??   // kalau header cuma "BBU"
                null,
                100
            ),

            // TB/U
            'tb_u'           => $tb_u,
            'zs_tb_u'        => $zs_tb_u,
            'status_tb_u'    => $this->safeString(
                $row['kategori_tb_u'] ??   // format lama (kalau ada)
                $row['kategori_tbu'] ??    // ✅ sesuai header "Kategori TBU"
                $row['status_tb_u']   ??
                $row['tbu']           ??   // kalau header cuma "TBU"
                null,
                100
            ),

            // BB/TB
            'bb_tb'          => $bb_tb,
            'zs_bb_tb'       => $zs_bb_tb,
            'status_bb_tb'   => $this->safeString(
                $row['kategori_bb_tb'] ??   // format lama
                $row['kategori_bbtb'] ??    // ✅ sesuai header "Kategori BB/TB" → slug: kategori_bbtb
                $row['status_bb_tb']   ??
                $row['bbtb']           ??
                null,
                100
            ),

            // Workflow
            'status'         => 'pending',
        ]);
    }
}
