<?php
/**
 * Author: mike
 * Date: 12.05.17
 * Time: 19:41
 */

namespace App\DataGrid\Columns;


use App\Services\CategoryManager;

class ColumnSection extends ColumnCategory
{
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

            /** @var CategoryManager $cm */
            $cm = resolve(CategoryManager::class);

            $options = $cm->getSelectSections($this->categoryType);

            foreach ($options as $section) {
                $input .= '<option value="' . $section['id'] . '">' . e($section['text']) . '</option>';
            }



            $input .= '</select>';
        }

        return sprintf('<th>%s</th>', $input);
    }

}