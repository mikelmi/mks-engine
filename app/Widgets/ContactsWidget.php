<?php

namespace App\Widgets;

use Mikelmi\MksAdmin\Form\AdminModelForm;


class ContactsWidget extends WidgetPresenter
{
    /**
     * @return string
     */
    public function title(): string
    {
        return __('general.ContactsWidget');
    }

    /**
     * @return string
     */
    public function alias(): string
    {
        return 'contacts';
    }

    public function form(AdminModelForm $form, $mode = null)
    {
        $form->addGroup('text', [
            'title' => $this->title(),
            'fields' => [
                ['name' => 'params[email]', 'nameSce' => 'params.email', 'label' => 'E-mail', 'type' => 'email',
                    'value' => $this->model->param('email')
                ],
                ['name' => 'content', 'label' => __('general.Text'), 'type' => 'editor', 'rows' => 5]
            ]
        ]);
    }

    public function render(): string
    {
        $user = \Auth::user();

        return $this->view('widget.contacts', [
            'showFeedbackForm' => $this->model->param('email'),
            'email' => $user ? $user->email : null,
            'name' => $user ? $user->name : null,
        ])->render();
    }

    public function rules(): array
    {
        return [
            'params.email' => 'email',
        ];
    }
}