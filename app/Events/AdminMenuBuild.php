<?php
/**
 * Created by PhpStorm.
 * User: mike
 * Date: 16.08.16
 * Time: 13:27
 */

namespace App\Events;


use Lavary\Menu\Builder;

class AdminMenuBuild extends Event
{
    /**
     * @var Builder
     */
    public $menu;

    public function __construct(Builder $menu)
    {
        $this->menu = $menu;
    }
}