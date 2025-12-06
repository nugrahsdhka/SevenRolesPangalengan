<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ImportJob extends Model
{
    protected $fillable = [
        'user_id',
        'type',
        'period',
        'file_path',
        'total_rows',
        'processed_rows',
        'status',
        'error_message',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
