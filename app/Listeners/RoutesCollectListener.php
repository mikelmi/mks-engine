<?php

namespace App\Listeners;


use App\Events\RoutesCollect;
use App\Models\Page;
use Illuminate\Events\Dispatcher;
use Illuminate\Support\Collection;

class RoutesCollectListener
{

    public function subscribe(Dispatcher $events)
    {
        $events->listen(RoutesCollect::class, self::class . '@onRoutesCollect');
        $events->listen('route-params.page.id', self::class . '@onRouteParamsPage');
    }
    
    public function onRoutesCollect(RoutesCollect $event)
    {
        $event->routes->forget('page');
        $page = $event->routes->get('page.id');
        if ($page) {
            $page['text'] = trans('a.Page');
            $page['extended'] = true;
            $event->routes['page.id'] = $page;
        }
    }

    public function onRouteParamsPage(Collection $data)
    {
        $pages = Page::orderBy('title')->select('id', 'title');
        $search = request('q');

        if ($search) {
            $pages->where('title', 'like', '%'.$search.'%');
        }

        $pagination = $pages->paginate(10)->toArray();

        $data->put('items', $pagination['data']);

        unset($pagination['data']);
        $data->put('pagination', $pagination);

        $data->put('title', trans('a.Pages'));
    }
}