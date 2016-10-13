<?php

namespace App\Presenters;


class PillsStackedMenuPresenter extends PillsMenuPresenter
{
    /**
     * @return string
     */
    public static function title()
    {
        return 'pills-stacked (' . trans('a.Vertical') . ')';
    }

    public static function options()
    {
        return [
            'class_ul' => 'nav nav-pills nav-stacked',
        ];
    }
}