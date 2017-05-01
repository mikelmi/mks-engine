<?php
/**
 * Author: mike
 * Date: 09.04.17
 * Time: 22:31
 */

namespace App\Form\Field;


use Mikelmi\MksAdmin\Form\Field\Custom;

class ImagesPicker extends Custom
{
    /**
     * @var bool
     */
    private $hasMain = false;

    /**
     * @var string
     */
    private $url = '';

    /**
     * @return bool
     */
    public function isHasMain(): bool
    {
        return $this->hasMain;
    }

    /**
     * @param bool $hasMain
     * @return ImagesPicker
     */
    public function setHasMain(bool $hasMain): ImagesPicker
    {
        $this->hasMain = $hasMain;
        return $this;
    }

    /**
     * @return string
     */
    public function getUrl(): string
    {
        return $this->url;
    }

    /**
     * @param string $url
     * @return ImagesPicker
     */
    public function setUrl(string $url): ImagesPicker
    {
        $this->url = $url;
        return $this;
    }

    public function renderInput(): string
    {
        $attr = $this->getAttributes();

        unset($attr['class']);

        $attr['id'] = 'images-picker-' . uniqid();

        if ($url = $this->getUrl()) {
            $attr['url'] = $url;
        }

        if ($this->hasMain) {
            $attr['pick-main'] = 'true';
        }

        return '<mks-images-picker ' . html_attr($attr) . '></mks-images-picker>';
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