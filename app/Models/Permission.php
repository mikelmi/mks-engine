<?php

namespace App\Models;


use Zizaco\Entrust\EntrustPermission;

class Permission extends EntrustPermission
{
    public function scopeOrdered($query)
    {
        return $query->orderBy('name');
    }
}