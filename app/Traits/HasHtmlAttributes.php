<?php
/**
 * Author: mike
 * Date: 06.04.17
 * Time: 10:54
 */

namespace App\Traits;


/**
 * Class HasHtmlAttributes
 * @package App\Traits
 *
 * @property array $attr
 */
trait HasHtmlAttributes
{
    /**
     * @param string|null $key
     * @param string|null $default
     * @return array|mixed
     */
    public function htmlAttr(string $key = null, string $default = null)
    {
        $attr = $this->attr;

        if (is_null($attr)) {
            $attr = [];
        }

        if ($key) {
            return array_get($attr, $key, $default);
        }

        return $attr;
    }

    /**
     * @return array
     */
    public function htmlAttributes(): array
    {
        return $this->htmlAttr();
    }
}