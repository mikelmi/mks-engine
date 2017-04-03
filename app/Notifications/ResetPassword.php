<?php

namespace App\Notifications;

use Illuminate\Auth\Notifications\ResetPassword as BaseResetPassword;
use Illuminate\Notifications\Messages\MailMessage;

class ResetPassword extends BaseResetPassword
{
    public function toMail($notifiable)
    {
        return (new MailMessage())
            ->subject(__('passwords.subject'))
            ->line(__('passwords.mail_text'))
            ->action(__('auth.Reset Password'), url('password/reset', $this->token))
            ->line(__('passwords.mail_note'));
    }
}