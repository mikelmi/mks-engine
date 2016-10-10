<?php

namespace App\Repositories;


use App\Contracts\ModuleInterface;
use App\Contracts\ModuleRepositoryInterface;
use App\Exceptions\ModuleNotFoundException;
use App\Module;
use App\Services\Json;
use Illuminate\Cache\Repository as CacheRepository;

class ModuleRepository implements ModuleRepositoryInterface
{
    /**
     * @var string
     */
    private $scanPath;

    /**
     * @var CacheRepository
     */
    private $cache;

    /**
     * @var array
     */
    private $modules;

    /**
     * @var string
     */
    private $cacheKey;

    /**
     * @var mixed
     */
    private $cacheLifetime;

    /**
     * ModuleRepository constructor.
     * @param CacheRepository $cache
     * @param $path
     * @param null $cacheLifetime
     * @param null $cacheKey
     */
    public function __construct(CacheRepository $cache, $path, $cacheLifetime = null, $cacheKey = null)
    {
        $this->cache = $cache;
        $this->scanPath = $path;

        $this->cacheKey = $cacheKey;
        $this->cacheLifetime = $cacheLifetime;
    }

    /**
     * Get all modules
     *
     * @return ModuleInterface[]
     */
    public function all()
    {
        if (!isset($this->modules)) {

            if (!$this->cacheKey) {
                $this->modules = $this->scan();
                return $this->modules;
            }

            $this->modules = $this->cache->get($this->cacheKey);

            if (!is_array($this->modules)) {
                $this->modules = $this->scan();

                if ($this->cacheLifetime) {
                    $this->cache->put($this->cacheKey, $this->modules, $this->cacheLifetime);
                } else {
                    $this->cache->forever($this->cacheKey, $this->modules);
                }
            }
        }

        return $this->modules;
    }

    /**
     * Get enabled modules
     *
     * @return ModuleInterface[]
     */
    public function enabled()
    {
        return $this->getByStatus(true);
    }

    /**
     * Get disabled modules
     *
     * @return ModuleInterface[]
     */
    public function disabled()
    {
        return $this->getByStatus(false);
    }

    /**
     * @param $status
     * @return ModuleInterface[]
     */
    public function getByStatus($status)
    {
        $modules = [];

        foreach ($this->all() as $name => $module) {
            if ($module->isEnabled() == $status) {
                $modules[$name] = $module;
            }
        }

        return $modules;
    }

    /**
     * Get ordered enabled modules
     *
     * @param string $direction
     * @return ModuleInterface[]
     */
    public function ordered($direction = 'asc')
    {
        $modules = $this->enabled();

        uasort($modules, function (ModuleInterface $a, ModuleInterface $b) use ($direction) {
            if ($direction == 'desc') {
                return $b->meta('order') <=> $a->meta('order');
            }

            return $a->meta('order') <=> $b->meta('order');
        });

        return $modules;
    }

    /**
     * @return int
     */
    public function count()
    {
        return count($this->all());
    }

    /**
     * Check if module exists
     *
     * @param string $name
     * @return bool
     */
    public function has($name)
    {
        return array_key_exists($name, $this->all());
    }

    /**
     * Scan and get all modules
     *
     * @return ModuleInterface[]
     */
    public function scan()
    {
        $modules = [];

        $manifests = glob("{$this->scanPath}/*/module.json");

        is_array($manifests) || $manifests = [];

        foreach ($manifests as $manifest) {
            $info = Json::make($manifest);
            $name = $info->get('name');
            $modules[$name] = new Module(dirname($manifest), Json::make($manifest)->all());
        }

        return $modules;
    }

    /**
     * Get module by name
     *
     * @param $name
     * @return ModuleInterface
     * @throws ModuleNotFoundException
     */
    public function get($name)
    {
        if (!is_null($module = $this->find($name))) {
            return $module;
        }

        throw new ModuleNotFoundException($name);
    }
    /**
     * Find module by name
     *
     * @param $name
     * @return ModuleInterface|null
     */
    public function find($name)
    {
        return array_get($this->all(), $name);
    }

    /**
     * Delete module by name
     *
     * @param $name
     * @return mixed
     */
    public function delete($name)
    {
        if ($this->has($name)) {
            $modules = $this->all();
            unset($modules[$name]);
            $this->modules = $modules;
        }
    }

    /**
     * Clear cached data
     * @return void
     */
    public function clear()
    {
        if ($this->cacheKey) {
            $this->cache->forget($this->cacheKey);
        }
    }
}