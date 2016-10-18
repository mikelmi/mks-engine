<?php

namespace App\Traits;


trait ModuleMigrator
{
    /**
     * Get the path to the migration directory.
     *
     * @return string
     */
    protected function getMigrationPath()
    {
        $modules = $this->laravel->make('modules');

        $module = $modules->get($this->input->getArgument('module'));

        return $module->getPath('database/migrations');
    }

    /**
     * Get all of the migration paths.
     *
     * @return array
     */
    protected function getMigrationPaths()
    {
        if ($this->input->hasArgument('module') && $this->input->getArgument('module')) {
            return [$this->getMigrationPath()];
        }

        $modules = $this->laravel->make('modules');

        $result = [];
        foreach ($modules->enabled() as $name => $module) {
            $result[] = $module->getPath('database/migrations');
        }

        return $result;
    }
}