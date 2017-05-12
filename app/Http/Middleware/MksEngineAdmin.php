<?php
/**
 * Author: mike
 * Date: 11.05.17
 * Time: 20:27
 */

namespace App\Http\Middleware;

use Illuminate\Http\Request;

class MksEngineAdmin
{
    public function handle($request, \Closure $next)
    {
        return $next($request);
    }

    /**
     * @param Request $request
     * @param $response
     */
    public function terminate($request, $response)
    {
        if ($request->attributes->get('clear-cache')) {
            \ResponseCache::flush();
        }
    }
}