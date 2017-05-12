<?php
/**
 * Author: mike
 * Date: 11.05.17
 * Time: 18:12
 */

namespace App\Settings;


class SystemSettings extends SettingsScope
{
    /**
     * @return string
     */
    public function name(): string
    {
        return 'system';
    }

    /**
     * @return array
     */
    public function fields(): array
    {
        return [
            ['name' => 'cache', 'label' => __('general.Cache Pages'), 'type' => 'toggle'],
            ['name' => 'cache_lifetime', 'label' => __('general.Cache Lifetime'), 'type' => 'number',
                'helpText' => __('general.In minutes')
            ],
            ['name' => 'btn_cache_clear', 'type' => 'button', 'label' => __('general.Clear Cache'),
                'mks-action' => true,
                'data-url' => route('admin::artisan.run', ['command' => 'responsecache:flush', 'flash'=>1]),
                'class' => 'btn btn-success'
            ],
        ];
    }

    /**
     * @return string
     */
    public function title(): string
    {
        return __('general.System');
    }

    public function afterSave(array $old, array $new)
    {
        $cache = array_get($new, 'cache');

        if (!$cache && array_get($old, 'cache')) {
            \Artisan::call('responsecache:flush');
        }
    }
}