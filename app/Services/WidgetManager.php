<?php

namespace App\Services;


use App\Events\WidgetTypesCollect;
use App\Models\Widget;
use App\User;
use App\Widgets\WidgetInterface;
use Illuminate\Routing\Route;
use Illuminate\Support\Collection;

class WidgetManager
{
    /**
     * @var Collection
     */
    private $types;

    /**
     * @var Collection
     */
    private $loaded;

    /**
     * @return Collection
     */
    public function getTypes()
    {
        if (!($this->types instanceof Collection)) {

            $this->types = new Collection();

            $dir = app_path('Widgets');

            foreach (glob($dir . '/*.php') as $file) {
                $className = '\App\Widgets\\' . basename($file, '.php');
                $class = new \ReflectionClass($className);

                if (!$class->isInterface() && !$class->isAbstract()
                    && $class->implementsInterface(WidgetInterface::class)
                ) {
                    $this->types->put($class->getName(), $className::title());
                }
            }

            event(new WidgetTypesCollect($this->types));
        }

        return $this->types;
    }

    /**
     * @param string $class
     * @return WidgetInterface
     * @throws \Exception
     */
    public static function make($class)
    {
        $class = str_replace('/', '\\', $class);

        if (!class_exists($class)) {
            throw new \Exception('Class \'' . $class. '\' not found');
        }

        /** @var WidgetInterface $widget */
        $widget = new $class();

        if (!($widget instanceof WidgetInterface)) {
            throw new \Exception('Class \'' . $class. '\' does not implement widget interface');
        }

        return $widget;
    }

    public function title($type, $default = null)
    {
        return $this->getTypes()->get($type, $default);
    }

    protected function load()
    {
        if ($this->loaded) {
            return $this->loaded;
        }

        $this->loaded = collect();

        $all = Widget::with(['roles', 'routes'])->where('status', true)->orderBy('ordering')->get();

        /** @var User|null $user */
        $user = auth()->user();

        $route = \Route::current();

        $locale = app()->getLocale();

        /** @var Widget $widget */
        foreach ($all as $widget) {

            // filter by lang
            if (!$this->checkForLocale($widget, $locale)) {
                continue;
            }

            // filter by roles
            if (!$this->checkForRoles($widget, $user)) {
                continue;
            }

            //filter by route
            if (!$this->checkForRoutes($widget, $route)) {
                continue;
            }

            $position = $widget->position ? $widget->position : '-';
            if (!$this->loaded->has($position)) {
                $this->loaded->put($position, collect([$widget]));
            } else {
                $this->loaded[$position]->push($widget);
            }
        }

        return $this->loaded;
    }

    protected function checkForLocale(Widget $widget, $locale)
    {
        return !$locale || !$widget->lang || $locale == $widget->lang;
    }

    protected function checkForRoles(Widget $widget, User $user = null)
    {
        $rolesShow = $widget->param('roles');

        if ($rolesShow) {
            if (!$user) {
                return false;
            }

            $hasRoles = $user->hasRole($widget->roles->pluck('name')->all());
            if ($rolesShow == '-1') {
                if ($hasRoles) {
                    return false;
                }
            } else if($rolesShow == '2' && !$hasRoles) {
                return false;
            }
        }

        return true;
    }

    protected function checkForRoutes(Widget $widget, Route $route)
    {
        $routeShow = $widget->param('showing');
        if ($routeShow) {
            $routes = $widget->routes->pluck('params', 'route')->toArray();
            $hasRoute = false;
            foreach ($routes as $name => $params) {
                if ($route->getName() == $name && (!$params || $route->parameters() == $params)) {
                    $hasRoute = true;
                }
            }

            return $routeShow == '1' ? $hasRoute : !$hasRoute;
        }

        return true;
    }

    /**
     * @param $name
     * @return Widget|null
     */
    protected function getByName($name)
    {
        $this->load();

        foreach ($this->loaded as $position => $items) {
            foreach ($items as $item) {
                if ($name == $item->name) {
                    return $item;
                }
            }
        }

        return null;
    }

    public function render($position) {
        $this->load();
        $items = $this->loaded->get($position);

        $content = '';

        if ($items) {
            foreach ($items as $item) {
                try {
                    $widget = self::make($item->class);
                    $widget->setModel($item);
                    $content .= $widget->render() . "\n";
                } catch(\Exception $e) {
                    if (config('app.debug')) {
                        throw $e;
                    }
                    \Log::error($e->getMessage(), [$e]);
                    continue;
                }
            }
        }

        return $content;
    }

    public function renderOne($name)
    {
        $model = $this->getByName($name);

        if (!$model) {
            return null;
        }

        try {
            $widget = self::make($model->class);
            $widget->setModel($model);
            return $widget->render();
        } catch(\Exception $e) {
            if (config('app.debug')) {
                throw $e;
            }
            \Log::error($e->getMessage(), [$e]);
        }

    }
}