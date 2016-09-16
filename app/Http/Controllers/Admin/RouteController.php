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

        return $result->values();
    }
    
    public function params(Request $request, RouteConfigService $configService, $name = null)
    {
        if (!$name) {
            $name = $request->get('name');
        }
        
        return $configService->collectParams($name);
    }
}