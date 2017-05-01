<?php
/**
 * Author: mike
 * Date: 27.04.17
 * Time: 12:09
 */

namespace App\DataGrid\Columns;


use Mikelmi\MksAdmin\DataGrid\Columns\Column;

class ColumnList extends Column
{
    protected $searchType = '';

    /**
     * @var string
     */
    protected $searchKey = '';

    /**
     * @var array
     */
    protected $options = [];

    public function renderSearch(): string
    {
        if ($this->searchType) {
            return parent::renderSearch();
        }

        $input = '';

        if ($this->searchable) {
            $attr = [
                'st-search' => $this->searchKey ?: $this->key,
                'class' => 'form-control form-control-sm form-block',
                'placeholder' => $this->title,
                'mks-select',
            ];

            $input = '<select ' . html_attr($attr) . '>';
            $input .= '<option value=""></option>';

            foreach($this->getOptions() as $key => $label) {
                $input .= '<option value="' . e($key) . '">' . e($label) . '</option>';
            }

            $input .= '</select>';
        }

        return sprintf('<th>%s</th>', $input);
    }

    /**
     * @return array
     */
    public function getOptions(): array
    {
       return $this->options;
    }

    /**
     * @param array $options
     * @return $this
     */
    public function setOptions(array $options)
    {
        $this->options = $options;

        return $this;
    }

    /**
     * @return string
     */
    public function getSearchKey(): string
    {
        return $this->searchKey;
    }

    /**
     * @param string $searchKey
     * @return ColumnList
     */
    public function setSearchKey(string $searchKey): ColumnList
    {
        $this->searchKey = $searchKey;
        return $this;
    }
}