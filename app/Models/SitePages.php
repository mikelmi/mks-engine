<?php

namespace App\Models;

use Illuminate\Config\Repository;

class SitePages extends SettingsScope
{
    public function __construct()
    {
        parent::__construct('page', trans('a.Pages'));

        $this->setFields(['home', '404', '503', '500']);
    }

    public function getModel(Repository $repository)
    {
        $pages = Page::ordered()->pluck('title', 'id')->toArray();
        $repository->set('pages', $pages);

        $params = $repository->get('home.params');

        if (!is_string($params)) {
            $repository->set('home.params', json_encode($params));
        }

        return parent::getModel($repository);
    }
}