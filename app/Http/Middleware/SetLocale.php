<?php

namespace App\Http\Middleware;


use Illuminate\Http\Request;

class SetLocale
{
    public function handle(Request $request, \Closure $next, $guard = null)
    {
        if ($locale = $request->attributes->get('language')) {
            app()->setLocale($locale);
        }

        return $next($request);
    }
}