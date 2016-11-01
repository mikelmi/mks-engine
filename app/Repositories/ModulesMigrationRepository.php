<?php

namespace App\Repositories;


use Illuminate\Database\Migrations\DatabaseMigrationRepository;

class ModulesMigrationRepository extends DatabaseMigrationRepository
{
    public function createRepository()
    {
        $schema = $this->getConnection()->getSchemaBuilder();

        $schema->create($this->table, function ($table) {
            // The migrations table is responsible for keeping track of which of the
            // migrations have actually run for the application. We'll create the
            // table to hold the migration file's path as well as the batch ID.
            $table->increments('id');

            $table->string('migration');

            $table->integer('batch');

            $table->string('module');
        });
    }

    public function log($file, $batch)
    {
        $module = func_get_arg(2);
        $record = ['migration' => $file, 'batch' => $batch, 'module' => $module];

        $this->table()->insert($record);
    }

    public function getMigrations($steps, $module = null)
    {
        $query = $this->table()->where('batch', '>=', '1');

        if ($module) {
            $query->where('module', $module);
        }

        return $query->orderBy('migration', 'desc')->take($steps)->get()->all();
    }

    public function getLast($module = null)
    {
        $query = $this->table()->where('batch', $this->getLastBatchNumber($module));

        if ($module) {
            $query->where('module', $module);
        }

        return $query->orderBy('migration', 'desc')->get()->all();
    }

    public function getLastBatchNumber($module = null)
    {
        $query = $this->table();

        if ($module) {
            $query->where('module', $module);
        }

        return $query->max('batch');
    }
}