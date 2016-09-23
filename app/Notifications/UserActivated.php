<?php

namespace App\Notifications;

use App\User;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class UserActivated extends Notification implements ReadableNotification
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

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['database'];
    }
    

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toDatabase($notifiable)
    {
        return [
            'user_id' => $this->user->id,
            'user_name' => $this->user->name,
            'user_email' => $this->user->email
        ];
    }

    public static function title($data)
    {
        return trans('events.user_activated', ['name' => array_get($data, 'user_name')]);
    }

    public static function details($data)
    {
        return view('admin._partial.user-details', ['user' => User::find($data['user_id'])])->render();
    }
}
