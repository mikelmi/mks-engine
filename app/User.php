<?php

namespace App;

use App\Notifications\ResetPassword;
use Illuminate\Cache\TaggableStore;
use Illuminate\Notifications\Notifiable;
use App\Models\Role;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Facades\Cache;
use Mikelmi\MksAdmin\Contracts\AdminableUserInterface;
use Mikelmi\MksAdmin\Notifications\ResetAdminPassword;
use Mikelmi\MksAdmin\Traits\AdminableUser;
use Zizaco\Entrust\Traits\EntrustUserTrait;

/**
 * Class User
 * @package App
 *
 * @property int id
 * @property string name
 * @property string email
 * @property string password
 * @property bool active
 * @property \DateTime created_at
 * @property \DateTime updated_at
 * @property string activation_token
 * @property bool $is_current
 * @property bool $non_selectable
 *
 * @method static Builder notCurrent()
 * @method static Builder admins()
 */
class User extends Authenticatable implements AdminableUserInterface
{
    use Notifiable;
    use EntrustUserTrait {
        can as entrustCan;
        cachedRoles as entrustCachedRoles;
    }
    use AdminableUser;

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

    public function isSuperAdmin(): bool
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

    public function scopeAdmins(Builder $query)
    {
        return $query->where('active', true)->whereHas('roles', function($q) {
            return $q->where('name', Role::ADMIN);
        });
    }

    public function can($ability, $arguments = false)
    {
        if ($this->isSuperAdmin()) {
            return true;
        }

        return $this->entrustCan($ability, $arguments);
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
        if (!$this->isSuperAdmin()) {
            return $this->notify(new ResetPassword($token));
        }

        $this->notify(new ResetAdminPassword($token));
    }

    public function generateToken()
    {
        return hash_hmac('sha256', str_random(40), config('app.key'));
    }

    /**
     * @return bool
     */
    public function isCurrent()
    {
        return $this->getIsCurrentAttribute();
    }
}
