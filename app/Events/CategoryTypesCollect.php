<?php

namespace App\Events;


use Illuminate\Support\Collection;

class CategoryTypesCollect extends Event
{
    /**
     * @var Collection
     */
    public $types;

    /**
     * CategorySectionsCollect constructor.
     * @param Collection $collection
     */
    public function __construct(Collection $collection)
    {
        $this->types = $collection;
    }
}