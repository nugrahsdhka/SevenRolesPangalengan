<?php

namespace App\Support;

use App\Mail\NewComplaintMail;
use App\Models\Complaint;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

/**
 * Kirim email notifikasi pengaduan baru:
 *  - ke puskesmas yang dipilih pelapor (hanya itu),
 *  - ke camat dan IT Admin (semua pengaduan).
 *
 * Gagal kirim TIDAK boleh menggagalkan pengaduan warga: error hanya dicatat di log.
 */
class ComplaintNotifier
{
    /** @return array<string, string> [email => audience] */
    public static function recipients(Complaint $complaint): array
    {
        $cfg = config('pengaduan.notify');
        $out = [];

        $puskesmasKey   = mb_strtoupper(trim((string) $complaint->puskesmas));
        $puskesmasEmail = $cfg['puskesmas'][$puskesmasKey] ?? null;

        if (self::valid($puskesmasEmail)) {
            $out[mb_strtolower(trim($puskesmasEmail))] = 'puskesmas';
        } else {
            Log::warning('Pengaduan: email puskesmas belum dikonfigurasi', [
                'ticket'    => $complaint->ticket_code,
                'puskesmas' => $complaint->puskesmas,
            ]);
        }

        foreach (['camat', 'admin'] as $role) {
            $email = $cfg[$role] ?? null;

            if (self::valid($email)) {
                // Jika alamatnya sama dengan puskesmas, peran puskesmas yang dipakai.
                $out[mb_strtolower(trim($email))] ??= 'pantau';
            }
        }

        return $out;
    }

    public static function notify(Complaint $complaint): void
    {
        foreach (self::recipients($complaint) as $email => $audience) {
            try {
                // Satu email per penerima, supaya alamat lain tidak saling terlihat.
                Mail::to($email)->send(new NewComplaintMail($complaint, $audience));
            } catch (\Throwable $e) {
                Log::error('Pengaduan: gagal mengirim email notifikasi', [
                    'ticket' => $complaint->ticket_code,
                    'to'     => $email,
                    'error'  => $e->getMessage(),
                ]);
            }
        }
    }

    private static function valid(mixed $email): bool
    {
        return is_string($email) && filter_var(trim($email), FILTER_VALIDATE_EMAIL) !== false;
    }
}
