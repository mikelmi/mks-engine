<?php

namespace App\Providers;


use App\Services\CaptchaManager;
use App\Services\Settings;
use Illuminate\Support\ServiceProvider;

class CaptchaServiceProvider extends ServiceProvider
{
    protected $defer = false;

    public function register()
    {
        /** @var Settings $settings */
        $settings = $this->app['settings'];

        if ($settings->get('captcha.type') == 'simple') {
            $this->app->register(\Mews\Captcha\CaptchaServiceProvider::class);
        } elseif ($settings->get('captcha.type') == 'recaptcha') {
            $secret = $settings->get('captcha.config.secret');
            $sitekey = $settings->get('captcha.config.sitekey');

            if ($secret) {
                $this->app['config']['captcha.secret'] = $secret;
            }

            if ($sitekey) {
                $this->app['config']['captcha.sitekey'] = $sitekey;
            }

            $this->app->register(\Anhskohbo\NoCaptcha\NoCaptchaServiceProvider::class);
        }

        $this->app->singleton(CaptchaManager::class, function($app) {
            return new CaptchaManager($app['captcha'], $app[Settings::class]);
        });

        $this->app->alias(CaptchaManager::class, 'app.captcha');
    }

    public function provides()
    {
        return [
            CaptchaManager::class,
            'app.captcha'
        ];
    }
}