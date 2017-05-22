<?php
/**
 * Author: mike
 * Date: 24.03.17
 * Time: 20:28
 */

namespace App\Traits;


use App\Models\Role;

/**
 * Trait HasRoles
 * @package App\Traits
 *
 * @property Role[] $roles
 */
trait HasRoles
{
    /**
     * @return \Illuminate\Database\Eloquent\Relations\MorphToMany
     */
    public function roles()
    {
        return $this->morphToMany(Role::class, 'model', 'model_role');
    }

    public function syncRoles(array $roles = [])
    {
        if (!$roles) {
            $this->roles()->detach();
        } else {
            $this->roles()->sync($roles);
        }
    }
}