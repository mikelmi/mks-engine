<?php
/**
 * Author: mike
 * Date: 28.03.17
 * Time: 17:37
 */

namespace App\Settings;


class PageSettings extends SettingsScope
{

    public function name(): string
    {
        return 'pages';
    }

    public function title(): string
    {
        return __('general.Pages');
    }

    /**
     * @return array
     */
    public function fields(): array
    {
        return [
            ['name' => 'home', 'label' => __('general.Homepage'), 'type' => 'route'],
            ['name' => 'e404', 'label' => '404', 'type' => 'pages'],
            ['name' => 'e500', 'label' => __('general.Error page'), 'type' => 'pages'],
            ['name' => 'e503', 'label' => __('general.Offline page'), 'type' => 'pages'],
        ];
    }
}