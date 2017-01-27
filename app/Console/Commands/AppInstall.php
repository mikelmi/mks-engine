<?php

namespace App\Console\Commands;

use App\Models\Role;
use App\User;
use Illuminate\Console\Command;

class AppInstall extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:install';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Install application';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->call('key:generate');
        $this->call('migrate');
        $this->call('module:migrate');

        if (Role::where('name', Role::ADMIN)->count() == 0) {
            $this->call('mks:role:create', [
                'name' => Role::ADMIN,
            ]);
        }

        $email = $this->ask('Admin email', 'admin@admin.com');

        if (User::where('email', $email)->count() > 0) {
            $this->warn('This email already exists');
            $this->info('App was successfully Installed');
            return;
        }

        $name = $this->ask('Admin name', 'Admin');
        $password = $this->ask('Admin password', 'admin');

        $validator = \Validator::make(compact('name', 'email', 'password'), [
            'name' => 'required|max:255',
            'email' => 'required|email|max:255|unique:users',
            'password' => 'required|min:5',
        ]);

        if ($validator->fails()) {
            foreach ($validator->errors() as $error) {
                $this->error($error);
            }
            return;
        }

        $this->call('mks:user:create', [
            'email' => $email,
            'name' => $name,
            '--password' => $password,
            '--role' => [Role::ADMIN],
            '--active' => 1
        ]);

        $this->info('App was successfully Installed');
    }
}
