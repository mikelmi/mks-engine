<?php
/**
 * Author: mike
 * Date: 20.03.17
 * Time: 16:37
 */

namespace App\Form\Field;


use App\Models\Page;
use Mikelmi\MksAdmin\Form\Field\Select2;

class SelectPages extends Select2
{
    public function __construct($name = null, $value = null, $label = null)
    {
        parent::__construct($name, $value, $label);

        $this->setOptions(Page::ordered()->pluck('title', 'id')->toArray());
    }
}