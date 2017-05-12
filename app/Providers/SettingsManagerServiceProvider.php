<?php
/**
 * Author: mike
 * Date: 27.03.17
 * Time: 20:46
 */

namespace App\Providers;


use App\Services\Settings;
use App\Services\SettingsManager;
use App\ServiceTag;
use App\Settings\CaptchaSettings;
use App\Settings\FilesSettings;
use App\Settings\PageSettings;
use App\Settings\SiteSettings;
use App\Settings\SystemSettings;
use App\Settings\UserSettings;
use Illuminate\Support\ServiceProvider;

class SettingsManagerServiceProvider extends ServiceProvider
{
    protected $defer = true;

    private $scopes = [
        SiteSettings::class,
        UserSettings::class,
        PageSettings::class,
        CaptchaSettings::class,
        FilesSettings::class,
        SystemSettings::class
    ];

    public function register()
    {
        $this->app->singleton(SettingsManager::class, function($app) {
            return new SettingsManager($app->tagged(ServiceTag::SETTINGS), $app[Settings::class]);
        });

        $this->app->tag($this->scopes, ServiceTag::SETTINGS);
    }

    public function provides()
    {
        return [SettingsManager::class];
    }
}