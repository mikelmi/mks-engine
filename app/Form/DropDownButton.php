<?php
/**
 * Author: mike
 * Date: 24.03.17
 * Time: 14:24
 */

namespace App\Form;


use Mikelmi\MksAdmin\Form\Button;

class DropDownButton extends Button
{
    /**
     * @var array
     */
    protected $items = [];

    private $id;

    protected function getClass(): string
    {
        return parent::getClass() . ' dropdown-toggle';
    }

    /**
     * @return array
     */
    public function getItems(): array
    {
        return $this->items;
    }

    /**
     * @param array $items
     * @return DropDownButton
     */
    public function setItems(array $items): DropDownButton
    {
        foreach ($items as $item) {
            $this->addItem($item);
        }
        return $this;
    }

    /**
     * @param $item
     * @return DropDownButton
     */
    public function addItem($item): DropDownButton
    {
        if ($item instanceof DropDownItem) {
            $this->items[] = $item;
        } elseif(is_array($item)) {
            $this->items[] = DropDownItem::make($item);
        } elseif (is_string($item)) {
            $this->items[] = DropDownItem::make(['title' => $item]);
        }

        return $this;
    }

    protected function defaultAttributes(): array
    {
        $result = parent::defaultAttributes();

        $result['type'] = 'button';
        $result['data-toggle'] = 'dropdown';
        $result['aria-haspopup'] = 'true';
        $result['aria-expanded'] = 'false';
        $result['id'] = $this->id();

        return $result;
    }

    /**
     * @return string
     */
    public function id(): string
    {
        if (!isset($this->id)) {
            $this->id = 'dropdown-' . uniqid();
        }

        return $this->id;
    }

    /**
     * @return string
     */
    public function render(): string
    {
        $result = '<div class="dropwown btn-group">' . parent::render();
        $result .= '<div class="dropdown-menu" aria-labelledby="' . $this->id() . '">';

        /** @var DropDownItem $item */
        foreach ($this->items as $item) {
            $result .= $item->render();
        }

        $result .= '</div></div>';

        return $result;
    }
}