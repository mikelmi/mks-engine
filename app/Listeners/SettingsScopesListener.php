<?php

namespace App\Listeners;


use App\Events\AdminMenuBuild;
use App\Events\PagePathChanged;
use App\Events\SettingsScopesCollect;
use App\Models\CaptchaSettings;
use App\Models\SettingsScope;
use App\Models\SitePages;
use App\Models\SiteSettings;
use App\Services\Settings;
use Illuminate\Events\Dispatcher;
use Illuminate\Support\Collection;

class SettingsScopesListener
{

    public function subscribe(Dispatcher $events)
    {
        $events->listen(SettingsScopesCollect::class, self::class . '@onScopesCollect');
        $events->listen(AdminMenuBuild::class, self::class . '@onAdminMenu');
        $events->listen(PagePathChanged::class, self::class . '@onPagePathChanged');
    }
    
    public function onScopesCollect(SettingsScopesCollect $event)
    {
        $site = new SiteSettings();
        $event->scopes->put('site', $site);

        $users = new SettingsScope('users', trans('a.Users'));
        $users->setFields(['registration', 'auth', 'verification']);
        $event->scopes->put('users', $users);

        $pages = new SitePages();
        $event->scopes->put('page', $pages);

        $event->scopes->put('captcha', new CaptchaSettings());
    }
    
    public function onAdminMenu(AdminMenuBuild $event)
    {
        $menuItem = $event->menu->item('settings');

        if ($menuItem) {
            $scopes = new Collection();
            event(new SettingsScopesCollect($scopes));

            foreach ($scopes as $scope) {
                $menuItem->add($scope->title, ['href' => '#/settings/'.$scope->name, 'hash' => 'settings/'.$scope->name]);
            }
        }
    }

    public function onPagePathChanged(PagePathChanged $event)
    {
        $old = $event->getOldPath();

        if (!$old) {
            return;
        }

        /** @var Settings $settings */
        $settings = app(Settings::class);

        if ($settings->get('page.home.route') == 'page') {
            $params = $settings->get('page.home.params');
            if (is_string($params)) {
                $params = json_decode($settings->get('page.home.params'), true);
            }
            if (is_array($params) && array_get($params, 'path') == $old) {
                $settings->set('page.home.params', json_encode(['path' => $event->getNewPath()]));
                $settings->save();
            }
        }
    }
}