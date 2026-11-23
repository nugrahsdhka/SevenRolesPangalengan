<?php

namespace App\Mail;

use App\Models\Complaint;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

/**
 * Notifikasi pengaduan baru.
 *
 * $audience:
 *  - 'puskesmas' : puskesmas yang diadukan (bisa menanggapi)
 *  - 'pantau'    : camat / IT Admin (hanya melihat)
 *
 * Sengaja TIDAK menyertakan nama & kontak pelapor: data itu hanya boleh
 * dilihat setelah login di aplikasi, bukan dikirim lewat email.
 */
class NewComplaintMail extends Mailable
{
    use SerializesModels;

    public function __construct(
        public Complaint $complaint,
        public string $audience = 'pantau',
    ) {
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: "Pengaduan Baru {$this->complaint->ticket_code} - {$this->complaint->puskesmas}",
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.new-complaint',
            with: [
                'complaint' => $this->complaint,
                'audience'  => $this->audience,
                'url'       => route('complaints.show', $this->complaint),
            ],
        );
    }
}
