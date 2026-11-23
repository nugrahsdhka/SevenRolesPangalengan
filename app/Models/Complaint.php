<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;
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
        'status_diperbarui_at',
        'dibuang_at',
        'dibuang_oleh',
    ];

    /** Status akhir: hanya pengaduan berstatus ini yang boleh dibuang ke sampah. */
    public const STATUS_AKHIR = ['selesai', 'ditolak'];

    /** Lama (bulan) pengaduan selesai/ditolak disimpan sebelum dihapus permanen otomatis. */
    public const RETENSI_BULAN = 3;

    protected $casts = [
        'ditanggapi_at'        => 'datetime',
        'status_diperbarui_at' => 'datetime',
        'dibuang_at'           => 'datetime',
    ];

    /** Pengaduan yang tampil di halaman utama (belum dibuang ke sampah). */
    public function scopeAktif($query)
    {
        return $query->whereNull('dibuang_at');
    }

    /** Pengaduan yang ada di halaman sampah. */
    public function scopeDibuang($query)
    {
        return $query->whereNotNull('dibuang_at');
    }

    /** Pengaduan selesai/ditolak yang status terakhirnya sudah lewat masa retensi. */
    public function scopeKedaluwarsa($query)
    {
        return $query->whereIn('status', self::STATUS_AKHIR)
            // Data tanpa status_diperbarui_at (mis. dibuat lewat seeder) memakai updated_at.
            ->whereRaw('COALESCE(status_diperbarui_at, updated_at) <= ?', [
                now()->subMonthsNoOverflow(self::RETENSI_BULAN),
            ]);
    }

    public function isDibuang(): bool
    {
        return $this->dibuang_at !== null;
    }

    public function isStatusAkhir(): bool
    {
        return in_array($this->status, self::STATUS_AKHIR, true);
    }

    /** Waktu pengaduan ini akan dihapus permanen otomatis (null jika belum berstatus akhir). */
    public function hapusOtomatisPada(): ?Carbon
    {
        if (! $this->isStatusAkhir()) {
            return null;
        }

        return ($this->status_diperbarui_at ?? $this->updated_at)->copy()->addMonthsNoOverflow(self::RETENSI_BULAN);
    }

    public function pembuang(): BelongsTo
    {
        return $this->belongsTo(User::class, 'dibuang_oleh');
    }

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
