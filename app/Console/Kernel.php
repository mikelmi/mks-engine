<?php

namespace App\Console;

use App\Console\Commands\AppInstall;
use App\Console\Commands\ModuleCacheClear;
use App\Console\Commands\ModuleMakeMigration;
use App\Console\Commands\ModuleMigrate;
use App\Console\Commands\ModuleMigrateInstall;
use App\Console\Commands\ModuleRollback;
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
        AppInstall::class,
        RoleCreate::class,
        RoleUpdate::class,
        RoleRemove::class,
        UserCreate::class,
        UserUpdate::class,
        UserRemove::class,
        ModuleCacheClear::class,
        ModuleMakeMigration::class,
        ModuleMigrate::class,
        ModuleRollback::class,
        ModuleMigrateInstall::class
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

    /**
     * Register the Closure based commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        require base_path('routes/console.php');
    }
}
