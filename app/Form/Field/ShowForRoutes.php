<?php
/**
 * Author: mike
 * Date: 24.03.17
 * Time: 20:36
 */

namespace App\Form\Field;


use Mikelmi\MksAdmin\Form\Field\Custom;

class ShowForRoutes extends Custom
{
    public function render(): string
    {
        $attr = $this->getAttributes();

        unset($attr['class'], $attr['id']);

        $attr['value'] = $this->getValue();

        return '<mks-routes-select '.html_attr($attr).'></mks-routes-select>';
    }
}