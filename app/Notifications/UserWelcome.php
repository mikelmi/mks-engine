<?php

namespace App\Notifications;


use App\User;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class UserWelcome extends Notification
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
            ->subject($siteName)
            ->greeting(__('user.Hello', ['name' => $this->user->name]))
            ->line(__('auth.register_thanks', ['site' => $siteName]))
            ->line(__('user.mail_welcome'))
            ->line(__('auth.Username') . ': ' . $this->user->email)
            ->action(__('auth.Sign In'), url('auth/login'));
    }
}