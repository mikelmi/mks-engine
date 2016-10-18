<?php

namespace App\Http\Middleware;


use Illuminate\Http\Request;

class SetLocale
{
    public function handle(Request $request, \Closure $next, $guard = null)
    {
        $locale = $request->attributes->get('language');

        if (!$locale) {
            $locales = locales();
            $locale = $locales ? ($request->getPreferredLanguage(locales()) ?: settings('locale')) : null;
        }

        if ($locale) {
            app()->setLocale($locale);
        }

        return $next($request);
    }
}