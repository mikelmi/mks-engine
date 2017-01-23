<?php

namespace App\Widgets;


use Illuminate\Http\Request;

class SearchWidget extends WidgetBase implements WidgetInterface
{

    /**
     * @return string
     */
    public static function title()
    {
        return trans('general.SearchWidget');
    }

    public function form()
    {
        return view('admin.widget.form.search', ['model' => $this->model]);
    }

    public function beforeSave(Request $request)
    {
        $this->model->content = $request->input('content', '');
    }
    
    public function render()
    {
        return $this->view('widget.search')->render();
    }
}