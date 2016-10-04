<?php

namespace App\Services;


use League\Flysystem\Adapter\Local;

class FileManagerAdapter extends Local
{
    /**
     * @var string
     */
    private $urlPrefix;

    /**
     * @param string $prefix
     */
    public function setUrlPrefix($prefix)
    {
        $this->urlPrefix = rtrim($prefix, '/');
    }

    /**
     * @param string $path
     * @return string
     */
    public function getUrl($path)
    {
        return url($this->getRelativeUrl($path));
    }

    /**
     * @param string $path
     * @return string
     */
    public function getRelativeUrl($path)
    {
        $url = str_replace('\\', '/', $path);

        if ($this->urlPrefix) {
            return $this->urlPrefix . '/'. ltrim($url, '/');
        }

        return $url;
    }
}