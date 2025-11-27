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
        'period',            // date (YYYY-MM-01)
        'desa_kelurahan',
        'berat',
        'tinggi',
        'zs_bb_u',
        'zs_tb_u',
        'zs_bb_tb',
        'status_bb_u',
        'status_tb_u',
        'status_bb_tb',
        'status',            // pending | approved | published

        // 🔹 kolom baru
        'nama',
        'nik',
        'posyandu',
    ];

    protected $casts = [
        'period' => 'date',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
