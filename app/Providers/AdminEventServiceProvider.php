<?php

namespace App\Providers;

use App\Events\PagePathChanged;
use App\Listeners\ChangeRoutePagePath;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class AdminEventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        PagePathChanged::class => [ChangeRoutePagePath::class]
    ];

    /**
     * Register any other events for your application.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();

        //
    }

    protected $subscribe = [

    ];
}
