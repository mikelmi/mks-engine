<?php

namespace App\Presenters;

class DropdownNavbarLanguagesPresenter extends DropdownLanguagesPresenter
{

    public static function title()
    {
        return 'dropdown navbar';
    }

    public static function options()
    {
        return [
            'class_ul' => 'nav navbar-nav'
        ];
    }
}