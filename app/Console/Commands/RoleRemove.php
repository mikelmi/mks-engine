<?php

namespace App\Console\Commands;

use App\Models\Role;
use Illuminate\Console\Command;

class RoleRemove extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'mks:role:remove {name}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Remove role by name';

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

        if ($role->delete()) {
            $this->info('Role removed');
        } else {
            $this->error('An error occurred');
        }
    }
}
