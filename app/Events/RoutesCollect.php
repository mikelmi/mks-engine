<?php
/**
 * Created by PhpStorm.
 * User: mike
 * Date: 16.08.16
 * Time: 12:55
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