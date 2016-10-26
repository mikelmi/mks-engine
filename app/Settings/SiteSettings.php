<?php

namespace App\Settings;


use Illuminate\Config\Repository;

class SiteSettings extends SettingsScope
{
    public function __construct()
    {
        parent::__construct('site', trans('general.Site'));

        $this->setFields(['title', 'description', 'keywords', 'off', 'theme']);
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
        $repository->set('themes', \Theme::all());

        return parent::getModel($repository);
    }
}