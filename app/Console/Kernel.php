<?php

namespace App\Console;

use App\Console\Commands\PermissionCreate;
use App\Console\Commands\RoleCreate;
use App\Console\Commands\RoleRemove;
use App\Console\Commands\RoleUpdate;
use App\Console\Commands\UserCreate;
use App\Console\Commands\UserRemove;
use App\Console\Commands\UserUpdate;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        // Commands\Inspire::class,
        RoleCreate::class,
        RoleUpdate::class,
        RoleRemove::class,
        UserCreate::class,
        UserUpdate::class,
        UserRemove::class
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        // $schedule->command('inspire')
        //          ->hourly();
    }
}
