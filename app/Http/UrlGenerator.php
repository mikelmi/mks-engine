<?php

namespace App\Http;


class UrlGenerator extends \Illuminate\Routing\UrlGenerator
{
    private $language;

    protected function getRouteRoot($route, $domain)
    {
        return $this->getRootUrl($this->getRouteScheme($route), $domain, true);
    }

    protected function toRoute($route, $parameters, $absolute)
    {
        $result = parent::toRoute($route, $parameters, $absolute);

        if (!$absolute && $locale = $this->getLanguage())
        {
            $result = $locale . '/' . $result;
        }

        return $result;
    }

    protected function getRootUrl($scheme, $root = null, $withLocale = false)
    {
        $result = parent::getRootUrl($scheme, $root);

        if ($withLocale && $locale = $this->getLanguage())
        {
            $result .= '/' . $locale;
        }

        return $result;
    }

    protected function getLanguage()
    {
        if ($this->language === null) {
            $this->language = $this->request->attributes->get('language', false);
        }

        return $this->language;
    }
}