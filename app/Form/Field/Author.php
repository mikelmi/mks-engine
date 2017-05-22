<?php
/**
 * Author: mike
 * Date: 22.05.17
 * Time: 13:31
 */

namespace App\Form\Field;


use App\User;
use Illuminate\Contracts\Auth\Access\Gate;
use Mikelmi\MksAdmin\Form\Field\StaticText;

class Author extends StaticText
{
    public function __construct($name = null, $value = null, $label = null)
    {
        parent::__construct($name, $value, $label);

        if (!$label) {
            $this->label = __('general.Author');
        }
    }

    public function renderInput(): string
    {
        $id = $this->value;

        if (!$id || !($user = User::find($id))) {
            return '';
        }

        $text = e($user->name);

        if (resolve(Gate::class)->allows('admin.users.*')) {
            $text = sprintf(
                '<a href="%s">%s</a>',
                hash_url('user/show/' . $id),
                $text
            );
        }

        return '<p class="form-control-static">'.$text.'</p>';
    }

    public function renderStaticInput(): string
    {
        return $this->renderInput();
    }
}