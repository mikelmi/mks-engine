<?php

namespace App\Http\Controllers;


use App\Http\Request;
use App\Models\Category;
use App\Repositories\Breadcrumbs;
use App\Repositories\LanguageRepository;
use App\Services\Settings;
use App\Traits\HasMeta;
use Artesaos\SEOTools\Traits\SEOTools;
use Illuminate\Database\Eloquent\Model;

class SiteController extends Controller
{
    use SEOTools;

    /**
     * Enable caching full response
     *
     * @var bool
     */
    protected $cacheable = true;

    public function __construct(Settings $settings, LanguageRepository $languageRepository)
    {
        $title = $settings->get('site.title');
        $description = $settings->get('site.description');
        $keywords = $settings->get('site.keywords');

        if ($locale = app()->getLocale()) {
            if ($language = $languageRepository->get($locale)) {
                $title = $language->get('site.title', $title);
                $description = $language->get('site.description', $description);
                $keywords = $language->get('site.keywords', $keywords);
            }
        }

        $this->seo()->metatags()->setTitleDefault($title);
        $this->seo()->setDescription($description);
        $this->seo()->metatags()->setKeywords($keywords);

        if ($this->cacheable && $settings->get('system.cache')) {
            if ($lifetime = $settings->get('system.cache_lifetime')) {
                config()->set('responsecache.cache_lifetime_in_minutes', $lifetime);
            }

            $this->middleware('cacheResponse');
        }

        $this->init();
    }

    protected function init()
    {

    }

    public function flashMessage($message, $type='info')
    {
        \Session::flash('message', $message);
        \Session::flash('alert-class', $type == 'error' ? 'alert-danger' : 'alert-' . $type);
    }

    public function flashSuccess($message)
    {
        $this->flashMessage($message, 'success');
    }

    public function flashError($message)
    {
        $this->flashMessage($message, 'error');
    }

    public function flashInfo($message)
    {
        $this->flashMessage($message, 'info');
    }

    public function flashNotice($message)
    {
        $this->flashMessage($message, 'warning');
    }

    /**
     * @return Breadcrumbs
     */
    protected function breadcrumbs()
    {
        return app(Breadcrumbs::class);
    }

    protected function setCategoryBreadcrumbs(Category $category, $end = true)
    {
        $ancestors = $category->getAncestors();

        $breadcrumbs = $this->breadcrumbs();

        foreach ($ancestors as $item) {
            $breadcrumbs->add($item->title, $item->getUrl());
        }

        $breadcrumbs->add($category->title, !$end ? $category->getUrl() : null);

        /** @var Request $request */
        $request = app('request');

        $request->attributes->set('category_id', $category->id);
    }

    /**
     * @param Model $model
     */
    protected function setModelMeta(Model $model)
    {
        $seo = $this->seo();

        /** @var HasMeta $model */

        if ($title = $model->meta('title')) {
            $seo->setTitle($title);
        }

        if ($description = $model->meta('description')) {
            $seo->setDescription($description);
        }

        if ($keywords = $model->meta('keywords')) {
            $seo->metatags()->setKeywords($keywords);
        }
    }
}