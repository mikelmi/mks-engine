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
    public function __construct($name = null, $value = null, $label = null)
    {
        parent::__construct($name, $value, $label ?: __('general.Icon'));
    }

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