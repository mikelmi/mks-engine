<?php
/**
 * Author: mike
 * Date: 23.05.17
 * Time: 19:20
 */

namespace App\DataGrid\Columns;


use Mikelmi\MksAdmin\DataGrid\Columns\ColumnStatus;

class ColumnFeatured extends ColumnStatus
{
    /**
     * @return string
     */
    public function cell(): string
    {
        $icon = sprintf('<i class="fa fa-lg fa-thumb-tack"></i>', $this->key);

        if ($this->url) {
            $attr = array_merge([
                'class' => 'btn btn-sm no-b',
                'type' => 'button',
                'ng-class' => sprintf('{\'btn-outline-info\':row.%s,\'btn-outline-secondary\':!row.%1$s}', $this->key),
                'ng-click' => "grid.updateRow(row, '" . $this->url . "/'+row.id)",
                'title' => $this->actionTitle ?: '',
            ], $this->buttonAttributes);

            return sprintf('<button %s>%s</button>', html_attr($attr), $icon);
        }

        $attr = [
            'class' => 'badge',
            'ng-class' => sprintf('{\'badge-info\':row.%s,\'badge-default\':!row.%1$s}', $this->key),
        ];

        return sprintf('<badge %s>%s</badge>', html_attr($attr), $icon);
    }

    /**
     * @return array
     */
    public function getOptions(): array
    {
        if (!$this->options) {
            $this->options = [
                '1' => __('admin::messages.Yes'),
                '0' => __('admin::messages.No'),
            ];
        }

        return $this->options;
    }
}