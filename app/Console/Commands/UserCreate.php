<?php

namespace App\Console\Commands;

use App\Models\Role;
use App\User;
use Illuminate\Console\Command;

class UserCreate extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'mks:user:create
        {email : Email}
        {name : Name}
        {--P|password= : Password}
        {--R|role=* : Roles}
        {--A|active : Active}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create new user';

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
        $password = $this->option('password');

        if (!$password) {
            $password = $this->secret('Password');
        }

        if (!$password) {
            $this->error('Password should be provided');

            return false;
        }

        \DB::beginTransaction();

        try {
            $user = new User();
            $user->email = $this->argument('email');
            $user->password = bcrypt($password);
            $user->name = $this->argument('name');

            if ($this->option('active')) {
                $user->active = true;
            }

            $user->save();

            $rolesNames = $this->option('role');

            if ($rolesNames) {
                $roles = Role::whereIn('name', $rolesNames)->get();
                $user->roles()->attach($roles);
            }

            \DB::commit();
            $this->info('User Created');

        } catch(\Exception $e) {
            \DB::rollback();
            $this->error($e->getMessage());
        }
    }
}
