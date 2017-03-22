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
        $p = 'permission:';
        $prefix = 'admin.';

        if (property_exists($this, 'permissionsPrefix')) {
            $prefix .= $this->permissionsPrefix . '.';
        }

        $p .= $prefix;

        $this->middleware($p.'*')->only('index');
        $this->middleware($p.'create')->only(['create', 'store']);
        $this->middleware($p.'edit')->only(['edit', 'update']);
        $this->middleware($p.'delete')->only(['delete', 'toTrash']);
        $this->middleware($p.'toggle|'.$prefix.'edit')->only(['toggle', 'toggleBatch']);
        $this->middleware($p.'move|'.$prefix.'edit')->only('move');
        $this->middleware($p.'create')->only('create');
        $this->middleware($p.'delete|'.$prefix.'edit')->only('restore');
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

    public function canRestore($model = null)
    {
        return $this->canDelete($model) || $this->canEdit($model);
    }
}