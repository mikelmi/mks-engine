<?php

namespace App\Settings;


class CaptchaSettings extends SettingsScope
{

    /**
     * @return string
     */
    public function name(): string
    {
        return 'captcha';
    }

    /**
     * @return string
     */
    public function title(): string
    {
        return 'Captcha';
    }

    /**
     * @return array
     */
    public function fields(): array
    {
        return [
            ['name' => 'type', 'label' => __('general.Type'), 'type' => 'select',
                'options' => [
                    'simple' => __('messages.Simple'),
                    'recaptcha' => 'Google reCaptcha'
                ],
                'allowEmpty' => true,
                'attributes' => [
                    'ng-model' => 'captchaType',
                    'ng-init' => sprintf("captchaType='%s'", settings('captcha.type'))
                ]
            ],

            ['name' => 'config[template]', 'nameSce' => 'config.template', 'label' => __('general.Template'), 'type' => 'select',
                'options' => [
                    'flat' => 'flat',
                    'mini' => 'mini',
                    'inverse' => 'inverse'
                ],
                'allowEmpty' => true,
                'attributes' => [
                    'ng-model' => 'captchaTemplate',
                    'ng-init' => sprintf("captchaTemplate='%s'", settings('captcha.config.template'))
                ],
                'rowAttributes' => [
                    'ng-show' => "captchaType=='simple'",
                ]
            ],

            ['name' => 'config[sitekey]', 'nameSce' => 'config.sitekey', 'label' => __('general.Title'),
                'rowAttributes' => [
                    'ng-show' => "captchaType=='recaptcha'",
                ]
            ],

            ['name' => 'config[secret]', 'nameSce' => 'config.secret', 'label' => __('general.SecretKey'),
                'rowAttributes' => [
                    'ng-show' => "captchaType=='recaptcha'",
                ]
            ],
        ];
    }
}