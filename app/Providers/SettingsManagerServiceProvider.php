<?php
/**
 * Author: mike
 * Date: 27.03.17
 * Time: 20:46
 */

namespace App\Providers;


use App\Services\Settings;
use App\Services\SettingsManager;
use App\Settings\CaptchaSettings;
use App\Settings\PageSettings;
use App\Settings\SiteSettings;
use App\Settings\UserSettings;
use Illuminate\Support\ServiceProvider;

class SettingsManagerServiceProvider extends ServiceProvider
{
    protected $defer = true;

    private $scopes = [
        SiteSettings::class,
        UserSettings::class,
        PageSettings::class,
        CaptchaSettings::class
    ];

    public function register()
    {
        $this->app->singleton(SettingsManager::class, function($app) {
            return new SettingsManager($app->tagged('settings-scopes'), $app[Settings::class]);
        });

        $this->app->tag($this->scopes, 'settings-scopes');
    }

    public function provides()
    {
        return array_merge(
            $this->scopes,
            [SettingsManager::class]
        );
    }
}