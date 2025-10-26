<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DataGizi extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'desa_kelurahan',
        'berat',
        'tinggi',
        'status_bb_u',
        'zs_bb_u',
        'status_tb_u',
        'zs_tb_u',
        'status_bb_tb',
        'zs_bb_tb',
        'status',
        'is_published',
    ];

    protected $casts = [
        'berat' => 'decimal:2',
        'tinggi' => 'decimal:2',
        'zs_bb_u' => 'decimal:2',
        'zs_tb_u' => 'decimal:2',
        'zs_bb_tb' => 'decimal:2',
        'is_published' => 'boolean',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}