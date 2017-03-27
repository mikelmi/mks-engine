<?php

namespace App\Widgets;


class SearchWidget extends WidgetPresenter
{

    /**
     * @return string
     */
    public function title(): string
    {
        return __('general.SearchWidget');
    }

    /**
     * @return string
     */
    public function alias(): string
    {
        return 'search';
    }
}