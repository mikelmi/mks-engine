<?php

namespace App\Providers;

use App\Listeners\AuthenticatedListener;
use App\Listeners\UserListener;
use Illuminate\Auth\Events\Authenticated;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();

        //
    }

    protected $subscribe = [
        UserListener::class
    ];
}
