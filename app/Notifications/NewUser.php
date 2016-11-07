<?php

namespace App\Notifications;


use App\User;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NewUser extends Notification implements ReadableNotification
{
    use Queueable;

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
        return ['mail', 'database'];
    }

    public function toMail()
    {
        return (new MailMessage())
            ->subject('New User')
            ->greeting('New user details')
            ->line('Name: ' . $this->user->name)
            ->line('Email: ' . $this->user->email)
            ->line('ID: ' . $this->user->id);
    }

    public function toDatabase()
    {
        return [
            'user_id' => $this->user->id,
            'user_name' => $this->user->name,
            'user_email' => $this->user->email
        ];
    }

    public static function title($data)
    {
        return trans('events.new_user', ['name' => array_get($data, 'user_name')]);
    }

    public static function details($data)
    {
        return view('admin._partial.user-details', ['user' => User::find($data['user_id'])])->render();
    }
}