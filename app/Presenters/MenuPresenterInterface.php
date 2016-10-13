<?php

namespace App\Presenters;



use Illuminate\Support\Collection;

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

    /**
     * @return array
     */
    public static function options();

    /**
     * @param $name
     * @param null $default
     * @return mixed
     */
    public function option($name, $default = null);
}