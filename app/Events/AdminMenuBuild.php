<?php

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