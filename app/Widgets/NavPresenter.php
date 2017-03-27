<?php
/**
 * Author: mike
 * Date: 25.03.17
 * Time: 16:15
 */

namespace App\Widgets;


use App\Contracts\NestedMenuInterface;
use Illuminate\Support\Collection;
use Mikelmi\MksAdmin\Form\AdminModelForm;

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
        $attr = array_merge(
            (array) $this->model->param('nav_attr'),
            ['class' => $this->getNavClass()]
        );

        $nav = '<ul ' . html_attr($attr) . '>' . $this->renderItems($this->getItems()) . '</ul>';

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
            $result .= ' flex-column';
        }

        return $result;
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
            if ($this->maxDepth > -1 && $item->getDepth() > $this->maxDepth) {
                continue;
            }

            $hasChildren = $item->hasChildren();

            $liAttr = $liAttr = ['class' => $this->navItemClass];

            if ($hasChildren) {
                $liAttr['class'] .= ' dropdown';
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

        $aAttr = [
            'class' => $this->navLinkClass,
            'href' => $url,
        ];

        if ($item->isCurrent()) {
            $aAttr['class'] .= ' active';
        }

        if ($item->hasChildren()) {
            $aAttr['class'] .= ' dropdown-toggle';

            if ($url == '#') {
                $aAttr['data-toggle'] = 'dropdown';
                $aAttr['role'] = 'button';
                $aAtrr['aria-haspopup'] = 'true';
                $aAttr['aria-expanded'] = 'false';
            } else {
                $aAttr['class'] .= ' dropdown-hover';
            }
        }

        return sprintf('<a %s>%s</a>', html_attr($aAttr), $item->getTitle());
    }

    /**
     * @param null $mode
     * @return array
     */
    public function formFields($mode = null): array
    {
        return [
            ['name' => 'params[nav_type]', 'label' => __('general.Type'),
                'type' => 'radio',
                'value' => $this->model->param('nav_type', 'h'),
                'options' => [
                    'h' => __('general.Horizontal'),
                    'v' => __('general.Vertical'),
                ]
            ],
            ['name' => 'params[nav_align]', 'label' => __('general.Align'),
                'type' => 'radio',
                'value' => $this->model->param('nav_align', 'left'),
                'options' => [
                    'left' => __('general.align_left'),
                    'center' => __('general.align_center'),
                    'right' => __('general.align_right'),
                    'justify' => __('general.align_justify'),
                ]
            ],
            ['name' => 'params[nav_attr]', 'label' => __('general.html_attr'),
                'type' => 'assoc', 'value' => $this->model->param('nav_attr')],
        ];
    }

}