<?php
/**
 * Author: mike
 * Date: 28.03.17
 * Time: 17:19
 */

namespace App\Settings;


class UserSettings extends SettingsScope
{

    public function name(): string
    {
        return 'users';
    }

    public function title(): string
    {
        return __('general.Users');
    }

    /**
     * @return array
     */
    public function fields(): array
    {
        return [
            ['name' => 'registration', 'label' => __('user.Enable Registration'), 'type' => 'toggle'],
            ['name' => 'auth', 'label' => __('user.Enable Auth'), 'type' => 'toggle'],
            ['name' => 'verification', 'label' => __('user.Email Verification'), 'type' => 'toggle'],
        ];
    }
}