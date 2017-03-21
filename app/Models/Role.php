<?php

namespace App\Models;


use App\Traits\OrderByName;
use Illuminate\Database\Eloquent\Builder;
use Zizaco\Entrust\EntrustRole;

/**
 * Class Role
 * @package App\Models
 *
 * @property int $id
 * @property string $name
 * @property string $display_name
 * @property string $description
 * @property bool $isSystem
 * @property bool $nonSelectable
 *
 * @method static Builder notSystem()
 */
class Role extends EntrustRole
{
    use OrderByName;

    const ADMIN = 'admin';
    const USER = 'user';

    protected $appends = ['is_system', 'non_selectable'];

    protected $fillable = ['name', 'display_name', 'description'];

    /**
     * @return array
     */
    protected function systemNames()
    {
        return [self::ADMIN];
    }

    /**
     * @return bool
     */
    public function getIsSystemAttribute()
    {
        return isset($this->attributes['name']) && in_array($this->attributes['name'], $this->systemNames());
    }

    /**
     * @return bool
     */
    public function getNonSelectableAttribute()
    {
        return $this->getIsSystemAttribute();
    }

    /**
     * @param $query
     * @return mixed
     */
    public function scopeNotSystem($query)
    {
        return $query->whereNotIn('name', $this->systemNames());
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function permissions()
    {
        return $this->perms();
    }

    /**
     * @return bool
     */
    public function isSystem(): bool
    {
        return $this->getIsSystemAttribute();
    }
}