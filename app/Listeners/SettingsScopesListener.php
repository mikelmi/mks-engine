<?php

namespace App\Listeners;


use App\Events\AdminMenuBuild;
use App\Events\SettingsScopesCollect;
use App\Models\SettingsScope;
use App\Models\SitePages;
use App\Models\SiteSettings;
use Illuminate\Events\Dispatcher;
use Illuminate\Support\Collection;

class SettingsScopesListener
{

    public function subscribe(Dispatcher $events)
    {
        $events->listen(SettingsScopesCollect::class, self::class . '@onScopesCollect');
        $events->listen(AdminMenuBuild::class, self::class . '@onAdminMenu');
    }
    
    public function onScopesCollect(SettingsScopesCollect $event)
    {
        $site = new SiteSettings();
        $event->scopes->put('site', $site);

        $users = new SettingsScope('users', trans('a.Users'));
        $users->setFields(['registration', 'auth']);
        $event->scopes->put('users', $users);

        $pages = new SitePages();
        $event->scopes->put('page', $pages);
    }
    
    public function onAdminMenu(AdminMenuBuild $event)
    {
        $menuItem = $event->menu->item('settings');

        if ($menuItem) {
            $scopes = new Collection();
            event(new SettingsScopesCollect($scopes));

            foreach ($scopes as $scope) {
                $menuItem->add($scope->title, ['href' => '#/settings/'.$scope->name, 'hash' => 'settings']);
            }
        }
    }
}