<?php
/**
 * Created by PhpStorm.
 * User: mike
 * Date: 12.09.16
 * Time: 12:42
 */

namespace App\Events;


use Illuminate\Support\Collection;

class WidgetTypesCollect
{
    public $classes;

    public function __construct(Collection $collection)
    {
        $this->classes = $collection;
    }
}