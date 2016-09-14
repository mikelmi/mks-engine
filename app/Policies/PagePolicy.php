<?php

namespace App\Policies;

use App\User;
use App\Models\Page;
use Illuminate\Auth\Access\HandlesAuthorization;

class PagePolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view the page.
     *
     * @param  User  $user
     * @param  Page  $page
     * @return mixed
     */
    public function view(User $user, Page $page)
    {
        $rolesParam = $page->param('roles');

        if ($rolesParam) {
            if ($rolesParam == '1') {
                return $user->id > 0;
            } else {
                $roles = $page->roles->pluck('name')->all();
                $result = $user->hasRole([$roles]);

                return $rolesParam == '-1' ? !$result : $result;
            }
        } else {
            return true;
        }
    }
}
