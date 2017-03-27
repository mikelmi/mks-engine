<?php
/**
 * Author: mike
 * Date: 24.03.17
 * Time: 14:22
 */

namespace App\Form;


use Mikelmi\MksAdmin\Form\ButtonLink;

class DropDownItem extends ButtonLink
{
    protected function getClass(): string
    {
        return 'dropdown-item';
    }

    /**
     * @param array $options
     * @return DropDownItem
     */
    public static function make(array $options)
    {
        $instance = new DropDownItem();

        foreach ($options as $key => $value) {
            if (!is_string($key)) {
                continue;
            }

            $method = 'set' . ucfirst($key);

            if (is_callable([$instance, $method])) {
                $instance->$method($value);
            }
        }

        return $instance;
    }
}