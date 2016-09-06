<?php

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