<?php
/**
 * Event fired on collecting route's parameters (for select in Admin panel)
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

    /**
     * RouteParamsCollect constructor.
     * @param Collection $collection
     * @param Collection $info
     */
    public function __construct(Collection $collection, Collection $info)
    {
        $this->items = $collection;
        $this->info = $info;
    }


}