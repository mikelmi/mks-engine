<?php

namespace App\Models;


use Illuminate\Config\Repository;

class SiteSettings extends SettingsScope
{
    public function __construct()
    {
        parent::__construct('site', trans('a.Site'));

        $this->setFields(['title', 'description', 'keywords', 'off', 'off_message']);
    }

    public function afterSave(Repository $old, Repository $new)
    {
        $isDown = app()->isDownForMaintenance();
        if ($isDown && !$new->get('off')) {
            \Artisan::call('up');
        } elseif (!$isDown && $new->get('off')) {
            \Artisan::call('down');
        }
    }

    public function getModel(Repository $repository)
    {
        $repository->set('off', (int) app()->isDownForMaintenance());

        return parent::getModel($repository);
    }
}