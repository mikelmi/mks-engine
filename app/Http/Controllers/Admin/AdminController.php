<?php
/**
 * Author: mike
 * Date: 11.05.17
 * Time: 20:20
 */

namespace App\Http\Controllers\Admin;


use App\Http\Middleware\MksEngineAdmin;
use Illuminate\Http\Request;
use Mikelmi\MksAdmin\Http\Controllers\AdminController as BaseAdminController;

class AdminController extends BaseAdminController
{
    protected function init()
    {
        $this->middleware(MksEngineAdmin::class);
    }

    protected function triggerClearCache(Request $request = null)
    {
        if (!$request) {
            $request = app(Request::class);
        }

        $request->attributes->set('clear-cache', true);
    }
}