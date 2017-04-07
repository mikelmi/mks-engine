<?php

namespace App\Providers;


use App\Services\FileManager;
use App\Services\FileManagerAdapter;
use App\Services\ImageService;
use Illuminate\Contracts\Auth\Access\Gate;
use Illuminate\Support\ServiceProvider;
use League\Flysystem\Adapter\Local;
use League\Flysystem\Filesystem;

class FileManagerServiceProvider extends ServiceProvider
{
    protected $defer = true;
    
    public function register()
    {
        $this->app->singleton(FileManager::class, function($app) {
            return new FileManager($app['filesystem']->disk('files'), 'thumbnail');
        });

        $this->app->singleton(ImageService::class, function($app) {
            return new ImageService(
                $app['image'],
                dirname($app['config']->get('filesystems.disks.files.root')),
                storage_path('app/public/images'), $app['config']->get('image', [])
            );
        });
    }
    
    public function boot()
    {
        \Storage::extend('filemanager', function($app, $config) {
            $root = $config['root'];
            $url_prefix = $config['url_prefix'];

            $userId = $app['auth']->id();

            if (!$userId) {
                abort(403);
            }

            /** @var Gate $gate */
            $gate = $app[Gate::class];

            if (!$gate->allows('files.all')) {
                $prefix = DIRECTORY_SEPARATOR . 'users'. DIRECTORY_SEPARATOR . $userId;
                $root .= $prefix;
                $url_prefix .= str_replace_first('\\', '/', $prefix);
            }
            
            $adapter = new FileManagerAdapter($root);
            $adapter->setUrlPrefix($url_prefix);

            return new Filesystem($adapter);
        });
    }
    
    public function provides()
    {
        return [
            FileManager::class,
            ImageService::class
        ];
    }
}
