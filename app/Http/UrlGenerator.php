<?php

namespace App\Http;

use App\Repositories\LanguageRepository;
use Illuminate\Http\Request;
use Illuminate\Routing\RouteCollection;

class UrlGenerator extends \Illuminate\Routing\UrlGenerator
{
    /**
     * @var string
     */
    private $language;

    public function __construct(RouteCollection $routes, Request $request)
    {
        parent::__construct($routes, $request);
    }

    /**
     * @return RouteUrlGenerator
     */
    protected function routeUrl()
    {
        if (!$this->routeGenerator) {
            $this->routeGenerator = new RouteUrlGenerator($this, $this->request);
        }

        return $this->routeGenerator;
    }

    public function getLanguage()
    {
        if ($this->language === null) {
            $this->language = $this->request->attributes->get('language', false);
        }

        return $this->language;
    }
}