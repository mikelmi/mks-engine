<?php
/**
 * Author: mike
 * Date: 20.03.17
 * Time: 12:01
 */

namespace App\Form\Field;


use Mikelmi\MksAdmin\Form\Field;
use Mikelmi\MksAdmin\Form\FieldInterface;


class MetaTags extends Field
{
    protected $values = [];

    public function setValue($value): FieldInterface
    {
        parent::setValue($value);

        if (is_array($value)) {
            $this->values = $value;
        } elseif (is_string($value) && ($values = json_decode($value, true))) {
            $this->values = $values;
        }

        return $this;
    }

    protected function nameField($key)
    {
        return $this->getName() . '[' . $key . ']';
    }

    protected function valueField($key)
    {
        return array_get($this->values, $key);
    }

    public function render(): string
    {
        return $this->renderInput();
    }

    /**
     * @return string
     */
    public function renderInput(): string
    {
        $fields = [
            new Field\Text($this->nameField('title'), $this->valueField('title'), __('general.Caption')),
            new Field\Textarea($this->nameField('description'), $this->valueField('description'), __('general.Description')),
            new Field\Text($this->nameField('keywords'), $this->valueField('keywords'), __('general.Keywords')),
            new Field\Text($this->nameField('author'), $this->valueField('author'), __('general.Author')),
            new Field\Textarea($this->nameField('raw'), $this->valueField('raw'), __('general.meta_raw')),
        ];

        $result = '';

        /** @var FieldInterface $field */
        foreach ($fields as $field) {
            if ($this->isStatic()) {
                $field->setStatic(true);
            }

            $result .= "\n" . $field->render();
        }

        return $result;
    }

    public function setValueField($key, $value)
    {
        $this->values[$key] = $value;
        return $this;
    }

    public function setValues(array $values)
    {
        $this->values = $values;

        return $this;
    }
}