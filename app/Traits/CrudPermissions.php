<?php
/**
 * Author: mike
 * Date: 21.03.17
 * Time: 17:23
 */

namespace App\Traits;


use Illuminate\Support\Facades\Gate;

trait CrudPermissions
{
    protected function setupMiddlewares()
    {
        $prefix = 'permission:admin.';

        if (property_exists($this, 'permissionsPrefix')) {
            $prefix .= $this->permissionsPrefix . '.';
        }

        $this->middleware($prefix.'*')->only('index');
        $this->middleware($prefix.'create')->only(['create', 'store']);
        $this->middleware($prefix.'edit')->only(['edit', 'update']);
        $this->middleware($prefix.'delete')->only('delete');
        $this->middleware($prefix.'toggle|'.$prefix.'|edit')->only(['toggle', 'toggleBatch']);
        $this->middleware($prefix.'move|'.$prefix.'|edit')->only('move');
        $this->middleware($prefix.'create|'.$prefix.'|edit')->only('create');
    }

    /**
     * @param $name
     * @return string
     */
    private function permissionName($name)
    {
        $prefix = 'admin.';

        if (property_exists($this, 'permissionsPrefix')) {
            $prefix .= $this->permissionsPrefix . '.';
        }

        return $prefix . $name;
    }

    protected function canCreate($model = null)
    {
        return $this->canAction('create', $model);
    }

    /**
     * @param null $model
     * @return bool
     */
    protected function canEdit($model = null)
    {
        return $this->canAction('edit', $model);
    }

    /**
     * @param null $model
     * @return bool
     */
    protected function canDelete($model = null)
    {
        return $this->canAction('delete', $model);
    }

    /**
     * @param null $model
     * @return bool
     */
    protected function canToggle($model = null)
    {
        return $this->canAction('toggle', $model) || $this->canEdit($model);
    }

    /**
     * @param $action
     * @param null $model
     * @return bool
     */
    protected function canAction($action, $model = null)
    {
        return Gate::allows($this->permissionName($action), $model);
    }
}