<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class DataGizi extends Model
{
    use HasFactory;

    protected $table = 'data_gizis';

    protected $fillable = [
        'user_id',
        'period',
        'posyandu',
        'tgl_kunjungan',
        'desa_kelurahan',
        'alamat',
        'rt',
        'rw',
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
        'kia',
        'imd',
        'mbg',
        'asi_bulan_1',
        'asi_bulan_2',
        'asi_bulan_3',
        'asi_bulan_4',
        'asi_bulan_5',
        'asi_bulan_6',
        'berat',
        'tinggi',
        'lila',
        'lingkar_kepala',
        'cara_ukur',
        'edema',
        'vit_a',
        'pmt_biskuit',
        'taburia',
        'kelas_ibu_balita',
        'nama_ortu',
        'nik_ortu',
        'hp_ortu',
        'bb_u',
        'zs_bb_u',
        'status_bb_u',
        'tb_u',
        'zs_tb_u',
        'status_tb_u',
        'bb_tb',
        'zs_bb_tb',
        'status_bb_tb',
        'status',
        'is_published',
    ];

    protected $casts = [
        'period'        => 'date',
        'tgl_kunjungan' => 'date',
        'tgl_lahir'     => 'date',
        // Casting ke float/decimal penting agar sorting numerik akurat
        'berat'         => 'float',
        'tinggi'        => 'float',
        'zs_bb_u'       => 'float',
        'zs_tb_u'       => 'float',
        'zs_bb_tb'      => 'float',
    ];

    // Optimasi Memory: Definisikan Keyword di luar fungsi mutator
    // Agar tidak re-declare array 10.000 kali saat import
    private const JK_L_KEYWORDS = ['l', 'laki', 'laki-laki', 'laki laki', 'lk', 'cowok', 'cowo', 'lelaki', 'pria'];
    private const JK_P_KEYWORDS = ['p', 'perempuan', 'wanita', 'cewek', 'cewe', 'cw', 'gadis'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /*
    |--------------------------------------------------------------------------
    | SCOPES (Query Shortcut)
    |--------------------------------------------------------------------------
    | Membantu Controller agar query lebih ringkas dan konsisten
    */

    // Contoh guna: DataGizi::published()->get();
    public function scopePublished(Builder $query)
    {
        return $query->where('status', 'published');
    }

    // Contoh guna: DataGizi::byPeriod('2023-10')->get();
    public function scopeByPeriod(Builder $query, $periodYm)
    {
        // Handle input '2023-10' atau '2023-10-01'
        $date = strpos($periodYm, '-') !== false && strlen($periodYm) == 7 
            ? $periodYm . '-01' 
            : $periodYm;
            
        return $query->whereDate('period', $date);
    }

    /*
    |--------------------------------------------------------------------------
    | MUTATORS (Normalisasi Data)
    |--------------------------------------------------------------------------
    */

    public function setDesaKelurahanAttribute($value)
    {
        // PENTING: Index DB sensitif. Pastikan bersih.
        $this->attributes['desa_kelurahan'] = $value ? strtoupper(trim($value)) : null;
    }

    public function setPosyanduAttribute($value)
    {
        $this->attributes['posyandu'] = $value ? strtoupper(trim($value)) : null;
    }

    public function setNamaAttribute($value)
    {
        $this->attributes['nama'] = $value ? ucwords(strtolower(trim($value))) : null;
    }

    public function setNamaOrtuAttribute($value)
    {
        $this->attributes['nama_ortu'] = $value ? ucwords(strtolower(trim($value))) : null;
    }

    // Optimasi Logic Jenis Kelamin (High Performance for Loop)
    public function setJenisKelaminAttribute($value)
    {
        if (!$value) {
            $this->attributes['jenis_kelamin'] = null;
            return;
        }

        $v = strtolower(trim($value));

        // Cek Laki-laki (Pakai const, lebih hemat memori)
        if (in_array($v, self::JK_L_KEYWORDS, true)) {
            $this->attributes['jenis_kelamin'] = 'L';
            return;
        }

        // Cek Perempuan
        if (in_array($v, self::JK_P_KEYWORDS, true)) {
            $this->attributes['jenis_kelamin'] = 'P';
            return;
        }

        // Default null jika tidak dikenali
        $this->attributes['jenis_kelamin'] = null;
    }

    public function setZsBbUAttribute($value)
    {
        $this->attributes['zs_bb_u'] = ($value === '' || $value === null) ? null : $value;
    }

    public function setZsTbUAttribute($value)
    {
        $this->attributes['zs_tb_u'] = ($value === '' || $value === null) ? null : $value;
    }

    public function setZsBbTbAttribute($value)
    {
        $this->attributes['zs_bb_tb'] = ($value === '' || $value === null) ? null : $value;
    }
}