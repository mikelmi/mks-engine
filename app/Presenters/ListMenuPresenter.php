<?php

namespace App\Presenters;


class ListMenuPresenter extends NavMenuPresenter
{
    /**
     * @return string
     */
    public static function title()
    {
        return 'list (' . trans('a.Vertical') . ')';
    }

    public static function options()
    {
        return array(
            'class_ul' => 'menu', // class for <ul>
            'class_li' => 'menu-item', //class for ul->li
            'class_li_deep' => 'submenu-item', //class for li->ul->li
            'class_current' => 'active', //class for current menu item
            'class_a' => 'menu-link', //classfor li->a
            'class_li_children' => 'submenu-li', //class for <li> which has children
            'class_a_children' => '', //class for <a> which has children
            'class_sub_ul' => 'submenu', //class for li->ul
        );
    }
}