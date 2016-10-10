<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class ModuleCacheClear extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'module:cache-clear';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Clear modules cache';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->laravel->make('modules')->clear();
    }
}
