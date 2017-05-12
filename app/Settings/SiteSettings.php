<?php

namespace App\Settings;


class SiteSettings extends SettingsScope
{
    public function afterSave(array $old, array $new)
    {
        $isDown = app()->isDownForMaintenance();
        if ($isDown && !array_get($new, 'off')) {
            \Artisan::call('up');
        } elseif (!$isDown && array_get($new, 'off')) {
            \Artisan::call('down');
        }
    }

    public function name(): string
    {
        return 'site';
    }

    public function title(): string
    {
        return __('general.Site');
    }

    public function fields(): array
    {
        return [
            ['name' => 'title', 'label' => __('general.Title')],
            ['name' => 'description', 'label' => __('general.Description'), 'type' => 'textarea'],
            ['name' => 'keywords', 'label' => __('general.Keywords')],
            ['name' => 'theme', 'label' => __('general.Theme'), 'type' => 'select',
                'options' => \Theme::all()->toArray(),
                'allowEmpty' => true,
            ],
            ['name' => 'logo', 'label' => __('general.Logo'), 'type' => 'image'],
            ['name' => 'background', 'label' => __('general.Background'), 'type' => 'image'],
            ['name' => 'background_fixed', 'label' => __('general.Fixed Background'), 'type' => 'toggle'],
            ['name' => 'off', 'label' => __('general.Site off'), 'type' => 'toggle',
                'value' => (int) app()->isDownForMaintenance()
            ],
        ];
    }
}