<?php
/**
 * Created by PhpStorm.
 * User: mike
 * Date: 15.09.16
 * Time: 20:15
 */

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