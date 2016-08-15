<?php

namespace App\Console\Commands;

use App\Models\Role;
use Illuminate\Console\Command;

class RoleCreate extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'mks:role:create
        {name : Role name}
        {--D|display= : Display name}
        {--A|desc= : Description}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create role';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $name = $this->argument('name');

        $role = new Role();
        $role->name = $name;

        $displayName = $this->option('display');
        $description = $this->option('desc');

        if ($displayName) {
            $role->display_name = $displayName;
        }

        if ($description) {
            $role->description = $description;
        }

        if ($id = $role->save()) {
            $this->info('Role created (ID: ' . $id . ')');
        } else {
            $this->error('An error occurred');
        }
    }
}
