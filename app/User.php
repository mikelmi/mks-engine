<?php

namespace App;

use App\Models\Role;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Zizaco\Entrust\Traits\EntrustUserTrait;

/**
 * Class User
 * @package App
 *
 * @property int id
 * @property string name
 * @property string email
 * @property string password
 * @property boolean active
 * @property \DateTime created_at
 * @property \DateTime updated_at
 */
class User extends Authenticatable
{
    use EntrustUserTrait;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    protected $appends = ['is_current', 'non_selectable'];

    public function isAdmin()
    {
        return $this->hasRole(Role::ADMIN);
    }

    public function getIsCurrentAttribute()
    {
        return isset($this->attributes['id']) && $this->attributes['id'] == auth()->id();
    }

    public function getNonSelectableAttribute()
    {
        return isset($this->attributes['id']) && $this->attributes['id'] == auth()->id();
    }

    public function getActiveAttribute($value) {
        return (boolean) $value;
    }

    public function scopeNotCurrent(Builder $query)
    {
        return $query->where('id', '!=', auth()->id());
    }
}
