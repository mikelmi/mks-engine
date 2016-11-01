<?php

namespace App\Console\Commands;


use App\Repositories\ModulesMigrationRepository;
use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;

class ModuleMigrateInstall extends Command
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'module:migrate:install';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create the module migration repository';

    /**
     * The repository instance.
     *
     * @var \Illuminate\Database\Migrations\MigrationRepositoryInterface
     */
    protected $repository;

    public function __construct(ModulesMigrationRepository $repository)
    {
        parent::__construct();

        $this->repository = $repository;
    }

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function fire()
    {
        $this->repository->setSource($this->input->getOption('database'));

        $this->repository->createRepository();

        $this->info('Migration table created successfully.');
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
        ];
    }
}