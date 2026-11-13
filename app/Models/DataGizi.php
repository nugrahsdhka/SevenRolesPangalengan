<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DataGizi extends Model
{
    use HasFactory;

    protected $table = 'data_gizis';

    protected $fillable = [
        'user_id',
        'period',

        // Identitas posyandu & kunjungan
        'posyandu',
        'tgl_kunjungan',
        'desa_kelurahan',
        'alamat',
        'rt',
        'rw',

        // Data anak
        'nama',
        'tgl_lahir',
        'usia',
        'anak_ke',
        'jenis_kelamin',
        'nik',
        'nik_eppgbm',
        'nomor_kk',
        'bb_lahir',
        'tb_lahir',

        // KIA, IMD, MBG
        'kia',
        'imd',
        'mbg',

        // ASI Bulan 1–6
        'asi_bulan_1',
        'asi_bulan_2',
        'asi_bulan_3',
        'asi_bulan_4',
        'asi_bulan_5',
        'asi_bulan_6',

        // Pengukuran kunjungan
        'berat',
        'tinggi',
        'lila',
        'lingkar_kepala',
        'cara_ukur',
        'edema',

        // Vitamin & intervensi
        'vit_a',
        'pmt_biskuit',
        'taburia',
        'kelas_ibu_balita',

        // Data orang tua
        'nama_ortu',
        'nik_ortu',
        'hp_ortu',

        // BB/U
        'bb_u',
        'zs_bb_u',
        'status_bb_u',

        // TB/U
        'tb_u',
        'zs_tb_u',
        'status_tb_u',

        // BB/TB
        'bb_tb',
        'zs_bb_tb',
        'status_bb_tb',

        // Workflow
        'import_notes',
        'status',
        'is_published',
    ];

    protected $casts = [
        'period'        => 'date',
        'tgl_kunjungan' => 'date',
        'tgl_lahir'     => 'date',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /*
    |--------------------------------------------------------------------------
    | Mutators – Normalisasi data sebelum disimpan
    |--------------------------------------------------------------------------
    */

    // Desa/Kelurahan → UPPERCASE
    public function setDesaKelurahanAttribute($value)
    {
        $this->attributes['desa_kelurahan'] =
            $value !== null ? strtoupper(trim($value)) : null;
    }

    // Posyandu → UPPERCASE
    public function setPosyanduAttribute($value)
    {
        $this->attributes['posyandu'] =
            $value !== null ? strtoupper(trim($value)) : null;
    }

    // Nama anak → Title Case
    public function setNamaAttribute($value)
    {
        $this->attributes['nama'] =
            $value !== null ? ucwords(strtolower(trim($value))) : null;
    }

    // Nama orang tua → Title Case
    public function setNamaOrtuAttribute($value)
    {
        $this->attributes['nama_ortu'] =
            $value !== null ? ucwords(strtolower(trim($value))) : null;
    }

    public function setJenisKelaminAttribute($value)
    {
        if ($value === null) {
            $this->attributes['jenis_kelamin'] = null;
            return;
        }

        $v = strtolower(trim($value));

        $l_keywords = [
            'l', 'laki', 'laki-laki', 'laki laki',
            'lk', 'cowok', 'cowo', 'lelaki', 'pria'
        ];

        $p_keywords = [
            'p', 'perempuan', 'wanita', 'cewek', 'cewe', 'cw', 'gadis'
        ];

        if (in_array($v, $l_keywords)) {
            $this->attributes['jenis_kelamin'] = 'L';
        } elseif (in_array($v, $p_keywords)) {
            $this->attributes['jenis_kelamin'] = 'P';
        } else {
            $this->attributes['jenis_kelamin'] = null;
        }
    }

    /**
     * Normalize WHO Z-Score values.
     *
     * Values outside the plausible range (-6 to 6), non-numeric values,
     * and non-finite numbers are stored as null.
     */
    private function normalizeZScore($value): ?float
    {
        if ($value === null || $value === '') {
            return null;
        }

        if (! is_numeric($value)) {
            return null;
        }

        $numericValue = (float) $value;

        if (! is_finite($numericValue) || $numericValue < -6 || $numericValue > 6) {
            return null;
        }

        return $numericValue;
    }

    // ZS BB/U
    public function setZsBbUAttribute($value)
    {
        $this->attributes['zs_bb_u'] = $this->normalizeZScore($value);
    }

    // ZS TB/U
    public function setZsTbUAttribute($value)
    {
        $this->attributes['zs_tb_u'] = $this->normalizeZScore($value);
    }

    // ZS BB/TB
    public function setZsBbTbAttribute($value)
    {
        $this->attributes['zs_bb_tb'] = $this->normalizeZScore($value);
    }
}
