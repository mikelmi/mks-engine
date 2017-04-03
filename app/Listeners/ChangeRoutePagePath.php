<?php

namespace App\Listeners;


use App\Events\PagePathChanged;
use App\Models\MenuItem;
use App\Models\WidgetRoutes;

class ChangeRoutePagePath
{

    public function handle(PagePathChanged $event)
    {
        $old = $event->getOldPath();
        $new = $event->getNewPath();
        
        if ($old && $new) {

            \DB::beginTransaction();

            if (\DB::getName() == 'mysql') {
                $menuItems = MenuItem::where('params->path', $old)->get();
                $widgetRoutes = WidgetRoutes::where('params->path', $old)->get();
            } else {
                $menuItems = MenuItem::where('params', 'like', '%"path":"' . $old . '"%')->get();
                $widgetRoutes = WidgetRoutes::where('params', 'like', '%"path":"' . $old . '"%')->get();
            }

            foreach ($menuItems as $item) {
                $item->params = ['path' => $new];
                $item->save();
            }

            foreach ($widgetRoutes as $item) {
                $item->params = ['path' => $new];
                $item->save();
            }

            \DB::commit();
        }
    }
}