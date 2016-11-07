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
     * @param $noEmpty
     * @return Collection|mixed
     */
    public function param($key = null, $default = null, $noEmpty = false)
    {
        $params = $this->getAttribute($this->params_field);

        if ($key === null) {
            return $params;
        }

        $result = $params->get($key, $default);

        return $noEmpty && ($result === "" || $result === null) ? $default : $result;
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
            $this->paramsDecoded = $params instanceof Collection ? $params : collect($params);
        }

        return $this->paramsDecoded;
    }

    public function setAttribute($key, $value)
    {
        if ($key === $this->params_field) {
            if (is_string($value)) {
                $value = $this->fromJson($value);
            }

            $self = parent::setAttribute($key, $value);

            $params = $this->getAttribute($this->params_field);
            $this->paramsDecoded = $params instanceof Collection ? $params : collect($params);

            return $self;
        }

        return parent::setAttribute($key, $value);
    }
}