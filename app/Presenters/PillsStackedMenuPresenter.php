<?php
/**
 * Created by PhpStorm.
 * User: mike
 * Date: 15.09.16
 * Time: 20:15
 */

namespace App\Presenters;


class PillsStackedMenuPresenter extends PillsMenuPresenter
{
    public $class_ul = 'nav nav-pills nav-stacked';

    /**
     * @return string
     */
    public static function title()
    {
        return 'pills-stacked (' . trans('a.Vertical') . ')';
    }
}