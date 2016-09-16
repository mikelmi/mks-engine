<?php
/**
 * Event fired on collecting application routes (for select in Admin panel)
 */

namespace App\Events;


use Illuminate\Support\Collection;

class RoutesCollect extends Event
{
    /**
     * @var Collection
     */
    public $routes;

    public function __construct(Collection $collection)
    {
        $this->routes = $collection;
    }


}