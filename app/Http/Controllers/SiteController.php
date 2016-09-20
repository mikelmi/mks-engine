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
}