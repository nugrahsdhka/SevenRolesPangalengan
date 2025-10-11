<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DataPencegahan extends Model {
    use HasFactory;
    protected $fillable = [
        'user_id', 
        'periode', 
        'judul_kegiatan', 
        'lokasi', 
        'tanggal_kegiatan', 
        'laporan_singkat', 
        'status'
    ];

    protected $casts = [
        'periode' => 'date', 
        'tanggal_kegiatan' => 'date'
    ];
    
    public function user(): BelongsTo { return $this->belongsTo(User::class); }
}