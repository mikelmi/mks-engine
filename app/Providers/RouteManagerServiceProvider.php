<?php
/**
 * Author: mike
 * Date: 02.04.17
 * Time: 22:49
 */

namespace App\Providers;


use App\Services\GeneralRouteCollector;
use App\Services\RouteManager;
use App\ServiceTag;
use Illuminate\Support\ServiceProvider;

class RouteManagerServiceProvider extends ServiceProvider
{
    protected $defer = true;

    private $collectors = [
        GeneralRouteCollector::class
    ];

    public function register()
    {
        $this->app->singleton(RouteManager::class, function($app) {
            return new RouteManager($app['router'], $app->tagged(ServiceTag::ROUTES));
        });

        $this->app->tag($this->collectors, ServiceTag::ROUTES);
    }

    public function provides()
    {
        return [RouteManager::class];
    }
}