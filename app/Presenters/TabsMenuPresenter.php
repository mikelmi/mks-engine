<?php

namespace App\Presenters;


class TabsMenuPresenter extends NavMenuPresenter
{
    protected $maxDepth = 1;
    
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