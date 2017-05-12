<?php

namespace App\Models;


use App\Contracts\NestedMenuInterface;
use App\Traits\HasHtmlAttributes;
use App\Traits\Parametrized;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
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
 * @property string $icon
 * @property Menu $menu
 * @property int depth
 * @property MenuItem[] $children
 *
 * @method MenuItem ofMenu($menu)
 * @method MenuItem defaultOrder()
 * @method MenuItem widthDepth()
 */
class MenuItem extends Model implements NestedMenuInterface
{
    use NodeTrait,
        Parametrized,
        HasHtmlAttributes;

    public $timestamps = false;

    private $finalizedUrl;

    protected $casts = [
        'attr' => 'array',
    ];

    protected $appends = [
        'finalUrl'
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function menu()
    {
        return $this->belongsTo(Menu::class);
    }

    /**
     * @return array
     */
    protected function getScopeAttributes()
    {
        return [ 'menu_id' ];
    }

    /**
     * Check if item has children
     *
     * @return bool
     */
    public function hasChildren()
    {
        return $this->children->count() > 0;
    }

    /**
     * @return bool|\Illuminate\Contracts\Routing\UrlGenerator|string
     */
    public function getUrl()
    {
        if (!isset($this->finalizedUrl)) {
            $url = $this->url;

            if ($this->route) {
                $params = $this->params->all();
                try {
                    $url = route($this->route, $params);
                } catch (\Exception $e) {
                    /*if (config('app.debug')) {
                        throw $e;
                    }*/
                    $url = false;
                }
            } elseif ($url && !starts_with($url, ['#', 'http:', 'https:', 'mailto:', 'javascript:'])) {
                $url = url($url);
            }

            $this->finalizedUrl = $url;
        }

        return $this->finalizedUrl;
    }

    /**
     * Check if item is current
     *
     * @param Route|null $route
     * @return bool
     */
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

    /**
     * @param Builder $query
     * @param Menu|int $menu
     * @return MenuItem
     */
    public function scopeOfMenu(Builder $query, $menu)
    {
        $menuId = $menu instanceof Menu ? $menu->id : $menu;

        return self::scoped(['menu_id' => $menuId]);
    }

    /**
     * @param $menu
     * @param mixed $root
     * @return Collection
     */
    public static function getTree($menu, $root = null)
    {
        return self::ofMenu($menu)->defaultOrder()->withDepth()->get()->toTree($root);
    }

    /**
     * @param $menu
     * @param mixed $root
     * @return Collection
     */
    public static function getFlatTree($menu, $root = null)
    {
        return self::ofMenu($menu)->defaultOrder()->withDepth()->get()->toFlatTree($root);
    }

    /**
     * @return int
     */
    public function getDepth()
    {
        return $this->depth;
    }

    /**
     * @return array|\Illuminate\Support\Collection
     */
    public function getChildren()
    {
        return $this->children;
    }

    /**
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @return string
     */
    public function getIcon()
    {
        return $this->icon;
    }

    public function getFinalUrlAttribute()
    {
        return $this->getUrl();
    }
}