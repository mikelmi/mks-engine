<?php

namespace App\Providers;

use App\Services\RouteConfigService;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Route;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * This namespace is applied to your controller routes.
     *
     * In addition, it is set as the URL generator's root namespace.
     *
     * @var string
     */
    protected $namespace = 'App\Http\Controllers';

    /**
     * Define your route model bindings, pattern filters, etc.
     *
     * @return void
     */
    public function boot()
    {
        Route::pattern('id', '[0-9]+');

        parent::boot();
    }

    /**
     * Define the routes for the application.
     *
     * @return void
     */
    public function map()
    {
        $this->mapWebRoutes();

        $this->mapApiRoutes();

        //
    }

    /**
     * Define the "web" routes for the application.
     *
     * These routes all receive session state, CSRF protection, etc.
     *
     * @return void
     */
    protected function mapWebRoutes()
    {
        //admin routes
        Route::group([
            'middleware' => 'web',
            'prefix' => config('admin.url', 'admin'),
            'namespace' => $this->namespace . '\Admin',
            'as' => 'admin::'
        ], function ($router) {
            require base_path('routes/admin.php');
        });

        //frontend routes
        Route::group([
            'middleware' => ['web', 'frontend'],
            'namespace' => $this->namespace,
        ], function ($router) {
            require base_path('routes/frontend.php');
        });
    }

    /**
     * Define the "api" routes for the application.
     *
     * These routes are typically stateless.
     *
     * @return void
     */
    protected function mapApiRoutes()
    {
        Route::group([
            'middleware' => 'api',
            'namespace' => $this->namespace,
            'prefix' => 'api',
        ], function ($router) {
            require base_path('routes/api.php');
        });
    }

    public function register()
    {
        parent::register();

        $this->app->singleton(RouteConfigService::class, function($app) {
            return new RouteConfigService($app['router']);
        });
    }
}
