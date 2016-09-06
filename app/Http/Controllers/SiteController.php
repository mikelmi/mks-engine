<?php

namespace App\Http\Controllers;


use App\Services\Settings;
use Artesaos\SEOTools\Traits\SEOTools;

class SiteController extends Controller
{
    use SEOTools;

    public function __construct(Settings $settings)
    {
        $this->seo()->metatags()->setTitleDefault($settings->get('site.title'));
        $this->seo()->setDescription($settings->get('site.description'));
        $this->seo()->metatags()->setKeywords($settings->get('site.keywords'));
    }
}