<?php

namespace App\Presenters;


class PillsStackedMenuPresenter extends PillsMenuPresenter
{
    public $class_ul = 'nav nav-pills nav-stacked';

    /**
     * @return string
     */
    public static function title()
    {
        return 'pills-stacked (' . trans('a.Vertical') . ')';
    }
}