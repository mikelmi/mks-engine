<?php
/**
 * Created by PhpStorm.
 * User: mike
 * Date: 16.08.16
 * Time: 12:55
 */

namespace App\Events;


use Illuminate\Support\Collection;

class RouteParamsCollect extends Event
{
    /**
     * @var Collection
     */
    public $items;

    /**
     * @var Collection
     */
    public $info;

    public function __construct(Collection $collection, Collection $info)
    {
        $this->items = $collection;
        $this->info = $info;
    }


}