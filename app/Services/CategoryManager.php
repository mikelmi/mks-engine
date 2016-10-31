<?php

namespace App\Services;


use App\Events\CategoryTypesCollect;
use Illuminate\Support\Collection;

class CategoryManager
{
    /**
     * @var Collection|null
     */
    private $types;

    /**
     * @return Collection
     */
    public function getTypes()
    {
        if (!isset($this->types)) {
            $this->types = new Collection();
            event(new CategoryTypesCollect($this->types));
        }

        return $this->types;
    }

    public function hasTypes()
    {
        return $this->getTypes()->count() > 0;
    }
}