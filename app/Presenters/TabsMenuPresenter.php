<?php

namespace App\Presenters;


class TabsMenuPresenter extends NavMenuPresenter
{
    /**
     * @return string
     */
    public static function title()
    {
        return 'tabs (' . trans('general.Horizontal') . ')';
    }

    public static function options()
    {
        return [
            'class_ul' => 'nav nav-tabs',
        ];
    }
}