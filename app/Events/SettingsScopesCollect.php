<?php

namespace App\Events;


use Illuminate\Support\Collection;

class SettingsScopesCollect extends Event
{
    /**
     * @var Collection
     */
    public $scopes;

    public function __construct(Collection $collection)
    {
        $this->scopes = $collection;
    }


}