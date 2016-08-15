<?php

namespace App\Console\Commands;

use App\Models\Role;
use Illuminate\Console\Command;

class RoleUpdate extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'mks:role:update
        {name : Role name}
        {--N|name= : New name}
        {--D|display= : Display name}
        {--A|desc= : Description}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Role update';

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

        $role = Role::where('name', $name)->first();

        if (!$role) {
            $this->error('Role not found');

            return false;
        }

        $newName = $this->option('name');
        $displayName = $this->option('display');
        $description = $this->option('desc');

        if ($newName) {
            $role->name = $newName;
        }

        if ($displayName) {
            $role->display_name = $displayName;
        }

        if ($description) {
            $role->description = $description;
        }

        if ($role->save()) {
            $this->info('Role update');
        } else {
            $this->error('An error occurred');
        }
    }
}
