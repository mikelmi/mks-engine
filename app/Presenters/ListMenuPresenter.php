<?php

namespace App\Presenters;


class ListMenuPresenter extends NavMenuPresenter
{
    public $class_ul = 'menu';
    public $class_li = 'menu-item';
    public $class_li_deep = 'submenu-item';
    public $class_li_current = 'active';
    public $class_a = 'menu-link';
    public $class_li_children = 'submenu-li';
    public $class_a_children = '';
    public $class_sub_ul = 'submenu';

    /**
     * @return string
     */
    public static function title()
    {
        return 'list (' . trans('a.Vertical') . ')';
    }
}