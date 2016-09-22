<?php

namespace App\Models;


class CaptchaSettings extends SettingsScope
{
    public function __construct()
    {
        parent::__construct('captcha', 'Captcha');

        $this->setFields(['type', 'config']);
    }
}