<?php

namespace App\Services;


use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Intervention\Image\ImageManager;
use League\Glide\Responses\LaravelResponseFactory;
use League\Glide\ServerFactory;

class ImageService
{
    /**
     * @var string
     */
    private $root;

    /**
     * @var string
     */
    private $cachePath;

    /**
     * @var \League\Glide\Server
     */
    private $server;

    /**
     * @var array
     */
    private $config;

    /**
     * @var ImageManager
     */
    private $imageManager;

    /**
     * ImageService constructor.
     * @param ImageManager $imageManager
     * @param string $root
     * @param string $cachePath
     * @param array $config
     */
    public function __construct(ImageManager $imageManager, $root, $cachePath, array $config = [])
    {
        $this->imageManager = $imageManager;
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
                'watermarks' => $this->root
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

    /**
     * @param Request $request
     * @param $path
     * @param null $default
     * @param int $width
     * @param int $height
     * @param string $background
     * @return Response
     */
    public function assetProxy(Request $request, $path, $default = null, $width = 10, $height = 10, $background = '#ffffff')
    {
        $file = new \SplFileInfo(public_path($path));

        if ($default && !$file->isFile()) {
            $file = new \SplFileInfo(public_path($default));
        }
        
        if ($file->isFile()) {
            $img = $this->imageManager->make($path);
        } else {
            $img = $this->imageManager->canvas($width, $height, $background);
        }
        
        /** @var Response $response */
        $response = $img->response();
        $response->setPublic();
        $response->setMaxAge(31536000);
        $response->setExpires(date_create()->modify('+1 years'));

        try {
            if ($mtime = $file->getMTime()) {
                $response->setLastModified(date_create()->setTimestamp($mtime));
                $response->isNotModified($request);
            }
        } catch (\RuntimeException $e) {
            
        }

        return $response;
    }
}