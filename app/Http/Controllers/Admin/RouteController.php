<?php

namespace App\Http\Controllers\Admin;


use App\Services\RouteManager;
use Illuminate\Http\Request;

class RouteController extends AdminController
{
    /**
     * @var RouteManager
     */
    private $routeManager;

    protected function init()
    {
        parent::init();

        $this->routeManager = resolve(RouteManager::class);
    }

    public function all()
    {
        return $this->routeManager->links()->values();
    }

    public function params(Request $request, $name = null)
    {
        if (!$name) {
            $name = $request->get('name');
        }

        return $this->routeManager->collectParams($name);
    }
}