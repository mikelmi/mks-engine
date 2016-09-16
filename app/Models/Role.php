<?php

namespace App\Models;


use Zizaco\Entrust\EntrustRole;

class Role extends EntrustRole
{
    const ADMIN = 'admin';
    const USER = 'user';

    protected $appends = ['is_system', 'non_selectable'];

    protected function systemNames()
    {
        return [self::ADMIN, self::USER];
    }

    public function getIsSystemAttribute()
    {
        return isset($this->attributes['name']) && in_array($this->attributes['name'], $this->systemNames());
    }

    public function getNonSelectableAttribute()
    {
        return $this->getIsSystemAttribute();
    }

    public function scopeNotSystem($query)
    {
        return $query->whereNotIn('name', $this->systemNames());
    }

    public function permissions()
    {
        return $this->perms();
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('name');
    }
}