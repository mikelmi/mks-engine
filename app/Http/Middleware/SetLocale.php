<?php

namespace App\Http\Middleware;


use App\Repositories\LanguageRepository;
use Illuminate\Http\Request;

class SetLocale
{
    /**
     * @var LanguageRepository
     */
    private $langRepository;

    public function __construct(LanguageRepository $languageRepository)
    {
        $this->langRepository = $languageRepository;
    }

    public function handle(Request $request, \Closure $next, $guard = null)
    {
        $locale = $request->attributes->get('language');

        if (!$locale && ($lang = $request->cookie('locale'))) {
            $exists = $this->langRepository->locales();
            if ($exists && in_array($lang, $exists)) {
                $locale = $lang;
                $request->attributes->set('language', $locale);
            }
        }

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