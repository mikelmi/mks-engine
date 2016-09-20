<?php

namespace App;

use App\Notifications\ResetPassword;
use Illuminate\Cache\TaggableStore;
use Illuminate\Notifications\Notifiable;
use App\Models\Role;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Facades\Cache;
use Mikelmi\MksAdmin\Notifications\ResetAdminPassword;
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
    use Notifiable;
    use EntrustUserTrait {
        can as entrustCan;
        cachedRoles as entrustCachedRoles;
    }

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'active'
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

    public function can($ability, $arguments = [])
    {
        if (!parent::can($ability, $arguments)) {
            return $this->entrustCan($ability, $arguments);
        }

        return true;
    }

    public function cachedRoles()
    {
        if(Cache::getStore() instanceof TaggableStore) {
            return $this->entrustCachedRoles();
        }

        //fix create new query for each method's call
        return $this->roles;
    }

    public function sendPasswordResetNotification($token)
    {
        if (!$this->isAdmin()) {
            return $this->notify(new ResetPassword($token));
        }

        $this->notify(new ResetAdminPassword($token));
    }
}
