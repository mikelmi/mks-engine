<?php

namespace App\Events;

use App\User;

class UserRegistered extends Event
{
    /**
     * @var User
     */
    private $user;

    /**
     * UserRegistered constructor.
     * @param User $user
     */
    public function __construct(User $user)
    {
        $this->user = $user;
    }

    /**
     * @return User
     */
    public function getUser()
    {
        return $this->user;
    }
}
