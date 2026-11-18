<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class Complaint extends Model
{
    use HasFactory;

    public const KATEGORI = [
        'layanan_kesehatan' => 'Layanan Posyandu / Puskesmas',
        'bantuan_gizi'      => 'Bantuan Gizi / Makanan Tambahan',
        'kasus_stunting'    => 'Laporan Kasus Anak Stunting / Gizi Kurang',
        'sanitasi_air'      => 'Sanitasi & Air Bersih',
        'data_informasi'    => 'Data / Informasi pada Website',
        'lainnya'           => 'Lainnya',
    ];

    public const STATUS = [
        'baru'     => 'Baru',
        'diproses' => 'Diproses',
        'selesai'  => 'Selesai',
        'ditolak'  => 'Ditolak',
    ];

    protected $fillable = [
        'ticket_code',
        'nama',
        'kontak',
        'puskesmas',
        'desa_kelurahan',
        'posyandu',
        'kategori',
        'judul',
        'isi',
        'status',
        'tanggapan',
        'ditangani_oleh',
        'ditanggapi_at',
    ];

    protected $casts = [
        'ditanggapi_at' => 'datetime',
    ];

    /**
     * Batasi pengaduan sesuai user: IT Admin & Camat melihat semua,
     * petugas lain hanya pengaduan untuk puskesmasnya sendiri.
     */
    public function scopeVisibleTo($query, User $user)
    {
        if ($user->canViewAllComplaints()) {
            return $query;
        }

        if (blank($user->puskesmas)) {
            return $query->whereRaw('1 = 0');
        }

        return $query->whereRaw('LOWER(puskesmas) = ?', [mb_strtolower(trim($user->puskesmas))]);
    }

    public function penangan(): BelongsTo
    {
        return $this->belongsTo(User::class, 'ditangani_oleh');
    }

    public function getKategoriLabelAttribute(): string
    {
        return self::KATEGORI[$this->kategori] ?? ucfirst($this->kategori);
    }

    public function getStatusLabelAttribute(): string
    {
        return self::STATUS[$this->status] ?? ucfirst($this->status);
    }

    /**
     * Kode tiket acak, mis. PGD-7K2M9QXA (tanpa karakter yang mudah tertukar: 0/O, 1/I).
     */
    public static function generateTicketCode(): string
    {
        $alphabet = 'ABCDEFGHJKLMNPQRSTUVWXYZ23456789';

        do {
            $code = 'PGD-';
            for ($i = 0; $i < 8; $i++) {
                $code .= $alphabet[random_int(0, strlen($alphabet) - 1)];
            }
        } while (self::where('ticket_code', $code)->exists());

        return $code;
    }
}
