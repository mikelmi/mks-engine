<?php

namespace App\Presenters;


use App\Contracts\NestedMenuInterface;
use Illuminate\Support\Collection;

class LinksMenuPresenter extends ListMenuPresenter
{
    /**
     * @return string
     */
    public static function title()
    {
        return 'links';
    }

    public function render(Collection $items, array $attrs = [])
    {
        if ($items->count() == 0) {
            return '';
        }

        $attrs['role'] = 'navigation';

        return '<nav ' . html_attr($attrs) . '>' . $this->renderItems($items) . '</nav>';
    }

    protected function renderItems(Collection $items, &$result = '')
    {
        /** @var NestedMenuInterface $item */
        foreach ($items as $item) {

            $attrs = [
                'href' => $item->getUrl(),
            ];

            if ($item->isCurrent()) {
                $attrs['class'] = 'active';
            }

            $result .= '<a ' . html_attr($attrs) . '>' . $item->getTitle() . '</a>';
        }

        return $result;
    }
}