<?php

namespace App\Presenters;


class NavInlineMenuPresenter extends NavMenuPresenter
{
    /**
     * @return string
     */
    public static function title()
    {
        return 'nav-inline (' . trans('a.Horizontal') . ')';
    }

    public static function options()
    {
        return [
            'class_ul' => 'nav nav-inline',
        ];
    }
}