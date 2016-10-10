<?php

namespace App\Contracts;


interface ModuleInterface
{
    /**
     * @return string
     */
    public function getName();

    /**
     * @return string
     */
    public function __toString();

    /**
     * @param null|string $path
     * @return string
     */
    public function getPath($path = null);

    /**
     * @return bool
     */
    public function isEnabled();

    /**
     * @param string $key
     * @param null|mixed $default
     * @return mixed
     */
    public function meta($key, $default = null);
}