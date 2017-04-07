<?php

namespace App\Services;

use App\Contracts\AdminMenuBuilder;
use Lavary\Menu\Builder;
use Lavary\Menu\Item;
use Mikelmi\MksAdmin\Contracts\MenuManagerContract;
use Illuminate\Contracts\Auth\Access\Gate;

class AdminMenu implements MenuManagerContract
{
    /**
     * @var array
     */
    private $configItems = [];

    /**
     * @var AdminMenuBuilder[];
     */
    private $builders = [];

    /**
     * AdminMenu constructor.
     * @param array $items
     * @param array $builders
     */
    public function __construct(array $items = [], array $builders = [])
    {
        $this->configItems = $items;

        foreach ($builders as $builder) {
            $this->addBuilder($builder);
        }
    }

    /**
     * @param AdminMenuBuilder $builder
     */
    public function addBuilder(AdminMenuBuilder $builder)
    {
        $this->builders[] = $builder;
    }

    /**
     * @return array
     */
    public function getItems()
    {
        $items = $this->configItems;

        /** @var Builder $menu */
        $menu = \Menu::make('adminMenu', function($menu) use ($items) {
            /** @var Builder $menu */
            $this->buildFromArray($menu, $items);
        });

        foreach ($this->builders as $builder) {
            $builder->build($menu);
        }

        return $this->menuToArray($menu);
    }

    private function menuToArray(Builder $menu, $parent = null) {
        $items = [];

        /** @var Item $item */
        foreach ($menu->whereParent($parent) as $item)
        {
            $data = $item->attributes;

            if (isset($data['href'])) {
                $data['url'] = $data['href'];
                unset($data['href']);
            } else {
                $data['url'] = $item->url();
            }

            $data['title'] = $item->title;

            if( $item->hasChildren() ) {
                $data['children'] = $this->menuToArray($menu, $item->id);
            }

            $items[] = $data;
        }

        return $items;
    }

    /**
     * @param Builder|Item $menu
     * @param array $items
     * @return Builder
     */
    private function buildFromArray($menu, array $items) {
        /** @var Gate $gate */
        $gate = resolve(Gate::class);

        foreach ($items as $item) {
            $can = $this->array_pull($item, 'can');

            if ($can && $gate->denies($can)) {
                if ($menu instanceof Item) {
                    $menu->data('has-cannot-children', true);
                }
                continue;
            }

            $title = $this->array_pull($item, 'title');
            $children = $this->array_pull($item, 'children');

            /** @var Item $menuItem */
            $menuItem = $menu->add(__($title), $item);

            if ($children) {
                $this->buildFromArray($menuItem, $children);
            }
        }

        if ($menu instanceof Builder) {
            return $menu->filter(function(Item $item){
                if ($item->data('has-cannot-children') && !$item->hasChildren()) {
                    return false;
                }
                return true;
            });
        }

        return $menu;
    }

    /**
     * @param array $items
     * @param $key
     * @param null $default
     * @return mixed|null
     */
    private function array_pull(array &$items, $key, $default = null)
    {
        $result = $items[$key] ?? $default;
        unset($items[$key]);

        return $result;
    }
}