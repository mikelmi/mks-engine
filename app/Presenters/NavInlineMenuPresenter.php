<?php
/**
 * Created by PhpStorm.
 * User: mike
 * Date: 15.09.16
 * Time: 20:15
 */

namespace App\Presenters;


class NavInlineMenuPresenter extends NavMenuPresenter
{
    public $class_ul = 'nav nav-inline';

    /**
     * @return string
     */
    public static function title()
    {
        return 'nav-inline (' . trans('a.Horizontal') . ')';
    }
}