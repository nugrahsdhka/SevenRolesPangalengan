<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;
use App\Models\Complaint;
use App\Notifications\ResetPasswordNotification;
use App\Notifications\VerifyEmailNotification;


class User extends Authenticatable implements MustVerifyEmail
{
    use HasFactory, Notifiable, HasRoles, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'puskesmas',
    ];

    /**
     * The attributes that should be hidden for arrays / JSON.
     */
    protected $hidden = [
        'password',
        'remember_token',
        'verification_token',
    ];

    /**
     * The attributes that should be cast.
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'verification_token_expires_at' => 'datetime',
    ];

    /**
     * Hash token verifikasi sebelum disimpan / dibandingkan.
     * HMAC dengan APP_KEY, sehingga token 6 digit tidak bisa ditebak offline
     * kalau isi database bocor.
     */
    public static function hashVerificationToken(string $token): string
    {
        return hash_hmac('sha256', $token, (string) config('app.key'));
    }

    /**
     * Petugas hanya boleh membalas pengaduan yang ditujukan ke puskesmasnya sendiri.
     */
    public function canRespondTo(Complaint $complaint): bool
    {
        return $this->hasRole('Divisi Gizi') && $this->belongsToComplaintPuskesmas($complaint);
    }

    /**
     * IT Admin & Camat memantau semua pengaduan (hanya lihat).
     */
    public function canViewAllComplaints(): bool
    {
        return $this->hasAnyRole(['IT Admin', 'Camat']);
    }

    public function canViewComplaint(Complaint $complaint): bool
    {
        return $this->canViewAllComplaints() || $this->belongsToComplaintPuskesmas($complaint);
    }

    public function belongsToComplaintPuskesmas(Complaint $complaint): bool
    {
        return filled($this->puskesmas)
            && filled($complaint->puskesmas)
            && mb_strtolower(trim($this->puskesmas)) === mb_strtolower(trim($complaint->puskesmas));
    }

    public function sendPasswordResetNotification($token)
    {
        $this->notify(new ResetPasswordNotification($token));
    }

    // Gantikan default notifikasi verifikasi email
    public function sendEmailVerificationNotification()
    {
        $this->notify(new VerifyEmailNotification());
    }

}
