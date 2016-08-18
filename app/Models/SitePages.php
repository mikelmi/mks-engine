<?php
/**
 * Created by PhpStorm.
 * User: mike
 * Date: 16.08.16
 * Time: 13:51
 */

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
        $pages = Page::orderBy('title')->pluck('title', 'id')->toArray();
        $repository->set('pages', $pages);

        return parent::getModel($repository);
    }
}