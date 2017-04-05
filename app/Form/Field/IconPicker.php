<?php
/**
 * Author: mike
 * Date: 05.04.17
 * Time: 16:58
 */

namespace App\Form\Field;


use Mikelmi\MksAdmin\Form\Field;

class IconPicker extends Field
{

    /**
     * @return string
     */
    public function renderInput(): string
    {
        $attr = $this->getAttributes();

        unset($attr['class']);

        $attr['value'] = $this->getValue();

        return '<mks-icon-picker ' . html_attr($attr) . '></mks-icon-picker>';
    }
}