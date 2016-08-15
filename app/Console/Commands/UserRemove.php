<?php

namespace App\Console\Commands;

use App\User;
use Illuminate\Console\Command;

class UserRemove extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'mks:user:remove {email : Email}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Remove user by email';

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
        $email = $this->argument('email');

        $user = User::where('email', $email)->first();

        if (!$user) {
            $this->error('User not found');

            return false;
        }

        if ($user->delete()) {
            $this->info('User removed');
        } else {
            $this->error('An error occurred');
        }
    }
}
