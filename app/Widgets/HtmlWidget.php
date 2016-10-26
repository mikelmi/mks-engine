<?php

namespace App\Widgets;


use Illuminate\Http\Request;

class HtmlWidget extends WidgetBase implements WidgetInterface
{

    /**
     * @return string
     */
    public static function title()
    {
        return trans('general.HtmlWidget');
    }

    public function form()
    {
        return view('admin.widget.form.html', ['model' => $this->model]);
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