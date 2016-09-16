<?php

namespace App\Presenters;


class PillsMenuPresenter extends NavMenuPresenter
{
    public $class_ul = 'nav nav-pills';

    /**
     * @return string
     */
    public static function title()
    {
        return 'pills (' . trans('a.Horizontal') . ')';
    }
}