<?php

namespace App\Presenters;


class PillsMenuPresenter extends NavMenuPresenter
{
    /**
     * @return string
     */
    public static function title()
    {
        return 'pills (' . trans('a.Horizontal') . ')';
    }

    public static function options()
    {
        return [
            'class_ul' => 'nav nav-pills',
        ];
    }
}