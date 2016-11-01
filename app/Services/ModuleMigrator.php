<?php

namespace App\Services;


use Illuminate\Database\Migrations\Migrator;
use Illuminate\Support\Arr;

class ModuleMigrator extends Migrator
{
    protected $modulePattern = null;

    protected function resolveModuleName($path)
    {
        if ($this->modulePattern && preg_match($this->modulePattern, $path, $m)) {
            return $m[1];
        }

        return null;
    }

    public function setModulePattern($pattern)
    {
        $this->modulePattern = $pattern;
    }

    protected function runUp($file, $batch, $pretend)
    {
        $module = $this->resolveModuleName($file);

        $file = $this->getMigrationName($file);

        // First we will resolve a "real" instance of the migration class from this
        // migration file name. Once we have the instances we can run the actual
        // command such as "up" or "down", or we can just simulate the action.
        $migration = $this->resolve($file);

        if ($pretend) {
            return $this->pretendToRun($migration, 'up');
        }

        $this->runMigration($migration, 'up');

        // Once we have run a migrations class, we will log that it was run in this
        // repository so that we don't try to run it next time we do a migration
        // in the application. A migration repository keeps the migrate order.
        $this->repository->log($file, $batch, $module);

        $this->note("<info>Migrated:</info> {$file}; Module: {$module}");
    }

    public function rollback($paths = [], array $options = [])
    {
        $this->notes = [];

        $rolledBack = [];

        $module = Arr::get($options, 'module');

        // We want to pull in the last batch of migrations that ran on the previous
        // migration operation. We'll then reverse those migrations and run each
        // of them "down" to reverse the last migration "operation" which ran.
        if (($steps = Arr::get($options, 'step', 0)) > 0) {
            $migrations = $this->repository->getMigrations($steps, $module);
        } else {
            $migrations = $this->repository->getLast($module);
        }

        $count = count($migrations);

        $files = $this->getMigrationFiles($paths);

        if ($count === 0) {
            $this->note('<info>Nothing to rollback.</info>');
        } else {
            // Next we will run through all of the migrations and call the "down" method
            // which will reverse each migration in order. This getLast method on the
            // repository already returns these migration's names in reverse order.
            $this->requireFiles($files);

            foreach ($migrations as $migration) {
                $migration = (object) $migration;

                $rolledBack[] = $files[$migration->migration];

                $this->runDown(
                    $files[$migration->migration],
                    $migration, Arr::get($options, 'pretend', false)
                );
            }
        }

        return $rolledBack;
    }
}