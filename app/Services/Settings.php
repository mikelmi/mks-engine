<?php

namespace App\Services;


use Illuminate\Config\Repository;
use Illuminate\Filesystem\Filesystem;

class Settings extends Repository
{
    /**
     * @var Filesystem
     */
    protected $storage;

    /**
     * @var string
     */
    protected $path;

    protected $type;

    /**
     * @param Filesystem $storage
     * @param string $path
     */
    public function __construct(Filesystem $storage, $path) {
        $this->storage = $storage;
        $this->path = $path;

        $this->type = pathinfo($path, PATHINFO_EXTENSION);

        parent::__construct($this->load());
    }

    /**
     * @return array
     */
    protected function load() {
        if (!$this->storage->exists($this->path)) {
            $this->storage->put($this->path, $this->prepare([]));
        }

        return $this->read();
    }

    /**
     * @return array
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     */
    protected function read() {
        $result = null;

        switch($this->type) {
            case 'php':
                $result = $this->storage->getRequire($this->path);
                break;
            case 'json':
                return json_decode($this->storage->get($this->path), true);
                break;
            default: $result = [];
        }

        if (!is_array($result)) {
            $result = [];
        }

        return $result;
    }

    /**
     * @return int
     */
    public function save() {
        $result = $this->storage->put($this->path, $this->prepare($this->all()));

        if ($this->type == 'php') {
            //clear file stat cache
            clearstatcache(true, $this->path);
            // clear opcache for the next require config
            if (function_exists('opcache_reset')) {
                opcache_reset();
            }
        }

        return $result;
    }

    /**
     * @param array $data
     * @return string
     */
    protected function prepare(array $data) {
        switch($this->type) {
            case 'php':
                return "<?php\n\nreturn " . var_export($data, true) . ";\n";
                break;
            case 'json':
                return json_encode($data);
                break;
            default: return '';
        }
    }

    public function forget($key) {
        array_forget($this->items, $key);
    }

    public function getRepository($key)
    {
        return new Repository((array) $this->get($key, []));
    }
}