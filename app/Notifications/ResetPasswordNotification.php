<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ResetPasswordNotification extends Notification
{
    use Queueable;

    public $token;

    /**
     * Buat instance baru untuk notifikasi reset password.
     */
    public function __construct($token)
    {
        $this->token = $token;
    }

    /**
     * Tentukan channel pengiriman (email).
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Format email reset password-nya.
     */
    public function toMail($notifiable)
    {
        $resetUrl = url(route('password.reset', [
            'token' => $this->token,
            'email' => $notifiable->getEmailForPasswordReset(),
        ], false));

        return (new MailMessage)
            ->subject('🔐 Reset Password - Rumah Stunting Pangalengan')
            ->view('emails.reset-password-notification', [
                'url'  => $resetUrl,
                'user' => $notifiable,
            ]);
    }

}
