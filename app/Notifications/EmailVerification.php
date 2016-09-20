<?php

namespace App\Notifications;


use App\User;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class EmailVerification extends Notification
{
    /**
     * @var User
     */
    private $user;

    /**
     * EmailVerification constructor.
     * @param User $user
     */
    public function __construct(User $user)
    {
        $this->user = $user;
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail()
    {
        $siteName = settings('site.title', config('app.name'));
        
        return (new MailMessage())
            ->subject(trans('auth.subject_verification'))
            ->greeting(trans('user.Hello', ['name' => $this->user->name]))
            ->line(trans('auth.register_thanks', ['site' => $siteName]))
            ->line(trans('auth.mail_verification'))
            ->action(trans('auth.Verify'), url('auth/activation', $this->user->activation_token));
    }
}