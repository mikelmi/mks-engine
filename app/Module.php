<?php

namespace App;


use App\Contracts\ModuleInterface;

class Module implements ModuleInterface
{
    /**
     * @var mixed
     */
    private $name;

    /**
     * @var string
     */
    private $path;

    /**
     * @var array
     */
    private $meta;

    /**
     * Module constructor.
     * @param string $path
     * @param array $meta
     */
    public function __construct($path, array $meta)
    {
        $this->path = $path;
        $this->meta = $meta;

        $this->name = $this->meta('name', ucfirst(basename($path)));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->getName();
    }

    /**
     * @param null|string $path
     * @return string
     */
    public function getPath($path = null)
    {
        return $this->path . ($path ? '/' . $path : $path);
    }

    /**
     * @return bool
     */
    public function isEnabled()
    {
        return $this->meta('enabled') == true;
    }

    /**
     * @param string $key
     * @param null|mixed $default
     * @return mixed
     */
    public function meta($key, $default = null)
    {
        return array_get($this->meta, $key, $default);
    }
}