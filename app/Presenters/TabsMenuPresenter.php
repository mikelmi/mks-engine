<?php
/**
 * Created by PhpStorm.
 * User: mike
 * Date: 15.09.16
 * Time: 20:15
 */

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