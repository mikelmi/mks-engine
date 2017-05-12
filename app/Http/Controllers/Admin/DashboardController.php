<?php

namespace App\Http\Controllers\Admin;


use App\Models\Page;
use App\Models\Widget;
use App\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\Request;
use Illuminate\Notifications\DatabaseNotification;

class DashboardController extends AdminController
{
    public function home(Request $request)
    {
        return view('admin.dashboard.home');
    }

    public function notifications(Request $request)
    {
        /** @var LengthAwarePaginator $data */
        $data = $request->user()->notifications()->paginate(3);

        $notifications = collect($data->items())->map(function($item) {
            /** @var DatabaseNotification $item */
            $item->title = call_user_func([$item->type, 'title'], $item->data);

            return array_only($item->getAttributes(), [
                'id', 'title', 'read_at', 'created_at',
            ]);
        });

        $data = $data->toArray();
        $data['data'] = $notifications->toArray();

        return $data;
    }

    public function notificationDetails(Request $request, $id)
    {
        /** @var DatabaseNotification $item */
        $item = $request->user()->notifications()->find($id);

        $details = call_user_func([$item->type, 'details'], $item->data);

        if (!$item->read_at) {
            $item->markAsRead();
        }

        $read_at = $item->read_at;

        return compact('details', 'read_at');
    }

    public function notificationDelete(Request $request, $id)
    {
        $result = $request->user()->notifications()->find($id)->delete();

        return compact('result');
    }

    public function notificationsDelete(Request $request, $all = false)
    {
        $query = $request->user()->notifications();

        if (!$all) {
            $query->whereNotNull('read_at');
        }

        $result = $query->delete();

        return compact('result');
    }

    public function statistics()
    {
        return [
            [
                'title' => __('general.Users'),
                'count' => User::count(),
                'url' => '#/user',
            ],
            [
                'title' => __('general.Pages'),
                'count' => Page::withTrashed()->count(),
                'url' => '#/page',
            ],
            [
                'title' => __('general.Widgets'),
                'count' => Widget::count(),
                'url' => '#/widget',
            ],
        ];
    }
}