<?php

namespace App\Contracts;


use App\Exceptions\ModuleNotFoundException;

interface ModuleRepositoryInterface
{
    /**
     * Get all modules
     *
     * @return ModuleInterface[]
     */
    public function all();

    /**
     * Get enabled modules
     *
     * @return ModuleInterface[]
     */
    public function enabled();

    /**
     * Get disabled modules
     *
     * @return ModuleInterface[]
     */
    public function disabled();

    /**
     * Get ordered enabled modules
     *
     * @param string $direction
     * @return ModuleInterface[]
     */
    public function ordered($direction = 'asc');

    /**
     * @return int
     */
    public function count();

    /**
     * Check if module exists
     *
     * @param string $name
     * @return bool
     */
    public function has($name);

    /**
     * Scan and get all modules
     *
     * @return ModuleInterface[]
     */
    public function scan();

    /**
     * Get module by name
     *
     * @param $name
     * @return ModuleInterface
     * @throws ModuleNotFoundException
     */
    public function get($name);

    /**
     * Find module by name
     *
     * @param $name
     * @return ModuleInterface|null
     */
    public function find($name);

    /**
     * Delete module by name
     *
     * @param $name
     * @return mixed
     */
    public function delete($name);

    /**
     * Clear cached data
     * @return void
     */
    public function clear();
}