<?php

namespace App\Http\Controllers\Admin;


use App\Services\RouteConfigService;
use Illuminate\Http\Request;
use Mikelmi\MksAdmin\Http\Controllers\AdminController;

class RouteController extends AdminController
{
    public function all(RouteConfigService $configService)
    {
        $result = $configService->collect();

        $result = $result->map(function($item) {
            if ($item['id']) {
                $item['text'] = $item['id'] . ' (' . $item['text'] .')';
            }

            return $item;
        })->values();

        return $result;
    }

    public function params(Request $request, RouteConfigService $configService, $name = null)
    {
        if (!$name) {
            $name = $request->get('name');
        }

        return $configService->collectParams($name);
    }
}