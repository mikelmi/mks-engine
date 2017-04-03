<?php
/**
 * Author: mike
 * Date: 02.04.17
 * Time: 22:27
 */

namespace App\Services;


use App\Contracts\RouteCollector;
use Illuminate\Routing\Route;
use Illuminate\Routing\Router;
use Illuminate\Support\Collection;

class RouteManager
{
    /**
     * @var Router
     */
    private $router;

    /**
     * @var RouteCollector[]
     */
    private $collectors;

    /** @var Collection */
    private $all;

    /**
     * RouteManager constructor.
     * @param Router $router
     * @param array $collectors
     */
    public function __construct(Router $router, array $collectors = [])
    {
        $this->router = $router;
        $this->collectors = $collectors;
    }

    /**
     * @return Collection
     */
    public function all()
    {
        if (isset($this->all)) {
            return $this->all;
        }

        $routes = $this->router->getRoutes();

        $this->all = collect();

        $admin_prefix = admin_prefix();

        /** @var Route $route */
        foreach($routes as $route)
        {
            if (!$route->getName()) {
                continue;
            }

            if (starts_with($route->uri(), $admin_prefix) || starts_with($route->uri(), '_')) {
                continue;
            }

            if (!in_array('GET', $route->methods())) {
                continue;
            }

            $paramNames = $route->parameterNames();

            $this->all->put($route->getName(), [
                'id' => $route->getName(),
                'text' => $route->uri(),
                'params' => $paramNames,
                'hasParams' => count($paramNames) > 0
            ]);
        }

        return $this->all;
    }

    /**
     * @return Collection
     */
    public function links()
    {
        /** @var RouteCollector $collector */
        $mappers = array_map(function($collector) {
            return $collector->map();
        }, $this->collectors);

        //TODO: return mapped routes
        $mappers = call_user_func_array('array_merge', $mappers);

        $res =  $this->all();

        foreach($mappers as $name => $opt) {
            $options = is_array($opt) ? $opt : ['text' => $opt];
            $options['linked'] = true;
            $res[$name] = array_merge($res[$name], $options);
        }

        return $res->filter(function($item) {
            return isset($item['linked']);
        })->sortByDesc(function($item) {
            return array_get($item, 'priority', 0);
        });
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