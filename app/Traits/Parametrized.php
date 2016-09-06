<?php

namespace App\Traits;


use Illuminate\Support\Collection;

trait Parametrized
{
    protected $params_field = 'params';

    /**
     * @var Collection
     */
    protected $paramsDecoded;

    public function __construct(array $attributes = [])
    {
        $this->initParams();

        parent::__construct($attributes);
    }

    public function initParams() {
        $this->casts[$this->params_field] = 'collection';
    }

    /**
     * Get parameter(s)
     * 
     * @param null|string $key
     * @param null|string $default
     * @return Collection|mixed
     */
    public function param($key = null, $default = null)
    {
        $params = $this->getAttribute($this->params_field);

        return $key === null ? $params : $params->get($key, $default);
    }

    /**
     * @param $key
     * @return Collection|mixed
     */
    public function getAttribute($key)
    {
        if ($key !== $this->params_field) {
            return parent::getAttribute($key);
        }

        if (is_null($this->paramsDecoded)) {
            $params = parent::getAttribute($key);
            $this->paramsDecoded = $params instanceof Collection ? $params : collect();
        }

        return $this->paramsDecoded;
    }

    public function setAttribute($key, $value)
    {
        $self = parent::setAttribute($key, $value);

        if ($key === $this->params_field) {
            $params = $this->getAttribute($this->params_field);
            $this->paramsDecoded = $params instanceof Collection ? $params : collect();
        }

        return $self;
    }
}