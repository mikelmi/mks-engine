<?php
/**
 * User: mike
 * Date: 30.11.17
 * Time: 3:43
 */

namespace App\Http;

use Illuminate\Routing\RouteUrlGenerator as BaseGenerator;

class RouteUrlGenerator extends BaseGenerator
{
    /**
     * @var UrlGenerator
     */
    protected $url;

    protected function replaceRouteParameters($path, array &$parameters)
    {
        if (!preg_match('/^https?\:\/\//', $path) && $lang = $this->url->getLanguage()) {
            $path = $lang . '/' . ltrim($path, '/');
        }

        return parent::replaceRouteParameters($path, $parameters);
    }
}