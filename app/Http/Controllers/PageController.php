<?php

namespace App\Http\Controllers;


use App\Models\Page;
use App\Services\CaptchaManager;
use App\Repositories\LanguageRepository;
use App\Services\Settings;
use Illuminate\Http\Request;
use Illuminate\Routing\Router;

class PageController extends SiteController
{
    public function getById($id)
    {
        $page = Page::find($id);

        if (!$page) {
            abort(404);
        }

        return $this->show($page);
    }

    public function getByPath($path = null)
    {
        if (!$path) {
            return $this->home();
        }

        $locale = app()->getLocale();

        if ($locale) {
            $page = Page::where('path', $path)->where('lang', $locale)->first();
        }

        if (!$page) {
            $page = Page::where('path', $path)->orderBy('lang')->first();
        }

        if (!$page) {
            abort(404);
        }

        return $this->show($page);
    }

    public function show(Page $page)
    {
        if ($page->param('roles')) {
            $this->authorize('view', $page);
        }

        $title = $page->meta_title ?: $page->title;

        if ($title) {
            $this->seo()->setTitle($title);
        }

        if ($description = $page->meta_description) {
            $this->seo()->setDescription($description);
        }

        if ($keywords = $page->meta_keywords) {
            $this->seo()->metatags()->setKeywords($keywords);
        }

        return view('page.show', compact('page'));
    }

    public function home(Request $request, Settings $settings, Router $router, LanguageRepository $languageRepository)
    {
        $locale = app()->getLocale();

        $routeName = null;
        $routeParams = null;

        if ($locale && $language = $languageRepository->get($locale)) {
            $routeName = $language->get('home.route');
            $routeParams = $language->get('home.params');
        }

        if (!$routeName) {
            $routeName = $settings->get('page.home.route');
            $routeParams = $settings->get('page.home.params');
        }

        if ($routeName) {
            $params = json_decode($routeParams, true);

            if ($params) {
                $uri = route($routeName, $params, false);
                $newRequest = $request->create($uri);

                return $router->dispatch($newRequest);
            }
        }

        return view('page.home');
    }

    public function captchaImage(CaptchaManager $captchaManager)
    {
        $response = $captchaManager->getImage();

        if (!$response) {
            abort(404);
        }

        return $response;
    }
}