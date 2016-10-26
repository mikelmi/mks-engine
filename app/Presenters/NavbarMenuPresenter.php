<?php

namespace App\Presenters;


class NavbarMenuPresenter extends NavMenuPresenter
{
    /**
     * @return string
     */
    public static function title()
    {
        return 'navbar (' . trans('general.Horizontal') . ')';
    }

    public static function options()
    {
        return [
            'class_ul' => 'nav navbar-nav',
        ];
    }
}