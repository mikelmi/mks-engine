<?php
/**
 * Author: mike
 * Date: 25.03.17
 * Time: 16:15
 */

namespace App\Widgets;


use App\Contracts\NestedMenuInterface;
use Illuminate\Support\Collection;

abstract class NavPresenter extends WidgetPresenter
{
    /**
     * @var string
     */
    protected $navClass = 'nav';

    protected $navItemClass = 'nav-item';

    protected $navLinkClass = 'nav-link';

    /**
     * @var int
     */
    protected $maxDepth = -1;

    /**
     * @return Collection
     */
    abstract protected function getItems(): Collection;

    /**
     * @return string
     */
    public function render(): string
    {
        $items = $this->getItems();

        $attr = (array) $this->model->param('nav_attr');

        if ($this->model->param('nav_type') == 's') {
            $items = $this->renderSimpleItems($items);
        } else {
            $items = $this->renderItems($items);
            $attr['class'] = $this->getNavClass();
        }

        $nav = '<ul ' . html_attr($attr) . '>' . $items . '</ul>';

        return $this->view('widget.nav', compact('nav'))->render();
    }

    /**
     * @return string
     */
    protected function getNavClass(): string
    {
        $result = $this->navClass;

        $class = array_get((array) $this->model->param('nav_attr'), 'class');

        if ($class) {
            $result .= ' ' . $class;
        }

        if ($align = $this->model->param('nav_align')) {
            $class = array_get([
                'center' => 'justify-content-center',
                'right' => 'justify-content-center',
                'justify' => 'nav-fill'
            ], $align);

            if ($class) {
                $result .= ' ' . $class;
            }
        }

        if ($this->model->param('nav_type') == 'v') {
            $result .= ' nav-vertical flex-column';
        }

        return $result;
    }

    /**
     * @param Collection $items
     * @return string
     */
    protected function renderSimpleItems(Collection $items): string
    {
        $result = '';

        /** @var NestedMenuInterface $item */
        foreach ($items as $item) {
            if ($this->maxDepth > -1 && $item->getDepth() > $this->maxDepth) {
                continue;
            }

            $result .= '<li>'. $this->renderSimpleLink($item) . PHP_EOL;

            if ($item->hasChildren()) {
                $result .= '<ul>' . $this->renderSimpleItems($item->getChildren()) . '</ul>';
            }

            $result .= '</li>';
        }

        return $result;
    }

    /**
     * @param NestedMenuInterface $item
     * @return string
     */
    protected function renderSimpleLink(NestedMenuInterface $item): string
    {
        $url = $item->getUrl() ?: '#';

        $attr = $item->htmlAttributes();

        $attr['href'] = $url;

        if ($item->isCurrent()) {
            $attr['class'] = trim(($attr['class'] ?? '') . ' active');
        }

        if ($icon = $item->getIcon()) {
            $icon = '<i class="fa fa-' . $icon . '"></i> ';
        }

        return sprintf('<a %s>%s%s</a>', html_attr($attr), $icon, $item->getTitle());
    }

    /**
     * @param Collection $items
     * @return string
     */
    protected function renderItems(Collection $items): string
    {
        $result = '';

        /** @var NestedMenuInterface $item */
        foreach ($items as $item) {
            $depth = $item->getDepth();
            if ($this->maxDepth > -1 && $depth > $this->maxDepth) {
                continue;
            }

            $hasChildren = $item->hasChildren();

            $liAttr = ['class' => $depth > 0 ? 'subnav-item' : $this->navItemClass];

            if ($hasChildren) {
                $liAttr['class'] .= ' dropdown';
                if ($depth > 0 || $item->getUrl() != '#') {
                    $liAttr['class'] .= ' dropdown-hover';
                }
            }

            $result .= sprintf('<li %s>%s', html_attr($liAttr), $this->renderLink($item)) . PHP_EOL;

            if ($hasChildren) {
                $result .= '<ul class="dropdown-menu">';
                $result .= $this->renderItems($item->getChildren()) . '</ul>';
            }

            $result .= '</li>';
        }

        return $result;
    }

    /**
     * @param NestedMenuInterface $item
     * @return string
     */
    protected function renderLink(NestedMenuInterface $item): string
    {
        $url = $item->getUrl() ?: '#';

        $attr = $item->htmlAttributes();

        $class = $item->getDepth() > 0 ? 'dropdown-item' : $this->navLinkClass;

        $attr['class'] = trim($class .' '. ($attr['class'] ?? ''));
        $attr['href'] = $url;

        if ($item->isCurrent()) {
            $attr['class'] .= ' active';
        }

        if ($item->hasChildren()) {
            $attr['class'] .= ' dropdown-toggle';
            $attr['aria-haspopup'] = 'true';
            $attr['aria-expanded'] = 'false';

            if ($url == '#') {
                $attr['data-toggle'] = 'dropdown';
                $attr['role'] = 'button';
            }
        }

        if ($icon = $item->getIcon()) {
            $icon = '<i class="fa fa-' . $icon . '"></i> ';
        }

        return sprintf('<a %s>%s%s</a>', html_attr($attr), $icon, $item->getTitle());
    }

    /**
     * @return array
     */
    public function formFields(): array
    {
        return [
            ['name' => 'params[nav_type]', 'nameSce' => 'params.nav_type', 'label' => __('general.Type'),
                'type' => 'radio',
                'value' => $this->model->param('nav_type', 'h'),
                'options' => [
                    'h' => __('general.Horizontal'),
                    'v' => __('general.Vertical'),
                    's' => __('general.Simple'),
                ]
            ],
            ['name' => 'params[nav_align]', 'nameSce' => 'params.nav_align', 'label' => __('general.Align'),
                'type' => 'radio',
                'value' => $this->model->param('nav_align', 'left'),
                'options' => [
                    'left' => __('general.align_left'),
                    'center' => __('general.align_center'),
                    'right' => __('general.align_right'),
                    'justify' => __('general.align_justify'),
                ]
            ],
            ['name' => 'params[nav_attr]', 'nameSce' => 'params.nav_attr', 'label' => __('general.html_attr'),
                'type' => 'assoc', 'value' => $this->model->param('nav_attr')],
        ];
    }

}