<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DataPencegahan extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'judul_kegiatan',
        'lokasi',
        'tanggal_kegiatan',
        'laporan_singkat',
        'is_published',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}