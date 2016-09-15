<?php

namespace App\Widgets;


use Illuminate\Http\Request;

class TextWidget extends WidgetBase implements WidgetInterface
{

    /**
     * @return string
     */
    public static function title()
    {
        return trans('a.TextWidget');
    }

    public function form()
    {
        return view('admin.widget.form.text', ['model' => $this->model]);
    }

    public function beforeSave(Request $request)
    {
        $this->model->content = $request->input('content');
    }

    public function render()
    {
        return $this->view('widget.html')->render();
    }
}