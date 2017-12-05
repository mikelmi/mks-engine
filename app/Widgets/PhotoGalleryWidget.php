<?php

namespace App\Widgets;


use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Mikelmi\MksAdmin\Form\AdminModelForm;

class PhotoGalleryWidget extends WidgetPresenter
{

    /**
     * @return string
     */
    public function title(): string
    {
        return __('general.PhotoGalleryWidget');
    }

    /**
     * @return string
     */
    public function alias(): string
    {
        return 'photo';
    }

    public function form(AdminModelForm $form, $mode = null)
    {
        $form->addGroup('photo', [
            'title' => $this->title(),
            'fields' => [
                ['name' => 'params[photos]', 'nameSce' => 'params.photos', 'label' => __('general.Photos'),
                    'type' => 'images',
                    'url' => $this->model->id ? route('admin::widget.photos', $this->model->id) : null
                ]
            ]
        ]);
    }

    public function render(): string
    {
        $photos = json_decode($this->model->param('photos'), true);

        if (!is_array($photos)) {
            $photos = [];
        }

        return $this->view('widget.photo-gallery', compact('photos'))->render();
    }
}