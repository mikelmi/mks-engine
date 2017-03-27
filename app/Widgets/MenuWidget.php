<?php
/**
 * Author: mike
 * Date: 25.03.17
 * Time: 16:23
 */

namespace App\Widgets;


use App\Models\Menu;
use App\Models\MenuItem;
use Illuminate\Support\Collection;
use Mikelmi\MksAdmin\Form\AdminModelForm;

class MenuWidget extends NavPresenter
{
    protected function getItems(): Collection
    {
        return MenuItem::getTree($this->model->content);
    }

    /**
     * @return string
     */
    public function title(): string
    {
        return __('general.Menu');
    }

    /**
     * @return string
     */
    public function alias(): string
    {
        return 'menu';
    }

    public function form(AdminModelForm $form, $mode = null)
    {
        $fields = [
            ['name' => 'content', 'label' => __('general.Menu'), 'type' => 'select2',
                'options' => Menu::ordered()->pluck('name', 'id')->toArray(),
                'allowEmpty' => false,
                'required' => true
            ]
        ];


        $form->addGroup('menu', [
            'title' => __('general.Menu'),
            'fields' => array_merge($fields, $this->formFields())
        ]);
    }

    public function rules(): array
    {
        return [
            'content' => 'required'
        ];
    }


}