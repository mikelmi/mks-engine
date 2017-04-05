<?php
/**
 * Author: mike
 * Date: 05.04.17
 * Time: 15:51
 */

namespace App\Providers;


use App\Repositories\IconRepository;
use Illuminate\Support\ServiceProvider;

class IconServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->singleton(IconRepository::class, function($app) {
            return new IconRepository(
                $app['filesystem.disk'],
                $app['config']->get('services.icons.remoteUrl')
            );
        });
    }

    public function provides()
    {
        return [
            IconRepository::class
        ];
    }
}