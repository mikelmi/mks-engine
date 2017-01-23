<?php

namespace App\Settings;


use Illuminate\Config\Repository;
use Illuminate\Validation\Rule;

class FilesSettings extends SettingsScope
{
    public function __construct()
    {
        parent::__construct('files', trans('filemanager.Files'));

        $this->setFields([
            'upload',
            'extensions',
            'max_size',
            'watermark',
            'max_width',
            'max_height',
            'mark_pos',
            'mark_alpha',
            'mark_width',
            'mark_height',
        ]);
    }

    public function getRules()
    {
        return [
            'extensions' => 'array',
            'max_size' => 'numeric|min:0',
            'max_width' => 'integer|min:200|max:3000',
            'max_height' => 'integer|min:200|max:3000',
            'mark_alpha' => 'integer|min:0|max:100',
            'mark_width' => 'integer|min:10|max:500',
            'mark_height' => 'integer|min:10|max:500',
            'mark_pos' => Rule::in($this->markPositions())
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
        $repository->set('mark_positions', $this->markPositions());

        return $repository;
    }

    private function markPositions()
    {
        return [
            'top-left',
            'top',
            'top-right',
            'left',
            'center',
            'right',
            'bottom-left',
            'bottom',
            'bottom-right'
        ];
    }
}