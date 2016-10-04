<?php

namespace App\Models;


use App\Settings\SettingsScope;

class CaptchaSettings extends SettingsScope
{
    public function __construct()
    {
        parent::__construct('captcha', 'Captcha');

        $this->setFields(['type', 'config']);
    }
}