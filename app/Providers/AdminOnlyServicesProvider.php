<?php
/**
 * Author: mike
 * Date: 05.04.17
 * Time: 16:01
 */

namespace App\Providers;


use Illuminate\Support\ServiceProvider;

class AdminOnlyServicesProvider extends ServiceProvider
{
    protected $providers = [
        AdminEventServiceProvider::class,
        SettingsManagerServiceProvider::class,
        RouteManagerServiceProvider::class,
        CategoryManagerServiceProvider::class,
        AdminMenuServiceProvider::class,
        IconServiceProvider::class
    ];

    public function register()
    {
        foreach ($this->providers as $provider) {
            $this->app->register($provider);
        }
    }
}