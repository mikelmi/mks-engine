<?php
/**
 * Author: mike
 * Date: 03.04.17
 * Time: 18:02
 */

namespace App\Form\Field;


use Illuminate\Database\Eloquent\Model;
use Mikelmi\MksAdmin\Form\Field\Select2;

class TagsSelect extends Select2
{
    protected $multiple = true;

    /** @var  Model */
    protected $model;

    public function __construct($name = null, $value = null, $label = null)
    {
        parent::__construct($name, $value, $label ?: __('general.Tags'));
    }

    /**
     * @param Model $model
     */
    public function setModel(Model $model)
    {
        $this->model = $model;
    }

    protected function getDefaultAttributes(): array
    {
        $params = [];

        if ($this->model) {
            $params['type'] = get_class($this->model);
            $params['id'] = $this->model->getKey();
        }

        $this->setUrl(route('admin::tags', $params));

        $result = parent::getDefaultAttributes();

        $result['data-tags'] = 'true';

        return $result;
    }


}