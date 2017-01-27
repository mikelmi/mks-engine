<?php

namespace App\Console\Commands;

use App\Traits\ModuleMigrator;
use Illuminate\Database\Console\Migrations\MigrateCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;

class ModuleMigrate extends MigrateCommand
{
    use ModuleMigrator;

    /**
     * The name of the console command.
     *
     * @var string
     */
    protected $name = 'module:migrate';

    protected $signature = 'module:migrate {module? : The name of module}
                {--database= : The database connection to use.}
                {--force : Force the operation to run when in production.}
                {--path= : The path of migrations files to be executed.}
                {--pretend : Dump the SQL queries that would be run.}
                {--seed : Indicates if the seed task should be re-run.}
                {--step : Force the migrations to be run so they can be rolled back individually.}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Run the database migrations for specified module or for all modules';

    public function __construct()
    {
        parent::__construct(app('module.migrator'));
    }

    /**
     * Get the console command options.
     *
     * @return array
     */
    protected function getOptions()
    {
        return [
            ['database', null, InputOption::VALUE_OPTIONAL, 'The database connection to use.'],

            ['force', null, InputOption::VALUE_NONE, 'Force the operation to run when in production.'],


            ['pretend', null, InputOption::VALUE_NONE, 'Dump the SQL queries that would be run.'],

            ['seed', null, InputOption::VALUE_NONE, 'Indicates if the seed task should be re-run.'],

            ['step', null, InputOption::VALUE_NONE, 'Force the migrations to be run so they can be rolled back individually.'],
        ];
    }
    
    protected function getArguments()
    {
        return [
            ['module', InputArgument::OPTIONAL, 'The name of module will be used.']
        ];
    }

    protected function prepareDatabase()
    {
        $this->migrator->setConnection($this->option('database'));

        if (! $this->migrator->repositoryExists()) {
            $options = ['--database' => $this->option('database')];

            $this->call('module:migrate:install', $options);
        }
    }
}
