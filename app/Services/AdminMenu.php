<?php
/**
 * Created by PhpStorm.
 * User: mike
 * Date: 09.08.16
 * Time: 14:36
 */

namespace App\Services;

use App\Events\AdminMenuBuild;
use Lavary\Menu\Builder;
use Lavary\Menu\Item;
use Mikelmi\MksAdmin\Contracts\MenuManagerContract;

class AdminMenu implements MenuManagerContract
{
    private $configItems = [];

    public function __construct(array $items = [])
    {
        $this->configItems = $items;
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

        event(new AdminMenuBuild($menu));

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

    private function buildFromArray($menu, array $items) {
        foreach ($items as $item) {
            $title = $item['title'];
            unset($item['title']);

            $menuItem = $menu->add(trans($title), $item);

            if (isset($item['children']) && $item['children']) {
                $this->buildFromArray($menuItem, $item['children']);
            }
        }

        return $menu;
    }
}