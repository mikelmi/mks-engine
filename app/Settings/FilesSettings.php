<?php

namespace App\Settings;


use Illuminate\Config\Repository;
use Illuminate\Validation\Rule;

class FilesSettings extends SettingsScope
{
    public function rules(): array
    {
        return [
            'extensions' => 'array',
            'max_size' => 'numeric|min:0',
            'img_max_size.width' => 'integer|min:200|max:3000',
            'img_max_size.height' => 'integer|min:200|max:3000',
            'mark_alpha' => 'integer|min:0|max:100',
            'mark_size.width' => 'integer|min:10|max:500',
            'mark_size.height' => 'integer|min:10|max:500',
            'mark_pos' => Rule::in(array_keys($this->markPositions()))
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
        $positions = [
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

        $titles = array_map(function($item) {
            return __('general.' . $item);
        }, $positions);

        return array_combine($positions, $titles);
    }

    /**
     * @return string
     */
    public function name(): string
    {
        return 'files';
    }

    /**
     * @return string
     */
    public function title(): string
    {
        return __('filemanager.Files');
    }

    /**
     * @return array
     */
    public function fields(): array
    {
        $extensions = (array) settings('files.extensions', []);

        return [
            ['name' => 'upload', 'label' => __('filemanager.Enable upload'), 'type' => 'select',
                'options' => [
                    '' => __('filemanager.upload_with_permissions'),
                    '1' => __('filemanager.upload_for_all')
                ]
            ],
            ['name' => 'extensions[]', 'nameSce' => 'extensions', 'label' => __('filemanager.Extensions'), 'type' => 'select2',
                'multiple' => true,
                'attributes' => [
                    'data-tags' => 'true',
                    'data-token-separators' => "[',', ';', ' ']",
                ],
                'options' => array_combine($extensions, $extensions)
            ],
            ['name' => 'max_size', 'label' => __('filemanager.max_size') . ' (Mb)', 'type' => 'number'],
            ['name' => 'img_max_size', 'label' => __('general.image_max_size'), 'type' => 'size',
                'width' => [
                    'name' => 'files[img_max_size][width]',
                    'nameSce' => 'files.img_max_size.width',
                    'value' => settings('files.max_width'),
                    'min' => 200,
                    'max' => 3000,
                ],
                'height' => [
                    'name' => 'files[img_max_size][height]',
                    'nameSce' => 'files.img_max_size.height',
                    'value' => settings('files.max_height'),
                    'min' => 200,
                    'max' => 3000,
                ],
            ],

            ['name' => 'watermark', 'label' => __('general.watermark'), 'type' => 'image'],
            ['name' => 'mark_size', 'label' => __('general.watermark_size'), 'type' => 'size',
                'width' => [
                    'name' => 'files[mark_size][width]',
                    'nameSce' => 'files.mark_size.width',
                    'value' => settings('files.mark_width'),
                    'min' => 100,
                    'max' => 500,
                ],
                'height' => [
                    'name' => 'files[mark_size][height]',
                    'nameSce' => 'files.mark_size.height',
                    'value' => settings('files.mark_height'),
                    'min' => 100,
                    'max' => 500
                ],
            ],

            ['name' => 'mark_pos', 'label' => __('general.watermark_pos'), 'type' => 'select',
                'options' => $this->markPositions()
            ],

            ['name' => 'mark_alpha', 'label' => __('general.watermark_alpha'), 'type' => 'text',
                'min' => 0,
                'max' => 100,
                'placeholder' => 50,
                'class' => 'form-control form-control-dim'
            ],
        ];
    }

    public function input(array $data): array
    {
        $data['max_width'] = array_get($data, 'img_max_size.width');
        $data['max_height'] = array_get($data, 'img_max_size.height');

        $data['mark_width'] = array_get($data, 'mark_size.width');
        $data['mark_height'] = array_get($data, 'mark_size.height');

        unset($data['img_max_size'], $data['mark_size']);

        return $data;
    }


}