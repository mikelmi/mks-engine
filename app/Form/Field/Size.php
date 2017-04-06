<?php
/**
 * Author: mike
 * Date: 30.03.17
 * Time: 15:02
 */

namespace App\Form\Field;


use Mikelmi\MksAdmin\Form\Field\Custom;
use Mikelmi\MksAdmin\Form\Field\Number;
use Mikelmi\MksAdmin\Form\FieldInterface;

class Size extends Custom
{
    /**
     * @var Number
     */
    protected $widthElement;

    /**
     * @var Number
     */
    protected $heightElement;

    public function __construct($name = null, $value = null, $label = null)
    {
        parent::__construct($name, $value, $label);

        $this->widthElement = new Number($name ? $name.'[width]' :'width');
        $this->widthElement->setAttributes([
            'min' => 1,
            'title' => __('general.Width'),
            'class' => 'form-control form-control-dim'
        ]);

        $this->heightElement = new Number($name ? $name.'[height]' :'height');
        $this->heightElement->setAttributes([
            'min' => 1,
            'title' => __('general.Height'),
            'class' => 'form-control form-control-dim'
        ]);
    }

    /**
     * @param int|null|array $width
     * @return Size
     */
    public function setWidth($width)
    {
        if (is_array($width)) {
            foreach($width as $k => $v) {
                $this->widthElement->$k = $v;
            }
        } else {
            $this->widthElement->setValue($width);
        }

        return $this;
    }

    /**
     * @param int|null|array $height
     * @return Size
     */
    public function setHeight($height)
    {
        if (is_array($height)) {
            foreach($height as $k => $v) {
                $this->heightElement->$k = $v;
            }
        } else {
            $this->heightElement->setValue($height);
        }

        return $this;
    }

    public function setValue($value): FieldInterface
    {
        parent::setValue($value);

        if (is_array($value) && count($value) == 2) {
            foreach($value as $k => $v) {
                if ($k === 0 || $k == 'width') {
                    $this->widthElement->setValue($v);
                } elseif ($k ===1 || $k == 'height') {
                    $this->heightElement->setValue($v);
                }
            }
        }

        return $this;
    }

    public function render():string
    {
        $template = $this->template;

        if (!$template) {
            $template = 'admin.form.field.size-' . ($this->getLayout() ?: 'default');
        }

        return view($template, ['field' => $this]);
    }

    public function renderInput(): string
    {
        return $this->widthElement->renderInput() .'&nbsp x &nbsp' . $this->heightElement->renderInput();
    }

    public function renderStaticInput(): string
    {
        $width = $this->widthElement->getValue();
        $height = $this->heightElement->getValue();

        if (!$width && !$height) {
            return '';
        }

        return sprintf('<p class="form-control-static">%s x %s</p>', $width, $height);
    }

    public function setRequired(bool $required): FieldInterface
    {
        parent::setRequired($required);

        $this->widthElement->setRequired($required);
        $this->heightElement->setRequired($required);

        return $this;
    }

    public function setReadOnly(bool $readOnly): FieldInterface
    {
        parent::setReadOnly($readOnly);

        $this->widthElement->setReadOnly($readOnly);
        $this->heightElement->setReadOnly($readOnly);

        return $this;
    }

    public function setDisabled(bool $disabled): FieldInterface
    {
        parent::setDisabled($disabled);

        $this->widthElement->setDisabled($disabled);
        $this->heightElement->setDisabled($disabled);

        return $this;
    }

    /**
     * @return Number
     */
    public function width()
    {
        return $this->widthElement;
    }

    /**
     * @return Number
     */
    public function height()
    {
        return $this->heightElement;
    }

    public function getRowAttributes(): array
    {
        $attr = parent::getRowAttributes();

        if (($sceWidth = $this->widthElement->getNameSce()) && ($sceHeight = $this->heightElement->getNameSce())) {
            $attr['ng-class'] = "{'has-danger':page.errors['$sceWidth']||page.errors['$sceHeight']}";
        }

        return $attr;
    }
}