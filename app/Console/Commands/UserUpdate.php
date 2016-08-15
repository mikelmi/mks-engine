<?php

namespace App\Console\Commands;

use App\Models\Role;
use App\User;
use Illuminate\Console\Command;

class UserUpdate extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'mks:user:update
        {email : Email}
        {--E|email= : New Email}
        {--N|name= : Name}
        {--P|password= : Password}
        {--R|role=* : Roles}
        {--U|unrole : Delete all roles}
        {--A|activate : Activate}
        {--D|deactivate : Deactivate}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update user';

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
        \DB::beginTransaction();

        try {
            $email = $this->argument('email');

            $user = User::where('email', $email)->first();

            if (!$user) {
                throw new \Exception('User not found');
            }

            $name = $this->option('name');
            $password = $this->option('password');

            if ($email) {
                $user->email = $email;
            }

            if ($name) {
                $user->name = $name;
            }

            if ($password) {
                $user->password = bcrypt($password);
            }

            if ($this->option('activate')) {
                $user->active = true;
            } elseif ($this->option('deactivate')) {
                $user->active = false;
            }

            $user->save();

            if ($this->option('unrole')) {
                $user->roles()->detach();
            } else {
                $rolesNames = $this->option('role');

                if ($rolesNames) {
                    $roles = Role::whereIn('name', $rolesNames)->pluck('id')->toArray();
                    $user->roles()->sync($roles);
                }
            }

            \DB::commit();
            $this->info('User Updated');

        } catch(\Exception $e) {
            \DB::rollback();
            $this->error($e->getMessage());
        }
    }
}
