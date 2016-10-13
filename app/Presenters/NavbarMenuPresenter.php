<?php

namespace App\Presenters;


class NavbarMenuPresenter extends NavMenuPresenter
{
    /**
     * @return string
     */
    public static function title()
    {
        return 'navbar (' . trans('a.Horizontal') . ')';
    }

    public static function options()
    {
        return [
            'class_ul' => 'nav navbar-nav',
        ];
    }
}