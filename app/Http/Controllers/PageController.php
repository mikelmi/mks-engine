<?php

namespace App\Http\Controllers;


use App\Models\Page;
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

        $page = Page::where('path', $path)->first();

        if (!$page) {
            abort(404);
        }

        return $this->show($page);
    }

    public function show(Page $page)
    {
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

    public function home(Request $request, Settings $settings, Router $router)
    {
        $routeName = $settings->get('page.home.route');

        if ($routeName) {
            $params = json_decode($settings->get('page.home.params'), true);

            if ($params) {
                $uri = route($routeName, $params, false);
                $newRequest = $request->create($uri);

                return $router->dispatch($newRequest);
            }
        }

        return 'Home page';
    }
}