<?php
/**
 * Author: mike
 * Date: 28.03.17
 * Time: 18:01
 */

namespace App\Form\Field;


use Mikelmi\MksAdmin\Form\Field\Custom;

class ImagePicker extends Custom
{
    public function renderInput(): string
    {
        $attr = $this->getAttributes();

        unset($attr['class']);

        $attr['id'] = 'image-select-' . uniqid();
        $attr['data-image'] = $this->getValue();

        return '<mks-image-select ' . html_attr($attr) . '></mks-image-select>';
    }

    public function renderStaticInput(): string
    {
        if ($this->getValue()) {
            $this->setAttribute('disabled', 'true');
            return $this->renderInput();
        }

        return '';
    }
}