<?php

namespace App\Widgets;

class ContactsWidget extends HtmlWidget
{

    /**
     * @return string
     */
    public static function title()
    {
        return trans('general.ContactsWidget');
    }

    public function form()
    {
        return view('admin.widget.form.contacts', ['model' => $this->model]);
    }
    
    public function render()
    {
        $user = \Auth::user();

        return $this->view('widget.contacts', [
            'showFeedbackForm' => $this->model->param('email'),
            'email' => $user ? $user->email : null,
            'name' => $user ? $user->name : null,
        ])->render();
    }

    public function rules()
    {
        return [
            'params.email' => 'email',
        ];
    }
}