<?php

namespace App\Providers;


use App\Contracts\ModuleRepositoryInterface;
use App\Repositories\ModuleRepository;
use Illuminate\Routing\Router;
use Illuminate\Support\ServiceProvider;

class ModulesLoaderServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->singleton(ModuleRepository::class, function ($app) {
            return new ModuleRepository(
                $app['cache.store'],
                $app['config']->get('modules.paths.modules'),
                $app['config']->get('modules.cache.lifetime'),
                $app['config']->get('modules.cache.key')
            );
        });

        $this->app->alias(ModuleRepository::class, 'modules');
    }

    public function boot()
    {
        /** @var ModuleRepository $repository */
        $repository = $this->app['modules'];

        /** @var Router $router */
        $router = $this->app['router'];

        $isRouteCached = $this->app->routesAreCached();

        foreach ($repository->ordered() as $name => $module) {

            foreach ($module->meta('configs', []) as $scope => $config) {
                $this->mergeConfigFrom($module->getPath($config), $scope);
            }

            //attach middleware resolvers
            foreach ($module->meta('middleware', []) as $aliasName => $aliasClass) {
                $router->middleware($aliasName, $aliasClass);
            }

            //register providers
            foreach ($module->meta('providers', []) as $provider) {
                $this->app->register($provider);
            }

            //register files
            foreach ($module->meta('files', []) as $file) {
                require $module->getPath($file);
            }

            //register routes
            if (!$isRouteCached) {
                if ($routes = $module->meta('routes')) {
                    require $module->getPath($routes);
                }
            }

            //event listeners
            foreach ($module->meta('listeners', []) as $event => $listener) {
                \Event::listen($event, $listener);
            }

            //register views
            $this->loadViewsFrom($module->getPath('resources/views'), lcfirst($name));

            //load translations
            $this->loadTranslationsFrom($module->getPath('resources/lang'), lcfirst($name));
        }

        //should be at the end of all routes
        $router->get('{path?}', ['as' => 'page', 'uses' => 'App\Http\Controllers\PageController@getByPath'])->where('path', '[A-Za-z0-0-_]+');
    }
}