<?php

namespace App\Presenters;


class TabsMenuPresenter extends NavMenuPresenter
{
    public $class_ul = 'nav nav-tabs';

    /**
     * @return string
     */
    public static function title()
    {
        return 'tabs (' . trans('a.Horizontal') . ')';
    }
}