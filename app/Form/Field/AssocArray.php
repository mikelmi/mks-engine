<?php
/**
 * Author: mike
 * Date: 25.03.17
 * Time: 14:48
 */

namespace App\Form\Field;


use Mikelmi\MksAdmin\Form\Field\Custom;

class AssocArray extends Custom
{
    public function renderInput(): string
    {
        $attr = $this->getAttributes();

        unset($attr['class']);

        if (is_array($this->value)) {
            $attr['value'] = json_encode($this->value);
        }

        return '<mks-assoc-input ' . html_attr($attr) . '></mks-assoc-input>';
    }
}