<?php
/**
 * Created by PhpStorm.
 * User: mike
 * Date: 15.09.16
 * Time: 20:15
 */

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