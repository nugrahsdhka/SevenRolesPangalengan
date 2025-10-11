<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DataPenanggulangan extends Model {
    use HasFactory;

    protected $fillable = [
        'user_id', 
        'periode', 
        'jenis_bencana', 
        'jumlah_korban', 
        'tindakan', 
        'status'
    ];

    protected $casts = [
        'periode' => 'date'
    ];
    
    public function user(): BelongsTo { return $this->belongsTo(User::class); }
}