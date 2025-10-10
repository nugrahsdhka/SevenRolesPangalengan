<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DataDesa extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'jumlah_penduduk',
        'luas_wilayah',
        'potensi_desa',
        'is_published',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}