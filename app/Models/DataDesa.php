<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class DataDesa extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'periode',
        'nama_desa',
        'jumlah_penduduk',
        'luas_wilayah',
        'potensi_desa',
        'jumlah_kasus_stunting',
        'status',
    ];

    protected $casts = [
        'periode' => 'date',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Mutator untuk otomatis mengubah nama_desa menjadi Title Case.
     */
    protected function namaDesa(): Attribute
    {
        return Attribute::make(
            set: fn ($value) => Str::title($value),
        );
    }
}