<?php
/**
 * Author: mike
 * Date: 17.03.17
 * Time: 19:11
 */

namespace App\Form\Field;


use Mikelmi\MksAdmin\Form\Field\Textarea;

class Editor extends Textarea
{
    /**
     * @var array
     */
    protected $options = [];

    /**
     * @return array
     */
    public function getOptions(): array
    {
        return $this->options;
    }

    /**
     * @param array $options
     * @return Editor
     */
    public function setOptions(array $options): Editor
    {
        foreach ($options as $name => $value) {
            $this->setOption($name);
        }

        return $this;
    }

    /**
     * @param $name
     * @param $value
     * @return Editor
     */
    public function setOption($name, $value): Editor
    {
        $this->options[$name] = $value;
        return $this;
    }

    function __call($name, $arguments)
    {
        if (strpos($name, 'set') !== false) {
            $prop = lcfirst(preg_replace('/^set/', '', $name));
            if ($prop) {
                $arg = $arguments;
                array_unshift($arg, $prop);
                return call_user_func_array([$this, 'setOption'], $arg);
            }
        }

        throw new \LogicException("Method $name not found");
    }

    /**
     * @return array
     */
    protected function getDefaultAttributes(): array
    {
        $result = parent::getDefaultAttributes();

        $options = $this->getOptions();

        if ($options) {
            $options = json_encode($options);
        } else {
            $options = true;
        }

        $result['mks-editor'] = $options;

        return $result;
    }

}