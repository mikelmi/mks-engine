<?php

namespace App\Models;


use App\Traits\OrderByName;
use Zizaco\Entrust\EntrustPermission;

/**
 * Class Permission
 * @package App\Models
 *
 * @property string $name
 * @property string $display_name
 * @property string $description
 */
class Permission extends EntrustPermission
{
    use OrderByName;

    protected $fillable = ['name', 'display_name', 'description'];
}