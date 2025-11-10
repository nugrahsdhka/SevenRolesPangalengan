<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class VerifyEmailNotification extends Notification
{
    use Queueable;

    protected $token;

    /**
     * Kirim token lewat constructor
     */
    public function __construct(string $token)
    {
        $this->token = $token;
    }

    /**
     * Channel pengiriman
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Format email (pakai Blade custom yang berisi token)
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('✅ Verifikasi Email - Rumah Stunting Pangalengan')
            ->view('emails.verify-email-notification', [
                'user'  => $notifiable,
                'token' => $this->token,
            ]);
    }
}
