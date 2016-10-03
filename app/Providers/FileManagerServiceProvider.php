<?php

namespace App\Providers;


use App\Services\FileManager;
use App\Services\Image;
use Illuminate\Support\ServiceProvider;

class FileManagerServiceProvider extends ServiceProvider
{
    protected $defer = true;
    
    public function register()
    {
        $this->app->singleton(FileManager::class, function($app) {
            return new FileManager($app['filesystem']->disk('files'), url('files'), 'thumbnail');
        });

        $this->app->singleton(Image::class, function($app) {
            return new Image($app[FileManager::class], storage_path('app/public/images'), $app['config']->get('image', []));
        });
    }
    
    public function boot()
    {
        
    }
    
    public function provides()
    {
        return [
            FileManager::class,
            Image::class
        ];
    }
}
