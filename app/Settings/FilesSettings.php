<?php

namespace App\Settings;


use Illuminate\Config\Repository;

class FilesSettings extends SettingsScope
{
    public function __construct()
    {
        parent::__construct('page', trans('filemanager.Files'));

        $this->setFields(['upload', 'extensions', 'max_size']);
    }

    public function getRules()
    {
        return [
            'extensions' => 'array',
            'max_size' => 'numeric|min:0'
        ];
    }

    public function beforeSave(&$data)
    {
        if (!is_array($data['extensions'])) {
            $data['extensions'] = $data['extensions'] ? explode(',', $data['extensions']) : '';
        }
    }

    public function getModel(Repository $repository)
    {
        $extensions = $repository->get('extensions');

        if (!is_array($extensions)) {
            $extensions = $extensions ? explode(',', $extensions) : [];
        }

        $repository->set('extensions', $extensions);

        return $repository;
    }
}