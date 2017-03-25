<?php

namespace App\Widgets;


use Mikelmi\MksAdmin\Form\AdminModelForm;

class TextWidget extends WidgetPresenter
{
    /**
     * @return string
     */
    public function title(): string
    {
        return trans('general.TextWidget');
    }

    public function alias(): string
    {
        return 'text';
    }

    public function form(AdminModelForm $form, $mode = null)
    {
        $form->addGroup('text', [
            'title' => $this->title(),
            'fields' => [
                ['name' => 'content', 'label' => __('general.Text'), 'type' => 'textarea', 'rows' => 5]
            ]
        ]);
    }
}