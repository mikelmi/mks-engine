<?php

namespace App\Presenters;



use Illuminate\Database\Eloquent\Collection;

interface MenuPresenterInterface
{
    /**
     * @param Collection $items
     * @param array $attrs
     * @return mixed
     */
    public function render(Collection $items, array $attrs = []);

    /**
     * @return string
     */
    public static function title();
}