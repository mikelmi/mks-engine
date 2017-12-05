<?php

namespace App\Widgets;

use Mikelmi\MksAdmin\Form\AdminModelForm;

class SliderWidget extends WidgetPresenter
{
    /**
     * @return string
     */
    public function alias(): string
    {
        return  'slider';
    }

    /**
     * @return string
     */
    public function title(): string
    {
        return __('general.SliderWidget');
    }

    public function form(AdminModelForm $form, $mode = null)
    {
        $form->addGroup('slider', [
            'title' => $this->title(),
            'fields' => [
                ['name' => 'params[slides]', 'nameSce' => 'params.slides', 'label' => __('general.Slides'),
                    'type' => 'custom',
                    'view' => 'admin.form.field.slider',
                    'value' => json_encode($this->model->param('slides', []))
                ]
            ]
        ]);
    }
    
    public function render(): string
    {
        return $this->view('widget.slider')->render();
    }
}