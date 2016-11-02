<?php

namespace App\Http\ViewComposers;


use App\Repositories\Breadcrumbs;
use Illuminate\View\View;

class BreadcrumbComposer
{
    private $breadcrumbs;

    public function __construct(Breadcrumbs $breadcrumbs)
    {
        $this->breadcrumbs = $breadcrumbs;
    }

    public function compose(View $view)
    {
        $view->with([
            'items' => $this->breadcrumbs->all(),
            'empty' => $this->breadcrumbs->count() == 0
        ]);
    }
}