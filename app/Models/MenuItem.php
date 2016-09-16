<?php

namespace App\Models;


use App\Traits\Parametrized;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Routing\Route;
use Kalnoy\Nestedset\NodeTrait;

/**
 * Class MenuItem
 * @package App\Models
 *
 * @property int $id
 * @property string $title
 * @property string $route
 * @property string $url
 * @property string $target
 * @property Menu $menu
 */
class MenuItem extends Model
{
    use NodeTrait, Parametrized;

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function menu()
    {
        return $this->belongsTo(Menu::class);
    }

    protected function getScopeAttributes()
    {
        return [ 'menu_id' ];
    }

    public function hasChildren()
    {
        return $this->children->count() > 0;
    }

    public function getUrl()
    {
        $url = $this->url;

        if ($this->route) {
            $params = $this->params->all();
            try {
                $url = route($this->route, $params);
            } catch (\InvalidArgumentException $e) {
                if (config('app.debug')) {
                    throw $e;
                }
                return false;
            }
        } elseif($url && !starts_with($url, ['#', 'http:', 'https:', 'mailto:', 'javascript:'])) {
            return url($url);
        }

        return $url;
    }

    public function isCurrent(Route $route = null)
    {
        if (!$route) {
            $route = \Route::current();
        }

        $routeName = $this->route;

        if ($routeName) {
            if ($route->getName() == $routeName) {
                $params = $this->params->all();
                return !$params || $params == $route->parameters();
            }
        } elseif (is_string($this->url) && !starts_with($this->url, '#')) {
            return \Request::fullUrlIs(url($this->url));
        }

        return false;
    }
}