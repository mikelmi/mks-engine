<?php

namespace App\Services;


use Illuminate\Http\Request;
use League\Glide\Responses\LaravelResponseFactory;
use League\Glide\ServerFactory;

class ImageService
{
    /**
     * @var string
     */
    private $root;

    /**
     * @var
     */
    private $cachePath;

    /**
     * @var
     */
    private $server;

    /**
     * @var
     */
    private $config;

    /**
     * ImageService constructor.
     * @param string $root
     * @param array $config
     * @param $cachePath
     */
    public function __construct($root, $cachePath, array $config = [])
    {
        $this->root = $root;
        $this->cachePath = $cachePath;
        $this->config = $config;
    }

    /**
     * @param Request $request
     * @return \League\Glide\Server
     */
    public function server(Request $request)
    {
        if (!isset($this->server)) {
            $this->server = ServerFactory::create([
                'response' => new LaravelResponseFactory($request),
                'source' => $this->root,
                'cache' => $this->cachePath,
            ]);

            $defaults = array_get($this->config, 'defaults');
            if ($defaults) {
                $this->server->setDefaults($defaults);
            }

            $presets = array_get($this->config, 'presets');
            if ($presets) {
                $this->server->setPresets($presets);
            }
        }

        return $this->server;
    }

    /**
     * @param Request $request
     * @param string $path
     * @param array $params
     * @return mixed
     */
    public function response(Request $request, $path, array $params = [])
    {
        return $this->server($request)->getImageResponse($path, $params);
    }
}