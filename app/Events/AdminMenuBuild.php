<?php
/**
 * Event fired on Admin menu builds
 */

namespace App\Events;


use Lavary\Menu\Builder;

class AdminMenuBuild extends Event
{
    /**
     * @var Builder
     */
    public $menu;

    /**
     * AdminMenuBuild constructor.
     * @param Builder $menu
     */
    public function __construct(Builder $menu)
    {
        $this->menu = $menu;
    }
}