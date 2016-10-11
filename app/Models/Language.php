<?php

namespace App\Models;


use Illuminate\Contracts\Support\Arrayable;

class Language implements Arrayable
{
    /**
     * @var string
     */
    private $iso;

    /**
     * @var string
     */
    private $name;

    /**
     * @var string
     */
    private $title;

    /**
     * @var bool
     */
    private $enabled;

    /**
     * @var array;
     */
    private $params;

    /**
     * @return string
     */
    public function getIso()
    {
        return $this->iso;
    }

    /**
     * @param string $iso
     */
    public function setIso($iso)
    {
        $this->iso = $iso;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @param string $title
     */
    public function setTitle($title)
    {
        $this->title = $title;
    }

    /**
     * @return boolean
     */
    public function isEnabled()
    {
        return $this->enabled;
    }

    /**
     * @return bool
     */
    public function getEnabled()
    {
        return $this->isEnabled();
    }

    /**
     * @param boolean $enabled
     */
    public function setEnabled($enabled)
    {
        $this->enabled = $enabled;
    }

    /**
     * @return array
     */
    public function getParams()
    {
        return $this->params;
    }

    /**
     * @param array $params
     */
    public function setParams(array $params)
    {
        $this->params = $params;
    }

    /**
     * Language constructor.
     * @param array|null $data
     */
    public function __construct(array $data = null)
    {
        $this->params = [];

        if ($data) {
            foreach ($data as $key => $value) {
                $method = 'set' . ucfirst($key);
                if (method_exists($this, $method)) {
                    $this->$method($value);
                }
            }
        }
    }

    /**
     * @param string $name
     * @return mixed|null
     */
    public function __get($name)
    {
        return $this->get($name);
    }

    /**
     * @param string $name
     * @param mixed $value
     */
    public function __set($name, $value)
    {
        $method = 'set' . ucfirst($name);

        if (method_exists($this, $method)) {
            $this->$method($value);
        }
    }

    /**
     * @param $name
     * @param null $default
     * @return null
     */
    public function get($name, $default = null)
    {
        $method = 'get' . ucfirst($name);

        if (method_exists($this, $method)) {
            return $this->$method();
        }

        return array_get($this->params, $name, $default);
    }

    public function __isset($name)
    {
        return method_exists($this, 'get' . ucfirst($name));
    }

    /**
     * @return string
     */
    public function iconUrl()
    {
        return route('lang.icon', $this->iso);
    }

    /**
     * @return string
     */
    public function iconImage()
    {
        return sprintf('<img src="%s" alt="" width="12" height="10">', $this->iconUrl());
    }

    /**
     * @return array
     */
    public function toArray()
    {
        $reflect = new \ReflectionClass($this);

        $result = [];

        foreach ($reflect->getProperties() as $prop) {
            $result[$prop->getName()] = $this->get($prop->getName());
        }

        return $result;
    }
}