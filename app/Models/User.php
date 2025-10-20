<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles; // <-- IMPORT TRAIT

class User extends Authenticatable implements MustVerifyEmail
{
    use HasFactory, Notifiable, HasRoles, SoftDeletes; // <-- GUNAKAN TRAIT

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    // ... (di dalam class User)
    public function dataGizi()
    {
        return $this->hasMany(DataGizi::class);
    }

    public function dataPencegahan()
    {
        return $this->hasMany(DataPencegahan::class);
    }

    public function dataPenanggulangan()
    {
        return $this->hasMany(DataPenanggulangan::class);
    }

    public function dataDesa()
    {
        return $this->hasMany(DataDesa::class);
    }
}