<?php

namespace App\Services;


use App\Events\RouteParamsCollect;
use App\Events\RoutesCollect;
use Illuminate\Routing\Route;
use Illuminate\Routing\Router;

class RouteConfigService
{

    private $router;

    public function __construct(Router $router)
    {
        $this->router = $router;
    }

    public function collect()
    {
        $routes = $this->router->getRoutes();

        $data = collect();

        $admin_prefix = admin_prefix();

        /** @var Route $route */
        foreach($routes as $route)
        {
            if (!$route->getName() || starts_with($route->getUri(), $admin_prefix) || starts_with($route->getUri(), '_')) {
                continue;
            }

            if (!in_array('GET', $route->methods())) {
                continue;
            }

            $paramNames = $route->parameterNames();

            $data->put($route->getName(), [
                'id' => $route->getName(),
                'text' => $route->getUri(),
                'params' => $paramNames,
                'hasParams' => count($paramNames) > 0
            ]);
        }

        event(new RoutesCollect($data));

        return $data;
    }

    /**
     * @param string $routeName
     * @return \Illuminate\Support\Collection
     */
    public function collectParams($routeName)
    {
        $route = $this->router->getRoutes()->getByName($routeName);

        if (!$route) {
            abort(404, 'Route "'.$routeName.'" not found');
        }

        $data = collect([
            'name' => $routeName,
            'uri' => $route->uri(),
            'title' => $routeName,
            'params' => $route->parameterNames(),
            'items' => [],
        ]);

        event('route-params.'.$routeName, [$data]);

        return $data;
    }
}