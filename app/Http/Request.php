<?php

namespace App\Http;

use Illuminate\Http\Request as BaseRequest;

class Request extends BaseRequest
{
    /**
     * @var array
     */
    protected static $locales = [];

    /**
     * @var string
     */
    private $pathNoLocale;

    /**
     * @var string
     */
    private $language;

    /**
     * @param array $locales
     */
    public static function setLocales(array $locales)
    {
        static::$locales = $locales;
    }

    /**
     * @return string
     */
    public function path()
    {
        return $this->pathWithoutLocale();
    }

    /**
     * @return string
     */
    protected function pathWithoutLocale()
    {
        if (!$this->pathNoLocale) {
            $path = parent::path();

            if (static::$locales) {
                if (preg_match('#^('.(implode('|', static::$locales)).')(/|$)#', $path, $m)) {
                    $this->setLanguage($m[1]);

                    $path = trim(substr($path, strlen($this->language)), '/');

                    if (!$path) {
                        $path = '/';
                    }
                }
                $this->pathNoLocale = $path;
                
            } else {
                return $path;
            }
        }

        return $this->pathNoLocale;
    }

    /**
     * @return string
     */
    public function getLanguage()
    {
        return $this->language;
    }

    /**
     * @return string
     */
    public function localizedRoot()
    {
        $result = parent::root();

        if ($this->language) {
            $result .= '/' . $this->language;
        }

        return $result;
    }

    public function setLanguage($locale)
    {
        if ($locale != $this->language) {
            $this->pathNoLocale = null;
        }
        
        $this->setLocale($locale);
        $this->language = $locale;
        $this->attributes->set('language', $this->language);
    }
}