<?php

namespace App\Imports;

use App\Models\DataGizi;
use App\Models\ImportJob;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Illuminate\Contracts\Queue\ShouldQueue;
use Maatwebsite\Excel\Concerns\Importable;
use PhpOffice\PhpSpreadsheet\Shared\Date as ExcelDate;

class DataGiziImport implements ToModel, WithHeadingRow, WithChunkReading, WithBatchInserts, ShouldQueue
{
    use Importable;

    protected string $period;
    protected int $userId;
    protected ImportJob $importJob;

    // counter lokal supaya gak spam update DB
    protected int $localCounter = 0;

    public function __construct(string $period, int $userId, ImportJob $importJob)
    {
        $p = trim($period);

        // support "2025-11" -> "2025-11-01"
        if (preg_match('/^\d{4}-\d{2}$/', $p)) {
            $p .= '-01';
        }

        $this->period    = date('Y-m-01', strtotime($p));
        $this->userId    = $userId;
        $this->importJob = $importJob;
    }

    public function chunkSize(): int
    {
        return 500; // boleh kamu ubah
    }

    public function batchSize(): int
    {
        return 500;
    }

    // ================== PROGRESS HELPER ==================

    private function incrementProgress(): void
    {
        $this->localCounter++;

        // update DB tiap 20 baris biar ga kebanyakan query
        if ($this->localCounter >= 20) {
            $this->importJob->increment('processed_rows', $this->localCounter);
            $this->localCounter = 0;
        }
    }

    public function __destruct()
    {
        if ($this->localCounter > 0) {
            $this->importJob->increment('processed_rows', $this->localCounter);
        }
    }

    // ================== HELPER BERSIHKAN FORMULA ==================

    private function cleanFormulaForString($value): ?string
    {
        if ($value === null || $value === '') return null;

        $s = trim((string) $value);

        // Bukan formula
        if ($s === '' || $s[0] !== '=') {
            return $s;
        }

        // Formula: ambil teks di dalam tanda kutip
        if (preg_match_all('/"([^"]*)"/', $s, $matches)) {
            $last = trim(end($matches[1]));
            return $last !== '' ? $last : null;
        }

        // Formula tapi tidak ada string fallback → dianggap kosong
        return null;
    }

    private function cleanFormulaForNumeric($value)
    {
        if ($value === null || $value === '') return null;

        if (is_numeric($value)) return $value;

        $s = trim((string) $value);
        if ($s === '') return null;

        // Bukan formula → kembalikan apa adanya (nanti diproses lagi oleh toDecimal / toInt)
        if ($s[0] !== '=') {
            return $s;
        }

        // Formula: cari angka terakhir (boleh pakai koma/titik)
        if (preg_match_all('/-?\d+(?:[.,]\d+)?/', $s, $matches)) {
            $lastNumber = end($matches[0]); // string angka terakhir
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
        $v = preg_replace('/[^\d,\.\-]/', '', $v);
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

        // Excel serial
        if (is_numeric($value)) {
            try {
                return ExcelDate::excelToDateTimeObject($value)->format('Y-m-d');
            } catch (\Throwable $e) {
                // kalau gagal dianggap bukan serial, lanjut ke parsing string
            }
        }

        $s  = str_replace('/', '-', trim((string) $value));
        $ts = strtotime($s);
        if ($ts === false) {
            return null;
        }

        return date('Y-m-d', $ts);
    }

    // ================== INTI: 1 ROW EXCEL → 1 MODEL ==================

    public function model(array $row)
    {
        // Desa/Kel
        $desa = $this->safeString(
            $row['desa_kel'] ??
            $row['desakel'] ??
            $row['desa_kelurahan'] ??
            null,
            100
        );

        // Skip baris yang tidak punya desa
        if ($desa === null || $desa === '') {
            return null;
        }

        // ==================== ANGKA PENGUKURAN ====================
        $bb = $this->toDecimal($row['bb_kg'] ?? $row['berat'] ?? null);
        $tb = $this->toDecimal($row['tb_cm'] ?? $row['tinggi'] ?? null);

        $bb_lahir = $this->toDecimal($row['bb_lahir'] ?? $row['bb_lahir_kg'] ?? null);
        $tb_lahir = $this->toDecimal($row['tb_lahir'] ?? $row['tb_lahir_cm'] ?? null);

        $lila          = $this->toDecimal($row['lila'] ?? null);
        $lingkarKepala = $this->toDecimal($row['lingkar_kepala'] ?? null);

        // Z-score (numeric)
        $zs_bb_u  = $this->toDecimal($row['zs_bb_u'] ?? $row['zs_bbu'] ?? null);
        $zs_tb_u  = $this->toDecimal($row['zs_tb_u'] ?? $row['zs_tbu'] ?? null);
        $zs_bb_tb = $this->toDecimal($row['zs_bb_tb'] ?? $row['zs_bbtb'] ?? null);

        // BB/U, TB/U, BB/TB
        $bb_u  = $this->toDecimal($row['bb_u'] ?? null);
        $tb_u  = $this->toDecimal($row['tb_u'] ?? null);
        $bb_tb = $this->toDecimal($row['bb_tb'] ?? null);

        // Usia & Anak ke
        $usiaRaw   = $row['usia'] ?? $row['usia_bulan'] ?? null;
        $usiaInt   = $this->toIntOrNull($usiaRaw);
        $anakKeInt = $this->toIntOrNull($row['anak_ke'] ?? null);

        // Tanggal
        $tglKunjungan = $this->toDateYmd($row['tanggal_kunjungan'] ?? $row['tgl_kunjungan'] ?? null);
        $tglLahir     = $this->toDateYmd($row['tanggal_lahir'] ?? $row['tgl_lahir'] ?? null);

        // ==================== BUILD MODEL ====================

        // progress naik (hitung row yang bener-bener diinsert)
        $this->incrementProgress();

        return new DataGizi([
            'user_id'        => $this->userId,
            'period'         => $this->period,

            'posyandu'       => $this->safeString($row['nama_posyandu'] ?? $row['posyandu'] ?? null, 150),
            'tgl_kunjungan'  => $tglKunjungan,
            'desa_kelurahan' => $desa,
            'alamat'         => $this->safeString($row['alamat_anak'] ?? $row['alamat'] ?? null, 255),
            'rt'             => $this->safeString($row['rt'] ?? null, 50),
            'rw'             => $this->safeString($row['rw'] ?? null, 50),

            'nama'           => $this->safeString($row['nama_anak'] ?? $row['nama'] ?? null, 150),
            'tgl_lahir'      => $tglLahir,
            'usia'           => $usiaInt,
            'anak_ke'        => $anakKeInt,
            'jenis_kelamin'  => $this->safeString($row['jenis_kelamin'] ?? null, 10),
            'nik'            => $this->safeString($row['nik'] ?? null, 50),
            'nik_eppgbm'     => $this->safeString($row['nik_eppgbm'] ?? null, 50),
            'nomor_kk'       => $this->safeString($row['nomor_kk'] ?? null, 50),

            'bb_lahir'       => $bb_lahir,
            'tb_lahir'       => $tb_lahir,

            'kia'            => $this->safeString($row['kia'] ?? null, 50),
            'imd'            => $this->safeString($row['imd'] ?? null, 50),
            'mbg'            => $this->safeString($row['mbg'] ?? null, 50),

            'asi_bulan_1'    => $this->safeString($row['asi_bulan_1'] ?? null, 50),
            'asi_bulan_2'    => $this->safeString($row['asi_bulan_2'] ?? null, 50),
            'asi_bulan_3'    => $this->safeString($row['asi_bulan_3'] ?? null, 50),
            'asi_bulan_4'    => $this->safeString($row['asi_bulan_4'] ?? null, 50),
            'asi_bulan_5'    => $this->safeString($row['asi_bulan_5'] ?? null, 50),
            'asi_bulan_6'    => $this->safeString($row['asi_bulan_6'] ?? null, 50),

            'berat'          => $bb,
            'tinggi'         => $tb,
            'lila'           => $lila,
            'lingkar_kepala' => $lingkarKepala,
            'cara_ukur'      => $this->safeString($row['cara_ukur'] ?? null, 50),
            'edema'          => $this->safeString($row['edema'] ?? null, 20),

            'vit_a'          => $this->safeString($row['vit_a'] ?? null, 50),
            'pmt_biskuit'    => $this->safeString($row['pmt_biskuit'] ?? null, 50),
            'taburia'        => $this->safeString($row['taburia'] ?? null, 50),
            'kelas_ibu_balita' => $this->safeString($row['kelas_ibu_balita'] ?? null, 200),

            'nama_ortu'      => $this->safeString($row['nama_orang_tua'] ?? $row['nama_ortu'] ?? null, 150),
            'nik_ortu'       => $this->safeString($row['nik_orang_tua'] ?? $row['nik_ortu'] ?? null, 50),
            'hp_ortu'        => $this->safeString(
                $row['nomor_hp_orang_tua'] ??
                $row['no_hp_orang_tua'] ??
                $row['hp_ortu'] ?? null,
                50
            ),

            'bb_u'           => $bb_u,
            'zs_bb_u'        => $zs_bb_u,
            'status_bb_u'    => $this->safeString(
                $row['kategori_bb_u'] ??
                $row['status_bb_u'] ??
                $row['bbu'] ?? null,
                100
            ),

            'tb_u'           => $tb_u,
            'zs_tb_u'        => $zs_tb_u,
            'status_tb_u'    => $this->safeString(
                $row['kategori_tb_u'] ??
                $row['status_tb_u'] ??
                $row['tbu'] ?? null,
                100
            ),

            'bb_tb'          => $bb_tb,
            'zs_bb_tb'       => $zs_bb_tb,
            'status_bb_tb'   => $this->safeString(
                $row['kategori_bb_tb'] ??
                $row['status_bb_tb'] ??
                $row['bbtb'] ?? null,
                100
            ),

            'status'         => 'pending',
        ]);
    }
}
