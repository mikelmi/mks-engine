<?php

namespace App\Services;


use App\Contracts\WidgetPresenter;
use App\Exceptions\WidgetPresenterNotFound;
use App\Models\Widget;
use App\User;
use Illuminate\Routing\Route;
use Illuminate\Support\Collection;

class WidgetManager
{
    /**
     * @var WidgetPresenter[]
     */
    private $presenters;

    /**
     * @var Collection
     */
    private $loaded;

    /**
     * WidgetManager constructor.
     * @param array $presenters
     */
    public function __construct(array $presenters)
    {
        $this->setPresenters($presenters);
    }

    public function setPresenters(array $presenters)
    {
        /** @var WidgetPresenter $presenter */
        foreach ($presenters as $presenter) {
            $this->presenters[$presenter->alias()] = $presenter;
        }
    }

    /**
     * @return WidgetPresenter[]
     */
    public function getPresenters(): array
    {
        return $this->presenters;
    }

    /**
     * @return array
     */
    public function getPresentersList(): array
    {
        $result = [];

        foreach ($this->presenters as $presenter) {
            $result[$presenter->alias()] = $presenter->title();
        }

        return $result;
    }

    /**
     * @param string $alias
     * @return WidgetPresenter
     * @throws WidgetPresenterNotFound
     */
    public function presenter(string $alias): WidgetPresenter
    {
        if (!$this->exists($alias)) {
            throw new WidgetPresenterNotFound($alias);
        }

        return $this->presenters[$alias];
    }

    /**
     * @param string $alias
     * @return bool
     */
    public function exists(string $alias): bool
    {
        return array_key_exists($alias, $this->presenters);
    }

    /**
     * @param string $alias
     * @param string|null $default
     * @return null|string
     */
    public function title(string $alias, string $default = null)
    {
        return $this->exists($alias) ? $this->presenters[$alias]->title() : $default;
    }

    protected function load()
    {
        if ($this->loaded) {
            return $this->loaded;
        }

        $this->loaded = collect();

        $all = Widget::with(['roles', 'routes'])->active()->ordered()->get();

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
            if ($route && !$this->checkForRoutes($widget, $route)) {
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

    public function render($position, $count = false) {
        $this->load();
        /** @var Collection $items */
        $items = $this->loaded->get($position);

        $content = '';

        if ($items) {
            if ($count) {
                $items = $items->random($count);
            }

            foreach ($items as $item) {
                try {
                    $presenter = self::presenter($item->class);
                    $presenter->setModel($item);
                    $content .= $presenter->render() . "\n";
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
            $presenter = self::presenter($model->class);
            $presenter->setModel($model);
            return $presenter->render();
        } catch(\Exception $e) {
            if (config('app.debug')) {
                throw $e;
            }
            \Log::error($e->getMessage(), [$e]);
        }

    }
}