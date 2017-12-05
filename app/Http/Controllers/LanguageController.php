<?php
/**
 * Author: mike
 * Date: 04.04.17
 * Time: 17:45
 */

namespace App\Http\Controllers;


use App\Repositories\LanguageRepository;
use App\Services\ImageService;
use Illuminate\Http\Request;

class LanguageController extends Controller
{
    public function icon(Request $request, ImageService $imageService, $iso = null)
    {
        $file = 'vendor/mikelmi/mks-admin/img/lang/' . ($iso ?: $request->get('iso')) . '.gif';

        return $imageService->assetProxy($request, $file, null, 12, 8);
    }

    public function change(Request $request, LanguageRepository $languageRepository, $iso)
    {
        if (!$languageRepository->get($iso)) {
            abort(404);
        }

        /** @var \Illuminate\Routing\UrlGenerator $url */
        $url = app('url');

        $prev = $url->previous('/'.$iso);

        $root = $request->root();

        $path = ltrim(str_replace_first($root, '', $prev), '/');

        foreach($languageRepository->locales() as $locale) {
            $path = ltrim(str_replace_first($locale, '', $path), '/');
        }

        $url = $root . '/' . $iso;

        if ($path && !$languageRepository->has($path)) {
            $url .= '/' . $path;
        }

        return redirect()->away($url)->withCookie(cookie('locale', $iso, 43200));
    }
}