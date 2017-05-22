<?php
/**
 * Author: mike
 * Date: 12.05.17
 * Time: 20:17
 */

namespace App\DataGrid\Columns;


use Illuminate\Contracts\Auth\Access\Gate;
use Mikelmi\MksAdmin\DataGrid\Columns\Column;

class ColumnUser extends Column
{
    protected $id;

    /**
     * @param mixed $id
     * @return ColumnUser
     */
    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }

    protected function cell(): string
    {
        $v = 'row.'.$this->getKey();

        if (!$this->id || resolve(Gate::class)->denies('admin.users.*')) {
            return '{{'.$v.'}}';
        }

        return sprintf(
            '<a ng-href="%s">{{%s}}</a>',
            hash_url('user/show/{{row.'.$this->id.'}}'),
            $v
        );
    }
}