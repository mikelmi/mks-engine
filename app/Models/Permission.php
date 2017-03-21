<?php

namespace App\Models;


use App\Traits\OrderByName;
use Zizaco\Entrust\EntrustPermission;

/**
 * Class Permission
 * @package App\Models
 */
class Permission extends EntrustPermission
{
    use OrderByName;
}