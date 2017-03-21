<?php
/**
 * Author: mike
 * Date: 16.03.17
 * Time: 16:44
 */

namespace App\Form\Field;

use Mikelmi\MksAdmin\Form\Field\Custom;

class ChangePassword extends Custom
{
    /**
     * @var string
     */
    protected $view = 'admin.form.field.change-password';

    public function render(): string
    {
        if ($this->isStatic()) {
            return '';
        }

        return parent::render();
    }
}