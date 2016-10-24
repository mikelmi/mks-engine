<?php

namespace App\Notifications;

use Illuminate\Auth\Notifications\ResetPassword as BaseResetPassword;
use Illuminate\Notifications\Messages\MailMessage;

class ResetPassword extends BaseResetPassword
{
    public function toMail($notifiable)
    {
        return (new MailMessage())
            ->subject(trans('passwords.subject'))
            ->line(trans('passwords.mail_text'))
            ->action(trans('auth.Reset Password'), url('password/reset', $this->token))
            ->line(trans('passwords.mail_note'));
    }
}