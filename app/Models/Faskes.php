<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Faskes extends Model
{
    use HasFactory;

    protected $table = 'faskes';

    // ✅ Izinkan kolom-kolom ini di-mass assign (create/update)
    protected $fillable = [
        'nama_faskes',
        'jenis',
        'alamat',
        'desa_kelurahan',
        'lat',
        'lng',
        'is_active',
    ];
}
