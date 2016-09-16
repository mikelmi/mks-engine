<?php
/**
 * Event fired on collecting widget types (for choosing on Admin Panel -> Widgets -> Add)
 */

namespace App\Events;


use Illuminate\Support\Collection;

class WidgetTypesCollect
{
    /**
     * @var Collection
     */
    public $classes;

    /**
     * WidgetTypesCollect constructor.
     * @param Collection $collection
     */
    public function __construct(Collection $collection)
    {
        $this->classes = $collection;
    }
}