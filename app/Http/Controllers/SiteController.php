<?php

namespace App\Http\Controllers;


use App\Repositories\Breadcrumbs;
use App\Repositories\LanguageRepository;
use App\Services\Settings;
use Artesaos\SEOTools\Traits\SEOTools;

class SiteController extends Controller
{
    use SEOTools;

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
}