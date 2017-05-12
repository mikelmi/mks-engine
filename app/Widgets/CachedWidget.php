<?php
/**
 * Author: mike
 * Date: 01.05.17
 * Time: 19:30
 */

namespace App\Widgets;


abstract class CachedWidget extends WidgetPresenter
{
    protected $lifetime = 60;

    /**
     * @return string
     */
    abstract function freshRender(): string;

    /**
     * @return string
     */
    public function render(): string
    {
        $key = 'widget-' . $this->alias();

        if ($result = \Cache::get($key)) {
            return $result;
        }

        $result = $this->freshRender();
        \Cache::put($key, $result, $this->lifetime);

        return $result;
    }
}