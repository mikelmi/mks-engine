<?php

namespace App\Repositories;


class Breadcrumbs
{
    private $items = [];

    public function add($title, $url = null, $icon = null)
    {
        $this->items[] = compact('title', 'url', 'icon');
    }

    public function all()
    {
        return $this->items;
    }

    public function count()
    {
        return count($this->items);
    }

    public function last()
    {
        return array_last($this->items);
    }
}