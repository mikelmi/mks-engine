<?php
/**
 * Event fired on collecting settings scopes (Settings pages in Admin Panel)
 */

namespace App\Events;


use Illuminate\Support\Collection;

class SettingsScopesCollect extends Event
{
    /**
     * @var Collection
     */
    public $scopes;

    /**
     * SettingsScopesCollect constructor.
     * @param Collection $collection
     */
    public function __construct(Collection $collection)
    {
        $this->scopes = $collection;
    }


}