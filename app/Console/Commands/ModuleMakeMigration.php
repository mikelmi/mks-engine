<?php

namespace App\Console\Commands;

use App\Repositories\ModuleRepository;
use App\Traits\ModuleMigrator;
use Illuminate\Database\Console\Migrations\MigrateMakeCommand;

class ModuleMakeMigration extends MigrateMakeCommand
{
    use ModuleMigrator;
    
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'module:make-migration
        {module : The name of module}
        {name : The name of the migration.}
        {--create= : The table to be created.}
        {--table= : The table to migrate.}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create new migration for the specified module';

    protected function getMigrationPath()
    {
        /** @var ModuleRepository $modules */
        $modules = $this->laravel->make('modules');

        $module = $modules->get($this->input->getArgument('module'));

        return $module->getPath('database/migrations');
    }
}
