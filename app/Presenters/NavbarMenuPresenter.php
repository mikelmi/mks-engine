<?php

namespace App\Presenters;


class NavbarMenuPresenter extends NavMenuPresenter
{
    public $class_ul = 'nav navbar-nav';

    /**
     * @return string
     */
    public static function title()
    {
        return 'navbar (' . trans('a.Horizontal') . ')';
    }
}