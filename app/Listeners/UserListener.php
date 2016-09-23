<?php

namespace App\Listeners;

use App\Events\UserActivated;
use App\Events\UserRegistered;
use App\Notifications\EmailVerification;
use App\Notifications\NewUser;
use App\Notifications\UserWelcome;
use App\Services\Settings;
use App\User;
use Illuminate\Contracts\Events\Dispatcher;
use Illuminate\Support\Facades\Notification;

class UserListener
{
    /**
     * @var Settings
     */
    private $settings;

    /**
     * UserListener constructor.
     * @param Settings $settings
     */
    public function __construct(Settings $settings)
    {
        $this->settings = $settings;
    }

    public function subscribe(Dispatcher $dispatcher)
    {
        $dispatcher->listen(UserRegistered::class, self::class . '@onUserRegistered');
        $dispatcher->listen(UserActivated::class, self::class . '@onUserActivated');
    }
    
    public function onUserRegistered(UserRegistered $event)
    {
        $user = $event->getUser();
        
        if ($this->settings->get('users.verification')) {
            //send email verification
            if (!$user->active && $user->activation_token) {
                $user->notify(new EmailVerification($user));
            }
        } else {
            $user->notify(new UserWelcome($user));
        }

        //notify admin users
        Notification::send(User::admins()->get(), new NewUser($user));
    }

    public function onUserActivated(UserActivated $event)
    {
        $user = $event->getUser();

        $user->notify(new UserWelcome($user));

        //notify admin users
        Notification::send(User::admins()->get(), new \App\Notifications\UserActivated($user));
    }
}
